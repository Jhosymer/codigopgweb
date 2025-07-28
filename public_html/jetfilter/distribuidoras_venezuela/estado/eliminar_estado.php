<?php
    session_start();
    if( !isset($_POST['id']) ){
        header("location: ./estados_distribuidores.php");
    }

    try{
        $url_base_datos = './../../conexion/conexion.php';
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

    $id = $_POST['id'];
    $fecha = date("Y-m-d H:i:s");

    $sql = "UPDATE distribuidora_estado SET deleted_at = :fecha WHERE ( id = :id )";
    $eliminar_distribuidora = $base_de_datos->prepare($sql) or die('Error al eliminar');
    $eliminar_distribuidora->bindParam(':id', $id, PDO::PARAM_INT);
    $eliminar_distribuidora->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $eliminar_distribuidora->execute();

    $_SESSION['eliminado'] = true;
    header("location: estados_distribuidores.php");
