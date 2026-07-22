<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/apifacturas.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$_respuestas = new respuestas;
$_facturas = new facturas;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode($_respuestas->error_401("Token inválido"));
        exit;
    }

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID de usuario es obligatorio"]);
        exit;
    }

    $id_user = $_GET['id'];
    $cacheDir = 'cache';
    if (!file_exists($cacheDir)) mkdir($cacheDir, 0755, true);
    
    // Archivo de caché específico para facturas
    $cacheFile = $cacheDir . '/facturas_user_' . $id_user . '.json';

    // Servir caché si es reciente (5 minutos)
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 300)) {
        echo file_get_contents($cacheFile);
        exit;
    }

    // Consultar y guardar
    $datos = $_facturas->listarFacturasPorUsuario($id_user);
    $jsonResponse = json_encode($datos);
    file_put_contents($cacheFile, $jsonResponse);
    
    echo $jsonResponse;

} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>