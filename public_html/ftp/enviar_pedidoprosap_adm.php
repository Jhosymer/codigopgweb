<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Incluir la conexión a la base de datos PDO. Debe definir $base_de_datos.
include "./../config/conexion.php"; 

// Ajusta las rutas según tu estructura
require './../librerias/PHPMailer/Exception.php';
require './../librerias/PHPMailer/PHPMailer.php';
require './../librerias/PHPMailer/SMTP.php';

$PATH_BASE = './../'; 

// ----------------------------------------------------
// 1. LECTURA DEL JSON Y OBTENCIÓN DE DATOS DEL PEDIDO
// ----------------------------------------------------

$json_path = 'pedidopro.json'; 
$pedidos_cargados = []; // Array que contendrá los datos de la DB
$json_map_by_id = []; // array para mapear datos del JSON

if (file_exists($json_path)) {
    $json_content = file_get_contents($json_path);
    $data = json_decode($json_content, true);

    // Determinar si el JSON es un objeto simple o un array de objetos
    $pedidos_a_buscar = is_array($data) ? (isset($data['id']) ? [$data] : $data) : [];

    $order_ids = array_map(function($p) { return $p['id'] ?? null; }, $pedidos_a_buscar);
    $order_ids = array_filter($order_ids); // IDs de los pedidos de la web

    // Mapear los datos completos del JSON usando el ID como clave
    foreach ($pedidos_a_buscar as $pedido_json) {
        if (isset($pedido_json['id'])) {
            $json_map_by_id[$pedido_json['id']] = $pedido_json;
        }
    }

    if (!empty($order_ids)) {
        
        // Creamos una cadena de marcadores de posición (?, ?, ?) para PDO
        $placeholders = implode(',', array_fill(0, count($order_ids), '?'));

        // CONSULTA: Incluye p.id AS pedido_id para que la lógica de fallback funcione
        $sql = "SELECT p.id AS pedido_id, p.na_pedido AS sap_doc, u.name AS nombre_cliente, u.rif AS rif_cliente FROM pedidos p INNER JOIN users u ON p.id_users = u.id WHERE p.id IN ({$placeholders})"; 

        // EJECUCIÓN CON PDO
        try {
            $stmt = $base_de_datos->prepare($sql);
            
            // Vincular cada ID al marcador de posición
            foreach ($order_ids as $index => $id) {
                // Vincula por 1-base
                $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            // Obtener todos los resultados de la base de datos
            $pedidos_cargados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Manejo de errores de PDO.
            // error_log("Error de consulta PDO: " . $e->getMessage());
            $pedidos_cargados = [];
        }
    }
}
//SOLO CONTINUAR SI HAY PEDIDOS CARGADOS DE LA DB

if (!empty($pedidos_cargados)) {

// ----------------------------------------------------
// 2. CONFIGURACIÓN DEL EMAIL
// ----------------------------------------------------
$destinatarios_notificacion = [];

try {
    // CONSULTA: Obtiene nombre y email de users con id_permiso_admin = 8
    $sql_dest = "SELECT u.name, u.email FROM users u INNER JOIN users_permiso_admin upa ON u.id = upa.id_users WHERE upa.id_permiso_admin = 8";
    
    $stmt_dest = $base_de_datos->prepare($sql_dest);
    $stmt_dest->execute();
    
    // Almacenar los resultados en el array de destinatarios
    $destinatarios_notificacion = $stmt_dest->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    
    $destinatarios_notificacion = [];
}
$email_remitente = 'pedidosweb@webfiltros.com'; 

// Destinatarios internos 
/*$destinatarios_notificacion = [
    ['email' => 'jhoselynmercado@webfiltros.com', 'nombre' => 'Jhoselyn Mercado'],
   ['email' => 'jhoselynmercado72@gmail.com', 'nombre' => 'Jorge Delgadillo']
];*/

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP 
    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host = 'mail.webfiltros.com';
    $mail->SMTPAuth  = true; $mail->Username  = $email_remitente; 
    $mail->Password  = '1234facil'; $mail->SMTPSecure = 'ssl'; $mail->Port = 465; 

    $mail->setFrom($email_remitente, 'WebFiltros Pedidos'); 

    // Adjuntar y embeber imágenes para el logo 
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoweb.png', 'logo_web', 'logoweb.png');
    $mail->addEmbeddedImage($PATH_BASE . 'jetfilter/img/logoj.png', 'logo_jetfilter', 'logoj.png');

    
    // 3. CONSTRUCCIÓN DE LA TABLA DE PEDIDOS CARGADOS
    $detalle_html = '';

    foreach ($pedidos_cargados as $pedido) {
        
        // Eliminar: "C-" si el RIF comienza con esa secuencia
        $rif_final = htmlspecialchars($pedido['rif_cliente'] ?? 'N/A');
        if (strpos($rif_final, 'C-') === 0) {
            $rif_final = substr($rif_final, 2); // Remueve los primeros 2 caracteres ("C-")
        }

        // Prioridad: sap_doc (DB) -> doc_entry (JSON) -> pedido_id (DB)
        $numero_pedido_final = htmlspecialchars($pedido['sap_doc'] ?? ''); // Intenta con p.na_pedido

        if (empty($numero_pedido_final) && isset($pedido['pedido_id'])) {
            // Si sap_doc de la DB está vacío, intenta buscar "doc_entry" en el JSON original
            $id_db = $pedido['pedido_id'];
            if (isset($json_map_by_id[$id_db]['doc_entry'])) {
                $numero_pedido_final = htmlspecialchars($json_map_by_id[$id_db]['doc_entry']);
            }
        }
        
        if (empty($numero_pedido_final)) {
            // Último recurso: usar el ID del pedido de la web (si los otros fallan o son cero)
            $numero_pedido_final = htmlspecialchars($pedido['pedido_id'] ?? 'N/A');
        }


        $detalle_html .= "
            <tr style='background-color: #ffffff;'>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . $rif_final . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($pedido['nombre_cliente'] ?? 'N/A') . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . $numero_pedido_final . "</td>
            </tr>";
    }

    // Asunto del correo para el equipo de administración/SAP
    $mail->Subject = 'AVISO DE PROCESAMIENTO: Pedidos Cargados a SAP - ' . date('d/m/Y H:i:s');
    $mail->CharSet = 'UTF-8';

    // 4. Bucle y Cuerpo del Email
    foreach ($destinatarios_notificacion as $destinatario) {
        $mail->clearAddresses();
        $mail->addAddress($destinatario['email'], $destinatario['nombre']);

        $mail->isHTML(true); 

        // CUERPO DEL EMAIL - Ancho del contenedor ajustado a 500px
        $mail->Body = '
            <div style="font-family: Roboto, Tahoma, Verdana, sans-serif; font-size: 14px; color: #333;">
                
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 10px; text-align: left;">
                            <img src="cid:logo_web" alt="WebFiltros" style="width: 150px; height: auto;">
                        </td>
                        
                    </tr>
                </table>

                <p style="margin: 0 0 10px 0;">Estimado/a <strong>' . htmlspecialchars($destinatario['nombre']) . '</strong>,</p>
                <p style="margin: 0 0 20px 0;">Hemos recibido los siguientes pedidos de nuestros clientes y ya se encuentran cargados en SAP.</p>
                
                <h3 style="color: #333; margin-top: 0; margin-bottom: 20px; font-size: 18px;">Detalles Pedidos</h3> 
                
                <table width="60%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #007bff; color: #ffffff;">
                            <th style="padding: 10px; text-align: left;">RIF</th>
                            <th style="padding: 10px; text-align: left;">Nombre Cliente</th>
                            <th style="padding: 10px; text-align: left;">Pedido en SAP</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $detalle_html . '
                    </tbody>
                </table>
                
                <div style="text-align: right; font-size: 10px; color: #666; margin-top: 50px;">
                    <p style="margin-bottom: 5px;">
                        <img src="cid:logo_jetfilter" alt="JetFilter" style="width: 150px; height: auto;"> 
                    </p>
                    <p style="margin: 0;">Jetfilter, C.A, Tinaquillo EDO. Cojedes,</p>
                    <p style="margin: 0;">www.webfiltros.com, RIF J- 00059322-1</p>
                </div>
            </div>
        ';

        $mail->send();
    }

    // Lógica de redirección de éxito
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = 'Aviso interno de pedidos cargados a SAP enviado con éxito.';
    $_SESSION['icono_alerta'] = 'success';
    $_SESSION['title'] = '¡Aviso Enviado!';
    
    // Redireccionamos al panel de pedidos de administrador (ajusta la URL si es diferente)
    header('Location: ./enviar_pedidoprosap_clien.php'); 
    exit; 

} catch (Exception $e) {
    // Lógica de error
    $_SESSION["alerta_activa"] = true;
    $_SESSION['mensaje_alerta'] = "Error al enviar el aviso interno. El correo de confirmación no se pudo enviar. {$mail->ErrorInfo}";
    $_SESSION['icono_alerta'] = 'error';
    $_SESSION['title'] = 'Error de Envío';

   // header('Location: ./../../index.php?pag=pedido_admin');
    exit; 
}
}


?>