<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: productos.php");
    }

    try {
        session_start();
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: productos.php?errorBase=true");
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(PDOException $e){
        echo "
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema con la base de datos',
                timer: 1250,
                }).then(() => {
                    window.location.href('productos.php');
                })
            </script>
        ";
    }

    if( isset( $_POST['id'] ) ){
        $id = $_POST['id'];

        $fecha_updated = date("Y-m-d H:i:s"); 

        $nombre =  $_POST['nombre'];

        try {
            $base_de_datos->beginTransaction();

            $argumentos = [$nombre, $fecha_updated, $id];

            $actualizando = $base_de_datos->prepare("UPDATE productos SET nombre = ?, updated_at = ?  WHERE ( id = ? )") or die("Error al actualizar");
            $actualizando->execute($argumentos);

            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            header("location: ./productos.php");
        }
        catch(PDOException $exception){
            $base_de_datos->rollBack();
            $_SESSION['error'] = true;
            header("location: ./editar.php?id=$id");
        }
    } 
    else {
        header("location: ./productos.php");
    }
?>