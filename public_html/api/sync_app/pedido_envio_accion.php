<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Mantén tus rutas de inclusión según tu estructura de carpetas
require_once __DIR__ . '/../../librerias/PHPMailer/Exception.php';
require_once __DIR__ . '/../../librerias/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../../librerias/PHPMailer/SMTP.php';

function enviarCorreoPedido($id_pedido, $conexion_obj) {
    // 1. Obtener usuario
    $datos_user = $conexion_obj->obtenerDatos("SELECT u.email, u.name FROM users u INNER JOIN pedidos p ON u.id = p.id_users WHERE p.id = '$id_pedido' LIMIT 1");
    if (empty($datos_user)) return "Usuario no encontrado";
    $user = $datos_user[0];

    // 2. Obtener detalles del pedido
    $items = $conexion_obj->obtenerDatos("SELECT lp.precio_u as precio, lp.total as total, lp.cantidad as cantida, fc.codigo as codpro, fc.descripcion as nombre, p.fecha as fecha_pedido FROM lista_pedidos lp INNER JOIN pedidos p ON lp.id_pedido = p.id INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id WHERE lp.id_pedido = '$id_pedido'");
    
    $detalle_html = '';
    $total_pedido = 0;
    $fecha_pedido = !empty($items) ? date('Y-m-d', strtotime($items[0]['fecha_pedido'])) : date('Y-m-d');

    foreach ($items as $item) {
        $total_pedido += (float)$item['total'];
        $detalle_html .= "
            <tr style='background-color: #ffffff;'>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['codpro']) . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6;'>" . htmlspecialchars($item['nombre']) . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>" . number_format($item['cantida'], 0, '', '.') . "</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>" . number_format($item['precio'], 2, ',', '.') . "$</td>
                <td style='padding: 8px; border-top: 1px solid #dee2e6; text-align: right;'>" . number_format($item['total'], 2, ',', '.') . "$</td>
            </tr>";
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'mail.webfiltros.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'pedidosweb@webfiltros.com';
        $mail->Password = '1234facil';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('pedidosweb@webfiltros.com', 'WebFiltros Pedidos');
        $mail->addAddress($user['email'], $user['name']);

        // Imágenes embebidas (usando la misma ruta base del sistema)
        $path_base = $_SERVER['DOCUMENT_ROOT'] . '/jetfilter/';
        $mail->addEmbeddedImage($path_base . 'img/logoweb.png', 'logo_web', 'logoweb.png');
        $mail->addEmbeddedImage($path_base . 'img/logoj.png', 'logo_jetfilter', 'logoj.png');

        $mail->isHTML(true);
        $mail->Subject = 'CONFIRMACIÓN ' . $id_pedido;
        
        // Cuerpo del mensaje unificado
        $mail->Body = '
            <div style="font-family: Roboto, Tahoma, Verdana, sans-serif; font-size: 14px; color: #333;">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr><td style="padding: 10px; text-align: left;"><img src="cid:logo_web" alt="WebFiltros" style="width: 150px; height: auto;"></td></tr>
                </table>
                <p>Estimado/a <strong>' . htmlspecialchars($user['name']) . '</strong>,</p>
                <p>Hemos recibido correctamente su pedido para ser procesado por nuestro equipo.</p>
                <h3 style="color: #E2001A;">Detalle Pedido</h3> 
                <p style="font-size: 12px; margin-bottom: 15px; background-color: #f0f0f0; padding: 5px; display: inline-block;">Fecha Pedido: ' . htmlspecialchars($fecha_pedido) . '</p>
                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; border: 1px solid #dee2e6;">
                    <thead><tr style="background-color: #007bff; color: #ffffff;">
                        <th style="padding: 10px; text-align: left;">Código Prod.</th><th style="padding: 10px; text-align: left;">Nombre Producto</th>
                        <th style="padding: 10px; text-align: right;">Cantidad</th><th style="padding: 10px; text-align: right;">Precio Unid</th><th style="padding: 10px; text-align: right;">Total</th>
                    </tr></thead>
                    <tbody>' . $detalle_html . '</tbody>
                </table>
                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
                    <tr>
                        <td width="70%"></td>
                        <td width="30%" style="background-color: #D3D3D3; padding: 10px; text-align: right; font-size: 16px; font-weight: bold;">Total: ' . number_format($total_pedido, 2, ',', '.') . '$</td>
                    </tr>
                </table>
                <p style="font-weight: bold; color: #E2001a; font-size: 80%; float: right; text-align: right;">⚠️ Nota: El total mostrado NO incluye descuentos ni impuestos.</p>
                <div style="border-left: 5px solid #007bff; padding: 10px; background-color: #e9f5ff; margin-top: 30px; font-size: 13px;">
                    <p style="margin: 0;">Se le estará notificando la confirmación del registro de su pedido en nuestro sistema.</p>
                    <p style="margin: 5px 0 0 0;">Gracias por su confianza.</p>
                </div>
                <div style="border-left: 5px solid #ffc107; padding: 10px; background-color: #fff3cd; margin-top: 30px; font-size: 13px;">
                    <p style="margin: 0; color: #856404;"><span style="font-weight: bold; color: #856404;">⚠️ ATENCIÓN: </span> Este es un correo electrónico de notificación automática. <span style="font-weight: bold; color: #856404;">Por favor, no lo responda. </span></p>
                    <p style="margin: 5px 0 0 0; color: #856404;">Este buzón no es monitoreado y su mensaje no será leído.</p>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <div style="text-align: right; font-size: 10px; color: #666;">
                    <p style="margin-bottom: 5px;"><img src="cid:logo_jetfilter" alt="JetFilter" style="width: 150px; height: auto;"></p>
                    <p>Jetfilter, C.A, Tinaquillo EDO. Cojedes,</p>
                    <p>www.webfiltros.com, RIF J- 00059322-1</p>
                </div>
            </div>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}
?>