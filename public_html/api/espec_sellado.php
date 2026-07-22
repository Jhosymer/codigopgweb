<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/espec_sellado.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Configuración de caché (6 horas = 21600 segundos)
$cacheFile = 'sync/cache_espec_sellado.json';
$cacheTime = 21600;

$_respuestas = new respuestas;
$_espec_sellado = new espec_sellado;

// Obtener token flexible
$token_recibido = $_GET['token'] ?? $_GET['Token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // 1. Validar token
    if ($token_recibido !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o no proporcionado']);
        exit;
    }

    // 2. Verificar caché (Solo para la lista completa)
    $esPeticionGeneral = (count($_GET) <= 2 && !isset($_GET['id']) && !isset($_GET['codigo']) && !isset($_GET['total']));

    if ($esPeticionGeneral) {
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
            echo file_get_contents($cacheFile);
            exit;
        }
    }

    // 3. Ejecutar lógica
    if ($esPeticionGeneral) {
        $lista = $_espec_sellado->listarespec_sellado();
        file_put_contents($cacheFile, json_encode($lista, JSON_UNESCAPED_UNICODE));
        echo json_encode($lista, JSON_UNESCAPED_UNICODE);
        http_response_code(200);
    } 
    elseif (isset($_GET['id'])) {
        echo json_encode($_espec_sellado->listarespec_selladoId($_GET['id']), JSON_UNESCAPED_UNICODE);
    } 
    elseif (isset($_GET['codigo'])) {
        echo json_encode($_espec_sellado->listarespec_selladoCodigo($_GET['codigo']), JSON_UNESCAPED_UNICODE);
    } 
    elseif (isset($_GET['total'])) {
        echo json_encode($_espec_sellado->listarespec_selladoTotal(), JSON_UNESCAPED_UNICODE);
    }

} else {
    // Manejo de métodos no GET
    header('Content-Type: application/json');
    echo json_encode($_respuestas->error_405(), JSON_UNESCAPED_UNICODE);
}
?>