<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: ./tipo.php");
    }

    try {
        session_start();
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: tipo.php?errorBase=true");
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
                    window.location.href('./tipo.php');
                })
            </script>
        ";
    }

    if( isset( $_POST['editar_tipo'] ) ){
        $id = $_POST['id'];

        $fecha_updated = date("Y-m-d H:i:s"); 

        $categoria = $_POST['categoria'];
        $tipo =  $_POST['tipo'];

        $asegurar_no_existencia = $base_de_datos->prepare("SELECT COUNT(*) as total FROM tipos 
                WHERE ( categoria_id = :categoria ) and ( tipo = :tipo ) and ( id != :id )");
        $asegurar_no_existencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $asegurar_no_existencia->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        $asegurar_no_existencia->bindParam(':id', $id, PDO::PARAM_INT);
        $asegurar_no_existencia->execute();
        $numero_total = $asegurar_no_existencia->fetch(PDO::FETCH_ASSOC);
        $numero_total = $numero_total['total'];

        if( $numero_total > 0 ){
            $_SESSION['existencia'] = true;
            header("location: ./editar.php?id=$id");
        }
        else {
            try {
                $base_de_datos->beginTransaction();

                $sql = "UPDATE tipos SET tipo = :tipo, categoria_id = :categoria, updated_at = :fecha WHERE ( id = :id )";
                $actualizando = $base_de_datos->prepare($sql) or die("Error al actualizar");
                $actualizando->bindParam(':categoria', $categoria, PDO::PARAM_INT);
                $actualizando->bindParam(':fecha', $fecha_updated, PDO::PARAM_STR);
                $actualizando->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $actualizando->bindParam(':id', $id, PDO::PARAM_INT);
                $actualizando->execute();

                $base_de_datos->commit();

                $_SESSION['actualizado'] = true;
                header("location: ./tipo.php");
            }
            catch(PDOException $exception){
                $base_de_datos->rollBack();
                $_SESSION['error'] = true;
                header("location: ./editar.php?id=$id");
            }
        }
    } 
    else {
        header("location: ./tipo.php");
    }
?>