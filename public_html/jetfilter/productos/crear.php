<?php 
    date_default_timezone_set('America/Caracas');
    try {
        session_start();
        include_once('./../conexion/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: nuevo.php?errorBase=true");
    }

        if( isset( $_POST['producto'] ) ){
            $fecha = date("d-m-y"); 

            $producto = $_POST['producto'];

            try {
                $base_de_datos->beginTransaction();

                $sql = "INSERT INTO productos (nombre, created_at) VALUES (:producto, :created_at);";
                $seleccionado = $base_de_datos->prepare($sql);
                $seleccionado->bindParam(':producto', $producto, PDO::PARAM_STR);
                $seleccionado->bindParam(':created_at', $fecha, PDO::PARAM_STR);
                $seleccionado->execute();

                $base_de_datos->commit();

                $_SESSION['nuevo'] = true;
                header("location: ./productos.php") or die('Error');
            }
            catch(PDOException $exception){
                $base_de_datos->rollback();
                $_SESSION['error'] = true;
                header("location: ./nuevo.php");
            }
        }
        else {
            header("location: ./nuevo.php");
        }