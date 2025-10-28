<?php
// Incluir el archivo de configuración
require '../../../config/conexion.php';

// Consulta a la base de datos
$sql = "SELECT * FROM aplicacion_marca WHERE deleted_at IS NULL ORDER BY marca ASC";
$stmt = $base_de_datos->prepare($sql);
$stmt->execute();

// Obtener los datos y devolverlos como JSON
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
