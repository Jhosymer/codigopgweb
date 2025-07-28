<?php

    if( !isset($_POST['id']) ){
        header("location: ./nuevos_filtros.php");
    }
    session_start();

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

    $id = $_POST['id'];

    $seleccionado = $base_de_datos->prepare("SELECT COUNT(*) as total FROM nuevos_filtros");
    $seleccionado->execute();
    $limite_filtros = $seleccionado->fetch(PDO::FETCH_ASSOC);
    $limite = $limite_filtros['total'];
    if($limite <= 6){
        $_SESSION['error_limite'] = true;
        header("location: ./nuevos_filtros.php");
    }
    else{
        $eliminar_registro = $base_de_datos->prepare("DELETE FROM nuevos_filtros WHERE id = :id") or die('Error al eliminar');
        $eliminar_registro->bindParam(':id', $id, PDO::PARAM_INT);
        $eliminar_registro->execute();
        $_SESSION['eliminado'] = true;
        header("location: ./nuevos_filtros.php");
    }

?>