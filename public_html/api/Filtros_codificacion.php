<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/Filtros_codificacion.class.php'; // Asegúrate de que el nombre del archivo de la clase coincida

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Ruta y tiempo de caché
$cacheFile = 'sync/cache_Filtros_codificacion.json';
$cacheTime = 21600; 

// 1. Validar token
if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
    http_response_code(401);
    echo json_encode(['error' => 'Token inválido'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 2. Verificar caché (Solo si es petición general)
if (!isset($_GET['id']) && !isset($_GET['nombre']) && $_SERVER['REQUEST_METHOD'] == "GET") {
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
        echo file_get_contents($cacheFile);
        exit;
    }
}

// 3. Ejecutar consulta si no hay caché
$_filtro = new filtro_codificacion; // Asegúrate de que la clase se llame así

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['id'])) {
        $datos = $_filtro->listarfiltro_codificacionId($_GET['id']);
    } elseif (isset($_GET['nombre'])) {
        $datos = $_filtro->listarfiltro_codificacionNombre($_GET['nombre']);
    } else {
        $datos = $_filtro->listarfiltro_codificacion();
        // Guardar caché
        file_put_contents($cacheFile, json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
    
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
?>