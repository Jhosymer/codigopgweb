<?php 
    date_default_timezone_set('America/Caracas');
    include_once('../../../config/conexion.php');
    session_start();
    
    //Si recibe el parametro marca_nueva se creará
    if( isset($_POST['marca_nueva_editar']) ){
        //Se reciben los parametros del formulario
        $marca = $_POST['marca'];
        $id = $_POST['id'];
        $sincronizado = date("Ymd");

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
            $insertar_marca = $base_de_datos->prepare("INSERT INTO aplicacion_marca (marca, sincronizado) VALUES (:marca, :sincronizado)");
            $insertar_marca->bindParam(":marca", $marca, PDO::PARAM_STR);
            $insertar_marca->bindParam(":sincronizado", $sincronizado, PDO::PARAM_INT);
            $insertar_marca->execute();
            $_SESSION['nuevo'] = true;
            header("location: ./editar.php?id=$id");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: ./editar.php?id=$id "); 
        }
    }
    else {
        $_SESSION['error'] = true;
        header("location: ./editar.php?id=$id");
    }