<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset( $_POST['id'] ) ){
        header("location: ./categorias.php");
    }

    try {
        session_start();
        $url_base_datos = '../../../config/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: ./categorias.php?errorBase=true");
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
                    window.location.href('./categorias.php');
                })
            </script>
        ";
    }

    if( $_POST['categorias_editar'] ){

        $id = $_POST['id'];
        $categoria = $_POST['categoria'];
        $clase = $_POST['clase'];
        $producto = $_POST['producto'];
        
        $fecha_updated = date("Y-m-d H:i:s"); 

        $asegurar_no_existencia = $base_de_datos->prepare("SELECT COUNT(*) as total FROM categorias 
                                                WHERE ( categoria = :categoria ) and ( producto_id = :producto_id ) and ( id != :id ) and ( deleted_at is null )");
        $asegurar_no_existencia->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $asegurar_no_existencia->bindParam(':producto_id', $producto, PDO::PARAM_INT);
        $asegurar_no_existencia->bindParam(':id', $id, PDO::PARAM_INT);
        $asegurar_no_existencia->execute();
        $numero_total = $asegurar_no_existencia->fetch(PDO::FETCH_ASSOC);

        if( $numero_total['total'] > 0 ){
            $_SESSION['existencia'] = true;
            header("location: ./editar.php?id=$id");
        }
        else {
            $sql = "UPDATE categorias SET categoria = :categoria, producto_id = :producto_id, clase = :clase WHERE id = :id";
            $editar_categoria = $base_de_datos->prepare($sql);
            $editar_categoria->bindParam(':categoria', $categoria, PDO::PARAM_STR);
            $editar_categoria->bindParam(':producto_id', $producto, PDO::PARAM_INT);
            $editar_categoria->bindParam(':clase', $clase, PDO::PARAM_STR);
            $editar_categoria->bindParam(':id', $id, PDO::PARAM_STR);
            $editar_categoria->execute();

            $_SESSION['actualizado'] = true;
            header("location: ./categorias.php");
        }
    }
    else {
        header("location: ./categorias.php");
    }

?>