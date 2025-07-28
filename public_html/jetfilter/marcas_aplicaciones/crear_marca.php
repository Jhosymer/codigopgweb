<?php 
    date_default_timezone_set('America/Caracas');
    $marca = $_POST['marca'];$marca = $_POST['marca'];    include_once('./../conexion/conexion.php');
    session_start();
    
    if(isset($_POST['marcas'])){
        $sincronizado = date("Ymd");
        $marca = $_POST['marca'];

        //Se comprueba si la marca ya existe
        $sql = "SELECT COUNT(*) as total FROM aplicacion_marca WHERE ( marca = :marca ) and ( deleted_at is null )";
        $numMarcas = $base_de_datos->prepare($sql);
        $numMarcas->bindParam(':marca', $marca, PDO::PARAM_STR);
        $numMarcas->setFetchMode(PDO::FETCH_ASSOC); 
        $numMarcas->execute();
        $numMarcas = $numMarcas->fetch();
        $numMarcas = $numMarcas['total'];

        //Si no hay coincidencia, se agrega el registro
        if( $numMarcas == 0 ){
            //Se crea la nueva marca
            $argumentos = [$marca, $sincronizado];

            $seleccionado = $base_de_datos->prepare("INSERT INTO aplicacion_marca (marca, sincronizado) VALUES (?, ?)");
            $seleccionado->execute($argumentos);

            $_SESSION['nuevo'] = true;
            header("location: marcas_aplicaciones.php");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: ./nuevo.php"); 
        }
    }
    else {
        header("location: marcas_aplicaciones.php");
    }