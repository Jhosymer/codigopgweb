<?php
require_once __DIR__ . '/clases/conexion/conexion.php';
require_once __DIR__ . '/clases/pedidos.clases.php';

header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$lockFile = $_SERVER['DOCUMENT_ROOT'] . '/ftp/pedidos.lock';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // --- BLOQUEO DE SEGURIDAD ---
    if (file_exists($lockFile)) {
        die(json_encode(["error" => "Proceso de generación en curso, intente más tarde."]));
    }
    file_put_contents($lockFile, getmypid());

    $_pedidos = new pedidos;
    $listaDepedidos = $_pedidos->listarPedidosConDetalles();
    $json_data = json_encode($listaDepedidos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/ftp/pedidos.json';

    if (file_put_contents($rutaArchivo, $json_data, LOCK_EX) !== false) {
        unlink($lockFile); // Liberamos el candado
        header('Content-Type: application/json');
        echo json_encode([
            "mensaje" => "Archivo JSON creado",
            "url" => "https://" . $_SERVER['HTTP_HOST'] . "/ftp/pedidos.json"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        unlink($lockFile);
        http_response_code(500);
        echo json_encode(["error" => "No se pudo escribir el archivo"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no soportado"]);
}
?>