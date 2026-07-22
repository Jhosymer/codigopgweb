<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/estado_cuenta.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$_respuestas = new respuestas;
$_estado = new estado_cuenta;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode($_respuestas->error_401("Token de seguridad no válido"));
        exit;
    }

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "El ID del usuario es obligatorio"]);
        exit;
    }

    $id_user = $_GET['id'];
    $cacheDir = 'cache';
    if (!file_exists($cacheDir)) mkdir($cacheDir, 0755, true);
    
    // Archivo de caché único para el estado de cuenta de este usuario
    $cacheFile = $cacheDir . '/estado_cuenta_user_' . $id_user . '.json';

    // Si existe caché reciente (5 min), devolvemos el archivo directamente
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 300)) {
        echo file_get_contents($cacheFile);
        exit;
    }

    $listaEstado = $_estado->obtenerEstadoCuenta($id_user);
    $jsonResponse = json_encode($listaEstado);

    // Guardar para futuras peticiones
    file_put_contents($cacheFile, $jsonResponse);
    
    http_response_code(200);
    echo $jsonResponse;

} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>