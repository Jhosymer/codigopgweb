<?php
  date_default_timezone_set('America/Caracas');
  session_start();
if( isset($_POST['codigo']) ){

    $codigo = $_POST['codigo'];

    include('./../conexion/conexion.php');
    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }

    $sql = "SELECT * FROM filtro_codificacion WHERE codigo = :codigo and ( deleted_at is null )";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    
    // Evaluación de resultados
    if ($seleccionado->rowCount() > 0) {
        // La consulta generó resultados
        $filtro = $seleccionado->fetch();
        $clase = $filtro['clase'];
        $codigo = $filtro['codigo'];
         

        //echo $clase.'' .$codigo;

        $_SESSION['actualizado'] = true;
        header("location: generador_qr.php?clase=$clase&codigo=$codigo");
    } else {
        header("location: generador_qr.php");
        $_SESSION['error'] = true;
    }

    


} else {

    header("location: generador_qr.php");
    $_SESSION['error'] = true;
    
}