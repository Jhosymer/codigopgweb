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

        if( isset( $_POST['tipo'] ) ){
            $fecha = date("d-m-y"); 

            $tipo = $_POST['tipo'];
            $clase = $_POST['clase'];

            try {
                $base_de_datos->beginTransaction();

                $sql = "INSERT INTO tipo (tipo, clase, created_at) VALUES (:tipo, :clase, :created_at);";
                $seleccionado = $base_de_datos->prepare($sql);
                $seleccionado->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $seleccionado->bindParam(':clase', $clase, PDO::PARAM_STR);
                $seleccionado->bindParam(':created_at', $created_at, PDO::PARAM_STR);
                $seleccionado->execute();

                $base_de_datos->commit();

                $_SESSION['nuevo'] = true;
                header("location: ./tipo.php") or die('Error');
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