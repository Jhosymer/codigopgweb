<?php
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: productos.php");
    }

    try{
        session_start();
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

    $id = $_POST['id'];

    $fecha = date("Y-m-d H:i:s");

    $eliminar_aire_automotriz = $base_de_datos->prepare("UPDATE productos SET deleted_at = :fecha WHERE id = :id") or die('Error al eliminar');
    $eliminar_aire_automotriz->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $eliminar_aire_automotriz->bindParam(':id', $id, PDO::PARAM_STR);
    $eliminar_aire_automotriz->execute();

    $_SESSION['eliminado'] = true;
    header("location: ./productos.php");