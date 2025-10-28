<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluir la conexión a la base de datos
include "./../../../config/conex.php"; 

require './../../../../librerias/PHPMailer/Exception.php';
require './../../../../librerias/PHPMailer/PHPMailer.php';
require './../../../../librerias/PHPMailer/SMTP.php';

$PATH_BASE = './../../../../'; 

$id_pedido = $_GET['id'] ?? $_POST['id'] ?? 'N/A';
$email_cliente = $_SESSION['email'] ?? 'correo_no_encontrado@dominio.com';
$nombre_cliente = $_SESSION['name'] ?? 'Cliente'; 

//CONSULTA A LA BASE DE DATOS
$items_pedido = [];
$total_pedido = 0;
$fecha_pedido = 'Fecha no disponible'; 

if ($id_pedido !== 'N/A') {
    // Escapar el ID para prevenir inyección SQL
    $id_pedido_safe = $linki->real_escape_string($id_pedido); 

    // Consulta para obtener detalles del pedido y la FECHA CORRECTA
    $sql = "SELECT 
                lp.precio_u as precio, 
                lp.total as total, 
                lp.cantidad as cantida,
                fc.codigo as codpro, 
                fc.descripcion as nombre,
                p.fecha as fecha_pedido
            FROM 
                lista_pedidos lp
            INNER JOIN 
                pedidos p ON lp.id_pedido = p.id 
            INNER JOIN 
                filtro_codificacion fc ON lp.id_producto = fc.id 
            WHERE 
                lp.id_pedido = '$id_pedido_safe'";

    $resultado = $linki->query($sql); 
    
    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $items_pedido[] = $fila;
            $total_pedido += (float)$fila['total'];
            
            // Asignar y formatear la fecha del primer registro
            if ($fecha_pedido === 'Fecha no disponible' && !empty($fila['fecha_pedido'])) {
                 $fecha_pedido = date('Y-m-d', strtotime($fila['fecha_pedido'])); 
            }
        }
    } else {
        $items_pedido = [];
    }
}

// REMITENTE 
$email_remitente = 'jhoselynmercado@webfiltros.com'; 

// DESTINATARIO 
$destinatarios_notificacion = [
    ['email' => $email_cliente, 'nombre' => $nombre_cliente]
];

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'mail.webfiltros.com';
    $mail->SMTPAuth  = true;
    $mail->Username  = $email_remitente; 
    $mail->Password  = 'jhsmer2022*'; 
    $mail->SMTPSecure = 'ssl'; 
    $mail->Port = 465; 

    $mail->setFrom($email_remitente, 'WebFiltros Pedidos'); 

    // Adjuntar y embeber imágenes para el logo
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoweb.png', 'logo_web', 'logoweb.png');
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoj.png', 'logo_jetfilter', 'logoj.png');

   
    //  CONSTRUCCIÓN DE LA TABLA DE DETALLES DEL PEDIDO
   
    $detalle_html = '';
    $unidades_empaque = 1; // Asumimos 1 si no está en la consulta

    foreach ($items_pedido as $item) {
        $precio_u = number_format($item['precio'], 2, ',', '.') . '$';
        $total_item = number_format($item['total'], 2, ',', '.') . '$';
        $cantidad = number_format($item['cantida'], 0, '', '.');

        $detalle_html .= "
            <tr style='background-color: #ffffff;'>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['codpro']) . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['nombre']) . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>" . $cantidad . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>$precio_u</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>$total_item</td>
            </tr>";
    }

    // Enviar el correo personalizado al cliente
    foreach ($destinatarios_notificacion as $destinatario) {
        $mail->clearAddresses();
        $mail->addAddress($destinatario['email'], $destinatario['nombre']);

        $mail->isHTML(true); 
        
        // Asunto con el ID del pedido para unicidad
        $mail->Subject = 'CONFIRMACIÓN ' . htmlspecialchars($id_pedido);
        $mail->CharSet = 'UTF-8';

        // ----------------------------------------------------
        // 🚀 CUERPO DEL EMAIL CON LOS NUEVOS ESTILOS
        // ----------------------------------------------------
        $mail->Body = '
            <div style="font-family: Roboto, Tahoma, Verdana, sans-serif; font-size: 14px; color: #333;">
                
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 10px; text-align: left;">
                             <img src="cid:logo_web" alt="WebFiltros" style="width: 150px; height: auto;">
                        </td>
                    </tr>
                </table>

                <p>Estimado/a <strong>' . htmlspecialchars($destinatario['nombre']) . '</strong>,</p>
                <p>Hemos recibido correctamente su pedido para ser procesado por nuestro equipo.</p>
                
                <h3 style="color: #E2001A;">Detalle Pedido</h3> <p style="font-size: 12px; margin-bottom: 15px; background-color: #f0f0f0; padding: 5px; display: inline-block;">
                    Fecha Pedido: ' . htmlspecialchars($fecha_pedido) . '
                </p>

                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: 1px solid #dee2eec;">
                    <thead>
                        <tr style="background-color: #007bff; color: #ffffff;">
                            <th style="padding: 10px; text-align: left;">Código Prod.</th>
                            <th style="padding: 10px; text-align: left;">Nombre Producto</th>
                            <th style="padding: 10px; text-align: right;">Cantidad</th>
                            <th style="padding: 10px; text-align: right;">Precio Unid</th>
                            <th style="padding: 10px; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $detalle_html . '
                    </tbody>
                </table>
                
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
                    <tr>
                        <td width="70%"></td>
                        <td width="30%" style="background-color: #D3D3D3; padding: 10px; text-align: right; font-size: 16px; font-weight: bold;">
                            Total: ' . number_format($total_pedido, 2, ',', '.') . '$
                        </td>
                    </tr>
                </table>
                
                <div style="border-left: 5px solid #007bff; padding: 10px; background-color: #e9f5ff; margin-top: 30px; font-size: 13px;">
                    <p style="margin: 0;">Se le estará notificando la confirmación del registro de su pedido en nuestro sistema.</p>
                    <p style="margin: 5px 0 0 0;">Gracias por su confianza. </p>
                </div>

                <p style="margin: 20px 0 0 0;">Si tiene alguna pregunta, no dude en contactarnos.</p>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                <div style="text-align: right; font-size: 10px; color: #666;">
                    <p style="margin-bottom: 5px;">
                        <img src="cid:logo_jetfilter" alt="JetFilter" style="width: 150px; height: auto;"> </p>
                    <p>Jetfilter, C.A, Tinaquillo EDO. Cojedes,</p>
                    <p>www.webfiltros.com, RIF J- 00059322-1</p>
                </div>
            </div>
        ';

        $mail->send();
    }

    // Lógica de redirección
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = 'Su pedido fue enviado con éxito. Se ha enviado un correo de confirmación a su email.';
    $_SESSION['icono_alerta'] = 'success';
    $_SESSION['title'] = '¡Pedido Enviado!';
    
    header('Location: ../../index.php?pag=pedido'); 
    exit; 

} catch (Exception $e) {
    // Lógica de error
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = "Error al enviar el pedido. El correo de confirmación no se pudo enviar. {$mail->ErrorInfo}";
    $_SESSION['icono_alerta'] = 'error';
    $_SESSION['title'] = 'Error de Envío';

    header('Location: ../../index.php?pag=pedido');
    exit; 
}
?>