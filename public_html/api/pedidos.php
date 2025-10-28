<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/pedidos.clases.php';

header('Access-Control-Allow-Origin: *');
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$_respuestas = new respuestas;
$_pedidos = new pedidos;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (empty($_GET)) {
        $listaDepedidos = $_pedidos->listarPedidosConDetalles();
        
        $json_data = json_encode($listaDepedidos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        // --- Nuevo código para forzar la descarga del archivo JSON ---
        header('Content-disposition: attachment; filename=pedidos.json');
        header('Content-type: application/json');
        // -----------------------------------------------------------

        echo $json_data;
        http_response_code(200);

    } else {
        header('Content-Type: application/json');
        echo json_encode(["error" => "Parámetros GET no soportados"], JSON_UNESCAPED_UNICODE);
        http_response_code(400);
    }
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
?>