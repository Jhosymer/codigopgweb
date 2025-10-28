<?php 
    date_default_timezone_set('America/Caracas');
    try {
        session_start();
        include_once('../../../config/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: nuevo.php?errorBase=true");
    }

        if( isset( $_POST['tipo_enviar'] ) ){
            $fecha = date("d-m-y H:i:s"); 

            $tipo = $_POST['tipo'];
            $categoria = $_POST['categoria'];

            $asegurar_no_existencia = $base_de_datos->prepare("SELECT COUNT(*) as total FROM tipos 
                                                WHERE ( categoria_id = :categoria ) and ( tipo = :tipo )");
            $asegurar_no_existencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $asegurar_no_existencia->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $asegurar_no_existencia->execute();
            $numero_total = $asegurar_no_existencia->fetch(PDO::FETCH_ASSOC);
            $numero_total = $numero_total['total'];
            

            if( $numero_total > 0 ){
                $_SESSION['existencia'] = true;
                header("location: ./nuevo.php");
            }
            else {
                try {
                    $base_de_datos->beginTransaction();

                    $sql = "INSERT INTO tipos (tipo, categoria_id, created_at) VALUES (:tipo, :categoria, :created_at)";
                    $seleccionado = $base_de_datos->prepare($sql);
                    $seleccionado->bindParam(':tipo', $tipo, PDO::PARAM_STR );
                    $seleccionado->bindParam(':categoria', $categoria, PDO::PARAM_INT );
                    $seleccionado->bindParam(':created_at', $fecha, PDO::PARAM_STR );
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
        }
        else {
            header("location: ./nuevo.php");
        }