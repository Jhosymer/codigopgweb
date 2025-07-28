<?php 
    date_default_timezone_set('America/Caracas');
    include_once('./../conexion/conexion.php');
    session_start();
    
    if(isset($_POST['marca_nueva'])){
        $marca = $_POST['marca'];
        $fecha = date("Ymd"); 

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
            $insertar_marca = $base_de_datos->prepare("INSERT INTO equivalencia_marca (marca, sincronizado) VALUES (:marca, :sincronizado)");
            $insertar_marca->bindParam(":marca", $marca, PDO::PARAM_STR);
            $insertar_marca->bindParam(":sincronizado", $fecha, PDO::PARAM_INT);
            $insertar_marca->execute();
            $_SESSION['nuevo'] = true;
            header("location: ./nuevo.php");
        }
        else {
            $_SESSION['existencia'] = true;
            header("location: ./nuevo.php");
        }
    }
    else {
        header("location: ./equivalencias.php");
    }