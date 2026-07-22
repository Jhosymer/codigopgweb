<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/Filtros_codificacion.class.php';

header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$_respuestas = new respuestas;
$_filtro_codificacion = new filtro_codificacion;

// Función para enviar errores en JSON y código HTTP adecuado
function responderError($codigoHttp, $mensaje) {
    http_response_code($codigoHttp);
    header('Content-Type: application/json');
    echo json_encode(['error' => $mensaje]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == "GET") {

    try {
        // Si usas token, valida aquí (descomenta si quieres)
        
        if (!isset($_GET['token']) || $_GET['token'] !== 'Hlcbdep22') {
            responderError(401, 'Token inválido o no proporcionado');
        }
        

        // Caso sin parámetros o solo token
        if ((count($_GET) == 1 && isset($_GET['token'])) || empty($_GET)) {
            $datos = $_filtro_codificacion->listarfiltro_codificacion();
            if (isset($datos['error'])) {
                responderError(500, $datos['error']);
            }
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($datos);
            exit;
        }

        // Buscar por id
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if (!is_numeric($id)) {
                responderError(400, 'El parámetro id debe ser numérico');
            }
            $datos = $_filtro_codificacion->listarfiltro_codificacionId($id);
            if (isset($datos['error'])) {
                responderError(500, $datos['error']);
            }
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($datos);
            exit;
        }

        // Buscar por nombre
        if (isset($_GET['nombre'])) {
            $nombre = $_GET['nombre'];
            $datos = $_filtro_codificacion->listarfiltro_codificacionNombre($nombre);
            if (isset($datos['error'])) {
                responderError(500, $datos['error']);
            }
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($datos);
            exit;
        }

        // Obtener total
        if (isset($_GET['total'])) {
            $datos = $_filtro_codificacion->listarfiltro_codificacionTotal();
            if (isset($datos['error'])) {
                responderError(500, $datos['error']);
            }
            http_response_code(200);
            header("Content-Type: application/json");
            echo json_encode($datos);
            exit;
        }

        // Si no se reconoce el parámetro
        responderError(400, 'Parámetros inválidos o faltantes');

    } catch (Exception $e) {
        responderError(500, 'Error interno del servidor: ' . $e->getMessage());
    }

} else {
    responderError(405, 'Método no permitido');
}
?>
