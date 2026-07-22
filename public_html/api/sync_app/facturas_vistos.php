<?php
// Incluimos tu clase de conexión
// Ajustamos la ruta según tu estructura de directorios
require_once('./../clases/conexion/conexion.php');

$conexion = new conexion();

header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['facturas_vistas'])) {
    die(json_encode(['status' => 'error', 'message' => 'No se recibieron datos']));
}

$cliente_id = $data['cliente_id'];
$ids = $data['facturas_vistas'];
$resultados = [];

foreach ($ids as $id) {
    // Usamos tu método nonQuery de la clase conexión
    // Tabla: 'factura', Columna: 'visto'
    $sql = "UPDATE factura SET visto = 'Y' WHERE id = '$id' AND id_users = '$cliente_id'";
    
    $afectados = $conexion->nonQuery($sql);
    
    if ($afectados > 0) {
        $resultados[] = "ID $id actualizado";
    } else {
        $resultados[] = "ID $id sin cambios";
    }
}

echo json_encode(['status' => 'success', 'detalles' => $resultados]);
?>