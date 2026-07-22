<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/apipedidos.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$_respuestas = new respuestas;
$_pedidos = new pedidos;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode($_respuestas->error_401("Token inválido"));
        exit;
    }

    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "ID de usuario es requerido"]);
        exit;
    }

    $id_user = $_GET['id'];
    
    // Carpeta de caché (asegúrate de que exista y tenga permisos)
    $cacheDir = 'cache'; 
    if (!file_exists($cacheDir)) mkdir($cacheDir, 0755, true);
    $cacheFile = $cacheDir . '/pedidos_user_' . $id_user . '.json';

    // LÓGICA DE TIEMPO REAL:
    // Si el archivo existe y es reciente (menos de 5 min), servimos el JSON estático.
    // Esto reduce el consumo de CPU al mínimo posible (casi cero).
  /*  if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 100)) {
        echo file_get_contents($cacheFile);
        exit;
    }*/

    // Si no hay caché, ejecutamos la consulta y creamos el nuevo archivo
    $datos = $_pedidos->listarPedidosPorUsuario($id_user);
    $jsonResponse = json_encode($datos);

    file_put_contents($cacheFile, $jsonResponse);
    
    echo $jsonResponse;

} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>