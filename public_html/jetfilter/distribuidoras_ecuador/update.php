<?php 
    if( !isset($_POST['id']) ){
        header("location: espec_distribuidor.php");
    }

    try {
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: espec_distribuidor.php?errorBase=true");
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
                    window.location.href('espec_distribuidor.php');
                })
            </script>
        ";
    }


    if(isset($_POST['btnimg'])){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = $_POST['id'];
        $page = $_POST['page'];
        $registros = $_POST['registros'];

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $estado = $_POST['estado'];
        $ciudad = $_POST['ciudad'];
        $telefono = $_POST['telefono'];

        $base_de_datos->query("UPDATE distribuidoras SET nombre = '$nombre', email = '$email', estado = '$estado', ciudad = '$ciudad', telefono = '$telefono' WHERE id = '$id'") or die("Error al actualizar");
        header("location: espec_distribuidor.php?page=$page&registros=$registros&actualizado=true&id=$id");
    }

?>