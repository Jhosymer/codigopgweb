<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/espec_sellado.class.php';
header('Access-Control-Allow-Origin: *');

// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$_respuestas = new respuestas;
$_espec_sellado = new espec_sellado;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // Validar token
  /*  if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        header('Content-Type: application/json');
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Token inválido o no proporcionado']);
        exit; // Detener ejecución si no hay token válido
    }*/

    // Si el token es válido, continuar con la lógica existente
    // Considerar que el token puede estar junto con otros parámetros
    if ((count($_GET) == 1 && isset($_GET['token'])) || empty($_GET)) {
        $listaDeespec_sellado = $_espec_sellado->listarespec_sellado();
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_sellado);
        http_response_code(200);
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $listaDeespec_sellado = $_espec_sellado->listarespec_selladoId($id);
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_sellado);
        http_response_code(200);
    }
    if (isset($_GET['codigo'])) {
        $codigo = $_GET['codigo'];
        $listaDeespec_sellado = $_espec_sellado->listarespec_selladoCodigo($codigo);
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_sellado);
        http_response_code(200);
    }
    if (isset($_GET['total'])) {
        $listaDeespec_sellado = $_espec_sellado->listarespec_selladoTotal();
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_sellado);
        http_response_code(200);
    }

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Aquí puedes agregar lógica para POST si la necesitas

} else if ($_SERVER['REQUEST_METHOD'] == "PUT") {

    // Aquí puedes agregar lógica para PUT si la necesitas

} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {

    // Aquí puedes agregar lógica para DELETE si la necesitas

} else {
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}
?>
