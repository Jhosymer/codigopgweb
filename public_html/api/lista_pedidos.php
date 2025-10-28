<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/lista_pedidos.class.php'; // <-- Corregido el nombre del archivo
header('Access-Control-Allow-Origin: *');
    
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$_respuestas = new respuestas;
$_lista_pedidos = new lista_pedidos;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (empty($_GET)) {
        $listaDelista_pedidos = $_lista_pedidos->listarlista_pedidos();
        header("Content-Type: application/json");
        echo json_encode($listaDelista_pedidos);
        http_response_code(200);
    } 

    $ruta = 'E:\jhoselyn\Desktop\sap\lista_pedidos.json';

 $contenido = json_encode($listaDelista_pedidos);

 file_put_contents($ruta, $contenido);
} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
?>