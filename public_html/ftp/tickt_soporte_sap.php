<?php
require_once __DIR__ . '/clases/conexion/conexion.php';
require_once __DIR__ . '/clases/tickt_soporte_sap.clases.php';

header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$lockFile = $_SERVER['DOCUMENT_ROOT'] . '/ftp/tickets.lock';

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // --- BLOQUEO DE SEGURIDAD ---
    if (file_exists($lockFile)) {
        http_response_code(423);
        die(json_encode(["error" => "Generación de tickets en curso."]));
    }
    file_put_contents($lockFile, getmypid());

    $_soporte = new soporte;
    $listaTickets = $_soporte->listarTickets();
    
    $json_data = json_encode($listaTickets, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/ftp/tickets_soporte.json';

    // Guardamos con LOCK_EX para asegurar la atomicidad de la escritura
    if (file_put_contents($rutaArchivo, $json_data, LOCK_EX) !== false) {
        unlink($lockFile);
        header('Content-Type: application/json');
        echo json_encode([
            "mensaje" => "JSON de tickets creado",
            "url" => "https://" . $_SERVER['HTTP_HOST'] . "/ftp/tickets_soporte.json"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        unlink($lockFile);
        http_response_code(500);
        echo json_encode(["error" => "No se pudo crear el archivo JSON"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no soportado"]);
}
?>