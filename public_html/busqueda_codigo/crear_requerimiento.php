<?PHP

include("./../../conexion.php");

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
//$data = array($codigo_fabricante, $fabricante, $marca, $modelo, $ano, $motor, $tlf, $email, $comentario,);



 $argumentos = [$codigo_fabricante, $fabricante, $marca, $modelo, $ano, $motor, $tlf, $email, $comentario, $fecha];
 $seleccionado = $base_de_datos->prepare("INSERT INTO requerimiento_filtro (codigo_fabricante, fabricante, marca, modelo, ano, motor, tlf, email, comentario, fecha) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
 $seleccionado->execute($argumentos);

$seleccionado->send();

echo json_encode($seleccionado);

//echo json_encode('Exito');
} catch (Exception $e) {
    echo json_encode("Error: {$seleccionado->ErrorInfo}");
}
}



?>