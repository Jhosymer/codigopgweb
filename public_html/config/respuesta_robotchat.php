<?php
include 'conexion_robotchat.php'; // Asegúrate de incluir tu archivo de conexión

$data = json_decode(file_get_contents('php://input'), true);
$palabraBase = $data['palabraBase'];

$respuesta = obtenerRespuesta($palabraBase);
echo json_encode($respuesta);
?>