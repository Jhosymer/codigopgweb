<?PHP

include("./../../config/conexion.php");

if (isset($_POST['codigo_fabricante'])) { 

    $codigo_fabricante = $_POST['codigo_fabricante']; 
    $fabricante = $_POST['fabricante'];
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $ano = $_POST['ano'];
    $motor = $_POST['motor'];
    $tlf = $_POST['tlf'];
    $email = $_POST['email'];
    $comentario = $_POST['comentario'];
    $fecha = date("y-m-d");


    try {
        
        $argumentos = [$codigo_fabricante, $fabricante, $marca, $modelo, $ano, $motor, $tlf, $email, $comentario, $fecha];
        
        $seleccionado = $base_de_datos->prepare("INSERT INTO requerimiento_filtro (codigo_fabricante, fabricante, marca, modelo, ano, motor, tlf, email, comentario, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // La ejecución de la consulta inserta los datos
        $exito = $seleccionado->execute($argumentos);

        // ¡ESTA LÍNEA FUE ELIMINADA! ($seleccionado->send() causaba el 500)
        // $seleccionado->send(); 

        // Retorna un JSON con la respuesta (usando $exito para un mejor control)
        if ($exito) {
            echo json_encode(['status' => 'success', 'message' => 'Requerimiento creado con éxito.']);
        } else {
            // Si la ejecución falla, aunque no lance una excepción, retorna el error de la base de datos.
            echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar consulta', 'db_error' => $seleccionado->errorInfo()]);
        }
        
    } catch (Exception $e) {
        // Captura errores de conexión o de preparación de la consulta
        echo json_encode(['status' => 'exception', 'message' => 'Error de servidor: ' . $e->getMessage()]);
    }
} else {
    // Manejar el caso donde no hay datos POST
    echo json_encode(['status' => 'error', 'message' => 'Datos de entrada incompletos.']);
}

?>