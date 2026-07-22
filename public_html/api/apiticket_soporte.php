<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'clases/respuestas.class.php';
require_once 'clases/apiticket_soporte.class.php'; // Asegúrate de que la ruta sea correcta

$_respuestas = new respuestas;
$_tickets = new ticket_soporte;

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
    
    // Configuración de Caché
    $cacheDir = 'cache';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0755, true);
    }
    
    $cacheFile = $cacheDir . '/tickets_user_' . $id_user . '.json';

   /* // Lógica de caché: 5 minutos
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 300)) {
        echo file_get_contents($cacheFile);
        exit;
    }*/

    $datos = $_tickets->listarTicketsPorUsuario($id_user);
    $jsonResponse = json_encode($datos, JSON_UNESCAPED_UNICODE);

    file_put_contents($cacheFile, $jsonResponse);
    
    http_response_code(200);
    echo $jsonResponse;

} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>