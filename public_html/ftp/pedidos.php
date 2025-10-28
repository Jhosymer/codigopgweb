<?php


require_once __DIR__ . '/clases/conexion/conexion.php';
require_once __DIR__ . '/clases/pedidos.clases.php';

header('Access-Control-Allow-Origin: *');
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$_respuestas = new respuestas;
$_pedidos = new pedidos;

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (empty($_GET)) {
        // Obtener los datos
        $listaDepedidos = $_pedidos->listarPedidosConDetalles();
        $json_data = json_encode($listaDepedidos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        // Ruta donde se guardará el archivo JSON
        $nombreArchivo = 'pedidos.json'; // Puedes agregar timestamp si lo deseas
        $rutaArchivo = $_SERVER['DOCUMENT_ROOT'] . '/ftp/' . $nombreArchivo;

        // Guardar el archivo en la carpeta FTP
        $resultado = file_put_contents($rutaArchivo, $json_data);

        // Verificar si se guardó correctamente
        if ($resultado !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                "mensaje" => "Archivo JSON creado exitosamente",
                "ruta" => "/ftp/" . $nombreArchivo,
                "url" => "https://" . $_SERVER['HTTP_HOST'] . "/ftp/" . $nombreArchivo
            ], JSON_UNESCAPED_UNICODE);
            http_response_code(200);
        } else {
            header('Content-Type: application/json');
            echo json_encode(["error" => "No se pudo crear el archivo JSON"], JSON_UNESCAPED_UNICODE);
            http_response_code(500);
        }

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