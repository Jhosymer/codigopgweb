<?php
// Solo aceptar solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido. Solo se acepta POST."]);
    exit;
}

// Capturar y decodificar el JSON recibido
$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

// Validar estructura mínima
if (!isset($data['fecha']) || !isset($data['pedidosProcesados']) || !is_array($data['pedidosProcesados'])) {
    http_response_code(400);
    echo json_encode(["error" => "Estructura inválida. Se requiere 'fecha' y 'pedidosProcesados' como array."]);
    exit;
}

// Ruta al archivo donde se guardarán los registros
$archivoLog = __DIR__ . '/pedidos_registrados.json';

// Cargar registros existentes si el archivo ya existe
$historico = [];
if (file_exists($archivoLog)) {
    $historico = json_decode(file_get_contents($archivoLog), true) ?? [];
}

// Agregar nuevo bloque de pedidos al histórico
$historico[] = [
    "fecha" => $data['fecha'],
    "pedidos" => $data['pedidosProcesados']
];

// Guardar de nuevo el archivo
file_put_contents($archivoLog, json_encode($historico, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Confirmar al cliente
http_response_code(200);
echo json_encode([
    "estado" => "ok",
    "mensaje" => "Pedidos recibidos correctamente.",
    "total_recibidos" => count($data['pedidosProcesados'])
]);
?>