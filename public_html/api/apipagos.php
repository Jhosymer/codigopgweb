<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'clases/respuestas.class.php';
require_once 'clases/apipagos.class.php';

$_respuestas = new respuestas;
$_pagos = new pagos;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode($_respuestas->error_401("Token inválido"));
        exit;
    }

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID de usuario es requerido"]);
        exit;
    }

    $id_user = $_GET['id'];
    
    // Configuración de Caché
    $cacheDir = 'cache';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    // Archivo de caché específico para los pagos de este usuario
    $cacheFile = $cacheDir . '/pagos_user_' . $id_user . '.json';

    // LÓGICA DE TIEMPO REAL: 
    // Si la caché tiene menos de 5 minutos (300s), devolvemos el archivo ahorrando CPU
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 300)) {
        echo file_get_contents($cacheFile);
        exit;
    }

    // Si no hay caché, ejecutamos la consulta y guardamos el resultado
    $datos = $_pagos->listarPagosPorUsuario($id_user);
    $jsonResponse = json_encode($datos, JSON_UNESCAPED_UNICODE);

    file_put_contents($cacheFile, $jsonResponse);
    
    http_response_code(200);
    echo $jsonResponse;

} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>