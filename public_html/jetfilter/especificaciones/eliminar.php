<?php

    if( !isset($_GET['id']) ){
        header("location: especificaciones.php");
    }

    try{
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('No encontró la base de datos');
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
    }
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

    $id = $_GET['id'];
    $page = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
    $registros = ( isset($_GET['registros']) ) ? $_GET['registros'] : 10;

    $base_de_datos->query("DELETE FROM especificaciones WHERE codigo = $id") or die('Error al eliminar');
    header("location: especificaciones.php?page=$page&registros=$registros");