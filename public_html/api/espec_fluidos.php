<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/espec_fluidos.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Configuración de caché
$cacheFile = 'sync/cache_espec_fluidos.json';
$cacheTime = 21600; // 6 horas en segundos

$_respuestas = new respuestas;
$_espec_fluidos = new espec_fluidos;

// 1. Obtener token sin importar si viene como 'token' o 'Token'
$token_recibido = $_GET['token'] ?? $_GET['Token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // 2. Validar token
    if ($token_recibido !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o no proporcionado']);
        exit;
    }

    // 3. Verificar caché (Solo para la lista completa, sin filtros de ID, código o total)
    $esPeticionGeneral = (count($_GET) <= 2 && !isset($_GET['id']) && !isset($_GET['codigo']) && !isset($_GET['total']));
    
    if ($esPeticionGeneral) {
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
            echo file_get_contents($cacheFile);
            exit;
        }
    }

    // 4. Ejecutar lógica y guardar caché si es petición general
    if ($esPeticionGeneral) {
        $lista = $_espec_fluidos->listarespec_fluidos();
        file_put_contents($cacheFile, json_encode($lista, JSON_UNESCAPED_UNICODE));
        echo json_encode($lista, JSON_UNESCAPED_UNICODE);
        http_response_code(200);
    } 
    elseif (isset($_GET['id'])) {
        echo json_encode($_espec_fluidos->listarespec_fluidosId($_GET['id']), JSON_UNESCAPED_UNICODE);
    } 
    elseif (isset($_GET['codigo'])) {
        echo json_encode($_espec_fluidos->listarespec_fluidosCodigo($_GET['codigo']), JSON_UNESCAPED_UNICODE);
    } 
    elseif (isset($_GET['total'])) {
        echo json_encode($_espec_fluidos->listarespec_fluidosTotal(), JSON_UNESCAPED_UNICODE);
    }

} else {
    // Manejo de otros métodos
    header('Content-Type: application/json');
    echo json_encode($_respuestas->error_405(), JSON_UNESCAPED_UNICODE);
}
?>