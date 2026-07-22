<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/aplicacion_agricola.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Configuración de caché (6 horas = 21600 segundos)
$cacheFile = 'sync/cache_aplicacion_agricola.json';
$cacheTime = 21600;

$_respuestas = new respuestas;
$_aplicacion_agricola = new aplicacion_agricola;

// Obtener token flexible (token o Token)
$token_recibido = $_GET['token'] ?? $_GET['Token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // 1. Validar token
    if ($token_recibido !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o no proporcionado']);
        exit;
    }

    // 2. Verificar caché (Solo para la lista completa)
    $esPeticionGeneral = (count($_GET) <= 2 && (isset($_GET['token']) || isset($_GET['Token'])));

    if ($esPeticionGeneral) {
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
            echo file_get_contents($cacheFile);
            exit;
        }
    }

    // 3. Ejecutar lógica y guardar en caché
    if ($esPeticionGeneral) {
        $lista = $_aplicacion_agricola->listaraplicacion_agricola();
        file_put_contents($cacheFile, json_encode($lista, JSON_UNESCAPED_UNICODE));
        echo json_encode($lista, JSON_UNESCAPED_UNICODE);
        http_response_code(200);
    } else {
        // En caso de que se añadan otros métodos en el futuro para este archivo
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }

} else {
    // Manejo de métodos no GET
    header('Content-Type: application/json');
    echo json_encode($_respuestas->error_405(), JSON_UNESCAPED_UNICODE);
}
?>