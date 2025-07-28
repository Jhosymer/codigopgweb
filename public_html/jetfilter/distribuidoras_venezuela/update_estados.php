<?php 
    if( !isset($_POST['id']) ){
        header("location: estados_distribuidores.php");
    }

    try {
        session_start();
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: estados_distribuidores.php?errorBase=true");
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
                    window.location.href('estados_distribuidores.php');
                })
            </script>
        ";
    }


    if( isset( $_POST['btnimg'] ) ){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) as numero_seis FROM distribuidoras WHERE calificacion = 6";
        $cantidad_seis = $base_de_datos->prepare($sql);
        $cantidad_seis->setFetchMode(PDO::FETCH_ASSOC);
        $cantidad_seis->execute();
        while( $fila = $cantidad_seis->fetch() ) {
            $cantidad []= $fila;
        }  
        $seis = $cantidad[0]['numero_seis'];

        $id = $_POST['id'];
        $id_distribuidora = $_POST['id_distribuidora'];
        $estado = $_POST['estado'];
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];

        $argumentos = [$id_distribuidora, $direccion, $estado, $ciudad, $id];
        $seleccionado = $base_de_datos->prepare("UPDATE distribuidora_estado SET id_distribuidora = ?, direccion = ?, estado = ?, ciudad = ? 
                                                WHERE id = ?") or die("Error al actualizar");
        $seleccionado->execute($argumentos);

        $_SESSION['actualizado'] = true;
        header("location: estados_distribuidores.php");
    }

?>