<?php
require_once('../clases/conexion/conexion.php');
$conexion = new conexion();
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['pagos_vistos'])) {
    die(json_encode(['status' => 'error', 'message' => 'No se recibieron datos']));
}

$cliente_id = $data['cliente_id'];
$ids = $data['pagos_vistos'];

foreach ($ids as $id) {
    // Actualiza la tabla 'pagos' usando la columna 'num_pago_sap'
    $sql = "UPDATE pagos SET visto = 'Y' WHERE num_pago_sap = '$id' AND id_users = '$cliente_id'";
    $conexion->nonQuery($sql);
}

echo json_encode(['status' => 'success']);
?>