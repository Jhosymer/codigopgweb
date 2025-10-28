<?php 
    date_default_timezone_set('America/Caracas');
    include_once('../../../config/conexion.php');
    session_start();

    if(isset($_POST['marca_nueva'])){
        $marca = $_POST['marca'];
        $fecha = (int)date("Ymd"); 

        //Se comprueba si la marca ya existe
        $sql = "SELECT COUNT(*) as total FROM equivalencia_marca WHERE ( marca = :marca ) and ( deleted_at is null )";
        $numMarcas = $base_de_datos->prepare($sql);
        $numMarcas->bindParam(':marca', $marca, PDO::PARAM_STR);
        $numMarcas->setFetchMode(PDO::FETCH_ASSOC); 
        $numMarcas->execute();
        $numMarcas = $numMarcas->fetch();
        $numMarcas = $numMarcas['total'];

        //Si no hay coincidencia, se agrega el registro
        if( $numMarcas == 0 ){
            //Se crea la nueva marca
            $sql = "INSERT INTO equivalencia_marca (marca, sincronizado) Values (:marca, :fecha)";
            $ingresarEquivalencia = $base_de_datos->prepare($sql);
            $ingresarEquivalencia->bindParam(':marca', $marca, PDO::PARAM_STR);
            $ingresarEquivalencia->bindParam(':fecha', $fecha, PDO:: PARAM_INT);
            $ingresarEquivalencia->execute();

            $_SESSION['nuevo'] = true;
            header('location: ./equivalencias.php');
        }
        else {
            $_SESSION['existencia'] = true;
            header("location: ./editar.php?id=$id");
        }
    }
    else {
        header("location: ./equivalencias.php");
    }