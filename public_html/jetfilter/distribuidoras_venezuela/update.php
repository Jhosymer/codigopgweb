<?php 
    if( !isset($_POST['id']) ){
        header("location: espec_distribuidor.php");
    }

    try {
        session_start();
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

    if( isset( $_POST['btnimg'] ) ){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) as numero_seis FROM distribuidoras WHERE (calificacion = 6) and ( id != :id) and (deleted_at is null) ";
        $cantidad_seis = $base_de_datos->prepare($sql);
        $cantidad_seis->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $cantidad_seis->setFetchMode(PDO::FETCH_ASSOC);
        $cantidad_seis->execute();
        while( $fila = $cantidad_seis->fetch() ) {
            $cantidad []= $fila;
        }  
        $seis = $cantidad[0]['numero_seis'];

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $email2 = ( $_POST['email2'] != '' ) ? $_POST['email2'] : null;
        $telefono = $_POST['telefono'];
        $telefono2 = ( $_POST['telefono_2'] != '' ) ? $_POST['telefono_2'] : null; 
        $calificacion = ( $seis >= 4 && $_POST['calificacion'] == 6) ? 5 : (int)($_POST['calificacion']);
        $facebook = $_POST['facebook'];
        $twitter = $_POST['twitter'];
        $instagram = $_POST['instagram'];

        $argumentos = [$nombre, $email, $telefono, $telefono2, $calificacion, $facebook, $twitter, $instagram, $id];
        $seleccionado = $base_de_datos->prepare("UPDATE distribuidoras SET nombre = ?, email = ?, telefono = ?, telefono2 = ?, calificacion = ?, facebook = ?, twitter = ?, instagram = ? 
                                                WHERE id = ?") or die("Error al actualizar");
        $seleccionado->execute($argumentos);

        if( $seis >= 4 && $_POST['calificacion'] == 6){
            $_SESSION['limite'] = true;
        }
        else {
            $_SESSION['actualizado'] = true;
        }
        header("location: espec_distribuidor.php");
    }

?>