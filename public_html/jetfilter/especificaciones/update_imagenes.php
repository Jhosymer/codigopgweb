<?php 
    if( !isset($_POST['id']) ){
        header("location: especificaciones.php");
    }
    
    try {
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: especificaciones.php?errorBase=true");
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
                    window.location.href('especificaciones.php');
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


        $seleccionado = $base_de_datos->query("SELECT imagen, imagen1, imagen2, imagen3 FROM especificaciones WHERE codigo = $id") or die('Error al eliminar'); 
        $seleccionado_imagen = $seleccionado->fetch();

        for ($i=0; $i<4; $i++){
            $variable = "imagen" . ($i + 1);
            if ($_FILES[$variable]['type'] =='image/jpeg'){
                $imagen[$i] = $_FILES[$variable]['name'];
                $archivo = $_FILES[$variable]['tmp_name'];
                $ruta = "../../images/fichas-filtros/web";
                $ruta = $ruta . "/" . $imagen[$i];
                move_uploaded_file($archivo,$ruta);
                $imagen[$i] = substr($imagen[$i], 0, -4);
            }
            else {
                $imagen[$i] = $seleccionado_imagen[$i];
            }
        }
        
        $base_de_datos->query("UPDATE especificaciones SET imagen = '$imagen[0]', imagen1 = '$imagen[1]', imagen2 = '$imagen[2]', imagen3 = '$imagen[3]'  WHERE codigo = '$id'");
        header("location: especificaciones.php?page=$page&registros=$registros&actualizado_imagen=true&id=$id");
    }

?>