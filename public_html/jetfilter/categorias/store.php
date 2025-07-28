<?php 
    date_default_timezone_set('America/Caracas');
    try {
        session_start();
        include_once('./../conexion/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: nuevo.php");
    }

    if( isset( $_POST['categoria_enviar'] ) ){
        $fecha = date("d-m-y"); 

        $categoria = $_POST['categoria'];
        $producto = $_POST['producto'];
        $clase = $_POST['clase'];

        $asegurar_no_existencia = $base_de_datos->prepare("SELECT COUNT(*) as total FROM categorias 
                                                WHERE ( categoria = :categoria ) and ( producto_id = :producto_id )  and ( deleted_at is null )");
        $asegurar_no_existencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $asegurar_no_existencia->bindParam(':producto_id', $producto, PDO::PARAM_INT);
        $asegurar_no_existencia->execute();
        $numero_total = $asegurar_no_existencia->fetch(PDO::FETCH_ASSOC);

        if( $numero_total['total'] > 0 ){
            $_SESSION['existencia'] = true;
            header("location: nuevo.php");
        }
        else {
            $sql = "INSERT INTO categorias (categoria, producto_id, clase) VALUES (:categoria, :producto_id, :clase)";
            $insertar_categoria = $base_de_datos->prepare($sql);
            $insertar_categoria->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $insertar_categoria->bindParam(':producto_id', $producto, PDO::PARAM_INT);
            $insertar_categoria->bindParam(':clase', $clase, PDO::PARAM_STR);
            $insertar_categoria->execute();

            $_SESSION['nuevo'] = true;
            header("location: categorias.php");
        }
    }