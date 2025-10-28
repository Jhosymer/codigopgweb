<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluir la conexión
include "./../config/conexion.php"; 

// Ajusta las rutas según tu estructura
require './../librerias/PHPMailer/Exception.php';
require './../librerias/PHPMailer/PHPMailer.php';
require './../librerias/PHPMailer/SMTP.php';

$PATH_BASE = './../'; // Ruta base relativa al script actual

// ----------------------------------------------------
// 1. LECTURA DEL JSON Y OBTENCIÓN DE DATOS NECESARIOS
// ----------------------------------------------------

$json_path = 'pedidopro.json'; 
$order_ids = [];
$json_map_by_id = []; 
$pedidos_a_enviar = []; 

if (file_exists($json_path)) {
    $json_content = file_get_contents($json_path);
    $data = json_decode($json_content, true);

    $pedidos_desde_json = is_array($data) ? (isset($data['id']) ? [$data] : $data) : [];

    foreach ($pedidos_desde_json as $pedido_json) {
        if (isset($pedido_json['id'])) {
            $id_web = $pedido_json['id'];
            $order_ids[] = $id_web;
            $json_map_by_id[$id_web] = $pedido_json; 
        }
    }
}


// ----------------------------------------------------
// 2. OBTENCIÓN DE DATOS DE CABECERA 
// ----------------------------------------------------

if (!empty($order_ids)) {
    $placeholders = implode(',', array_fill(0, count($order_ids), '?'));

    // 2.1. OBTENER CABECERA (Datos de cliente y SAP)
    $sql_cabecera = "SELECT 
                        p.id AS id_web, 
                        p.na_pedido AS sap_doc, 
                        u.name AS nombre_cliente, 
                        u.email AS email_cliente,
                        p.fecha AS fecha_pedido
                    FROM 
                        pedidos p 
                    INNER JOIN 
                        users u ON p.id_users = u.id 
                    WHERE 
                        p.id IN ({$placeholders})"; 

    $stmt_cabecera = $base_de_datos->prepare($sql_cabecera);
    foreach ($order_ids as $index => $id) {
        $stmt_cabecera->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $stmt_cabecera->execute();
    $cabeceras = $stmt_cabecera->fetchAll(PDO::FETCH_ASSOC);

    
    // 2.2. OBTENER DETALLE de TODOS los pedidos
    $sql_detalle = "SELECT 
                        lp.id_pedido,
                        lp.precio_u as precio, 
                        lp.total as total, 
                        lp.cantidad as cantida,
                        fc.codigo as codpro, 
                        fc.descripcion as nombre
                    FROM 
                        lista_pedidos lp
                    INNER JOIN 
                        filtro_codificacion fc ON lp.id_producto = fc.id 
                    WHERE 
                        lp.id_pedido IN ({$placeholders})
                    ORDER BY lp.id_pedido";

    $stmt_detalle = $base_de_datos->prepare($sql_detalle);
    foreach ($order_ids as $index => $id) {
        $stmt_detalle->bindValue($index + 1, $id, PDO::PARAM_INT);
    }
    $stmt_detalle->execute();
    $detalles = $stmt_detalle->fetchAll(PDO::FETCH_ASSOC);

    // Agrupar detalles por id_pedido
    $detalles_por_pedido = [];
    foreach ($detalles as $detalle) {
        $detalles_por_pedido[$detalle['id_pedido']][] = $detalle;
    }

    // 2.3. COMBINAR DATOS Y PREPARAR PARA ENVÍO
    foreach ($cabeceras as $cabecera) {
        $id_web = $cabecera['id_web'];
        $pedido_json_data = $json_map_by_id[$id_web] ?? [];
        
        // Lógica de fallback para SAP ID
        $numero_sap_final = $cabecera['sap_doc'];
        if (empty($numero_sap_final) && isset($pedido_json_data['doc_entry'])) {
            $numero_sap_final = $pedido_json_data['doc_entry'];
        }
        if (empty($numero_sap_final)) {
            $numero_sap_final = 'N/A';
        }

        $pedidos_a_enviar[] = [
            'id_web' => $id_web,
            'nombre_cliente' => $cabecera['nombre_cliente'],
            'email_cliente' => $cabecera['email_cliente'],
            'fecha_pedido' => date('Y-m-d', strtotime($cabecera['fecha_pedido'])),
            'numero_sap' => $numero_sap_final,
            'items' => $detalles_por_pedido[$id_web] ?? [],
        ];
    }
}


// ----------------------------------------------------
// 3. CONFIGURACIÓN Y ENVÍO DEL EMAIL (Bucle por Pedido)
// ----------------------------------------------------

$email_remitente = 'jhoselynmercado@webfiltros.com'; 
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP 
    $mail->SMTPDebug = 0; $mail->isSMTP(); $mail->Host = 'mail.webfiltros.com';
    $mail->SMTPAuth  = true; $mail->Username  = $email_remitente; 
    $mail->Password  = 'jhsmer2022*'; $mail->SMTPSecure = 'ssl'; $mail->Port = 465; 
    $mail->setFrom($email_remitente, 'WebFiltros Pedidos'); 

    // Adjuntar y embeber imágenes para el logo 
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoweb.png', 'logo_web', 'logoweb.png');
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoj.png', 'logo_jetfilter', 'logoj.png');

    $envios_exitosos = 0;

    foreach ($pedidos_a_enviar as $pedido) {
        
        $mail->clearAddresses();
        // Saltar si no hay un email válido
        if (empty($pedido['email_cliente']) || $pedido['email_cliente'] === 'correo_no_encontrado@dominio.com') {
             continue; 
        }
        $mail->addAddress($pedido['email_cliente'], $pedido['nombre_cliente']);

        $mail->isHTML(true); 
        $mail->CharSet = 'UTF-8';

        // 3.1. CONSTRUCCIÓN DEL DETALLE HTML DEL PEDIDO
        $detalle_html = '';
        $total_pedido = 0; 
        foreach ($pedido['items'] as $item) {
            $precio_u = number_format($item['precio'], 2, ',', '.') . '$';
            $total_item = number_format($item['total'], 2, ',', '.') . '$';
            $cantidad = number_format($item['cantida'], 0, '', '.');
            $total_pedido += (float)$item['total'];

            $detalle_html .= "
                <tr style='background-color: #ffffff;'>
                    <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['codpro']) . "</td>
                    <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['nombre']) . "</td>
                    <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>" . $cantidad . "</td>
                    <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>$precio_u</td>
                    <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>$total_item</td>
                </tr>";
        }
        
        $total_final_formateado = number_format($total_pedido, 2, ',', '.') . '$';
        $numero_sap_display = htmlspecialchars($pedido['numero_sap']);

        // 3.2. ASUNTO
        $mail->Subject = 'CONFIRMACIÓN ' . htmlspecialchars($pedido['id_web']) ;
        
        // 3.3. CUERPO DEL EMAIL 
        $mail->Body = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Confirmación de Pedido Procesado</title>
                </head>
            <body>

            

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style=" margin: auto; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 20px;">
                                    
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                        <tr>
                                            <td style="text-align: left;">
                                                <img src="cid:logo_web" alt="WebFiltros" style="width: 100px; height: auto; border: 0;"> 
                                            </td>
                                        </tr>
                                    </table>

                                    <p style="margin: 0 0 10px 0;">Estimado/a <strong>' . htmlspecialchars($pedido['nombre_cliente']) . '</strong>,</p>
                                    
                                    <h3 style="color: #E2001A; margin-top: 0; margin-bottom: 10px; font-size: 18px;">¡Su Pedido ha sido Procesado!</h3> 
                                    
                                    <p style="margin: 0 0 20px 0;">Le confirmamos que su pedido  ha sido registrado exitosamente en nuestro sistema administrativo  con la siguiente información:</p>

                                    <p style="margin: 0 0 20px 0;">Número de Pedido' . $numero_sap_display . ' </p>
                                
                                    
                                    <h3 style="color: #007bff; margin-top: 0; margin-bottom: 10px;">Detalle de Productos</h3> 

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse; border: 1px solid #dee2eec; margin-bottom: 10px; font-size: 12px;">
                                        <thead>
                                            <tr style="background-color: #007bff; color: #ffffff;">
                                                <th style="padding: 10px; text-align: left;">Código Prod.</th>
                                                <th style="padding: 10px; text-align: left;">Nombre Producto</th>
                                                <th style="padding: 10px; text-align: right;">Cant.</th>
                                                <th style="padding: 10px; text-align: right;">Precio Unid</th>
                                                <th style="padding: 10px; text-align: right;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ' . $detalle_html . '
                                        </tbody>
                                    </table>
                                    
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 10px;">
                                        <tr>
                                            <td width="70%" style="font-size: 16px;"></td>
                                            <td width="30%" style="background-color: #D3D3D3; padding: 10px; text-align: right; font-size: 16px; font-weight: bold;">
                                                Total: ' . $total_final_formateado . '
                                            </td>
                                        </tr>
                                    </table>

                                    <div style="border-left: 5px solid #E2001A; padding: 10px; background-color: #ffe9ea; margin-top: 30px; font-size: 13px;">
                                        <p style="margin: 0; font-weight: bold; color: #E2001A;">Nota Importante:</p>
                                        <p style="margin: 5px 0 0 0;">Esta confirmación indica que su pedido ya está en nuestro sistema administrativo para su revisión final y posterior procesamiento. Nuestro equipo le contactará para coordinar el despacho. Gracias por su confianza.</p>
                                    </div>

                                    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                                    <div style="text-align: right; font-size: 10px; color: #666;">
                                        <p style="margin-bottom: 5px;">
                                            <img src="cid:logo_jetfilter" alt="JetFilter" style="width: 150px; height: auto; border: 0;"> 
                                        </p>
                                        <p style="margin: 0;">Jetfilter, C.A, Tinaquillo EDO. Cojedes,</p>
                                        <p style="margin: 0;">www.webfiltros.com, RIF J- 00059322-1</p>
                                    </div>

                                </td>
                            </tr>
                        </table>
                        
            
        ';

        if ($mail->send()) {
            $envios_exitosos++;
        }
    } 

    // Lógica de redirección de éxito
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = 'Correos de confirmación de ' . $envios_exitosos . ' pedidos procesados enviados a los clientes con éxito.';
    $_SESSION['icono_alerta'] = 'success';
    $_SESSION['title'] = '¡Avisos Enviados!';
    
    header('Location: ./../../index.php?pag=pedido_admin'); 
    exit; 

} catch (Exception $e) {
    // Lógica de error
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = "Error al enviar avisos a clientes. El correo de confirmación no se pudo enviar. {$mail->ErrorInfo}";
    $_SESSION['icono_alerta'] = 'error';
    $_SESSION['title'] = 'Error de Envío';

    header('Location: ./../../index.php?pag=pedido_admin');
    exit; 
}
?>