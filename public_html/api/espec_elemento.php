<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/espec_elemento.class.php';
header('Access-Control-Allow-Origin: *');

// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

$_respuestas = new respuestas;
$_espec_elemento = new espec_elemento;

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // Validar token
  /*  if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
        header('Content-Type: application/json');
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Token inválido o no proporcionado']);
        exit;
    }*/

    if ((count($_GET) == 1 && isset($_GET['token'])) || empty($_GET)) {
        $listaDeespec_elemento = $_espec_elemento->listarespec_elemento();
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_elemento);
        http_response_code(200);
    }
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $listaDeespec_elemento = $_espec_elemento->listarespec_elementoId($id);
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_elemento);
        http_response_code(200);
    }
    if (isset($_GET['codigo'])) {
        $codigo = $_GET['codigo'];
        $listaDeespec_elemento = $_espec_elemento->listarespec_elementoCodigo($codigo);
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_elemento);
        http_response_code(200);
    }
    if (isset($_GET['total'])) {
        $listaDeespec_elemento = $_espec_elemento->listarespec_elementoTotal();
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_elemento);
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
