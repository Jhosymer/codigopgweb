<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/user.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

// Cabeceras estrictas para evitar cualquier tipo de caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$_respuestas = new respuestas;
$_users = new users;

// Obtener token flexible
$token_recibido = $_GET['token'] ?? $_GET['Token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    // 1. Validar token
    if ($token_recibido !== 'Hlcbdep22') {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido o no proporcionado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 2. Consulta directa a la base de datos (Sin archivo intermedio en sync/)
    $lista = $_users->listarusers();
    
    echo json_encode($lista, JSON_UNESCAPED_UNICODE);
    http_response_code(200);

} else {
    // Manejo de métodos no permitidos
    echo json_encode($_respuestas->error_405(), JSON_UNESCAPED_UNICODE);
}
?>