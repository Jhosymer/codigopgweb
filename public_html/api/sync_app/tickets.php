<?php
require_once('./../clases/conexion/conexion.php');
$conexion = new conexion();
header('Content-Type: application/json');

// --- DEPURACIÓN ---
$debug_data = ['POST' => $_POST, 'FILES' => $_FILES];
file_put_contents('debug_post.txt', print_r($debug_data, true));

$accion = $_POST['accion'] ?? '';

if ($accion == 'crear') {
    $ticket = $_POST;
    $nombreFinal = ''; // Por defecto vacío
    $huboArchivo = false;

    // 1. Subida de archivo (solo si existe y no está vacío)
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        $extension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
        $nombreTemporal = $ticket['anexo'] . '_' . $ticket['id_user'] . '.' . $extension;
        $destino = './../../jetfilter/img/soporte/' . $nombreTemporal;

        if (move_uploaded_file($_FILES['archivo']['tmp_name'], $destino)) {
            $nombreFinal = $nombreTemporal;
            $huboArchivo = true;
        }
    }

    // 2. INSERT
    $sql = "INSERT INTO ticket_soporte (id_user, id_tp_soporte, id_producto, asunto, detalle, stado, anexo, anexo_r, fecha_creado, visto_admin, visto_cliente) 
            VALUES ('{$ticket['id_user']}', '{$ticket['id_tp_soporte']}', '{$ticket['id_producto']}', '{$ticket['asunto']}', '{$ticket['detalle']}', '{$ticket['stado']}', '$nombreFinal', '{$ticket['anexo_r']}', '{$ticket['fecha_creado']}', '{$ticket['visto_admin']}', '{$ticket['visto_cliente']}')";
    
    $new_id = $conexion->nonQueryId($sql);

    // 3. RENOMBRADO FINAL (Solo si hubo archivo)
   if ($huboArchivo) {
        $nombreReal = $new_id . '_' . $ticket['id_user'] . '.' . $extension;
        rename('./../../jetfilter/img/soporte/' . $nombreFinal, './../../jetfilter/img/soporte/' . $nombreReal);
        $conexion->nonQuery("UPDATE ticket_soporte SET anexo = '$nombreReal' WHERE id = '$new_id'");
        $ticket['anexo'] = $nombreReal;
    } else {
        $ticket['anexo'] = '';
    }

    // 4. LIMPIEZA DE DATOS PARA LA APP
    // Aquí eliminamos la ruta temporal antes de enviar el JSON de vuelta
    $ticket['ruta_completa_anexo'] = ''; 
    $ticket['id'] = (string)$new_id;
    $ticket['edit'] = 'original';
    
    echo json_encode(['status' => 'success', 'ticket_actualizado' => $ticket]);
}
elseif ($accion == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $visto = $_POST['visto_cliente'] ?? '';
    $conexion->nonQuery("UPDATE ticket_soporte SET visto_cliente = '$visto' WHERE id = '$id'");
    echo json_encode(['status' => 'success']);
} 
else {
    echo json_encode(['status' => 'error', 'message' => 'Accion no reconocida', 'recibido' => $accion]);
}
?>