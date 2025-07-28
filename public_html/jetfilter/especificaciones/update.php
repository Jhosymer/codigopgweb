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

        $id = $_POST['id'];
        $page = $_POST['page'];
        $registros = $_POST['registros'];

        $seleccionado = $base_de_datos->query("SELECT imagen, imagen1, imagen2, imagen3 FROM especificaciones WHERE codigo = $id") or die('Error al eliminar'); 
        $seleccionado_imagen = $seleccionado->fetch();

        $itemname = $_POST['itemname'];
        $itemcode = $_POST['itemcode'];
        $esp1 = isset( $_POST['esp1'] ) ? $_POST['esp1'] : "";
        $desc1 = isset( $_POST['desc1'] ) ? $_POST['desc1'] : "";
        $esp2 = isset( $_POST['esp2'] ) ? $_POST['esp1'] : "";
        $desc2 = isset( $_POST['desc2'] ) ? $_POST['desc2'] : "";
        $esp3 = isset( $_POST['esp3'] ) ? $_POST['esp1'] : "";
        $desc3 = isset( $_POST['desc3'] ) ? $_POST['desc3'] : "";
        $esp4 = isset( $_POST['esp4'] ) ? $_POST['esp1'] : "";
        $desc4 = isset( $_POST['desc4'] ) ? $_POST['desc4'] : "";
        $esp5 = isset( $_POST['esp5'] ) ? $_POST['esp1'] : "";
        $desc5 = isset( $_POST['desc5'] ) ? $_POST['desc5'] : "";
        $esp6 = isset( $_POST['esp6'] ) ? $_POST['esp1'] : "";
        $desc6 = isset( $_POST['desc6'] ) ? $_POST['desc6'] : "";
        $esp7 = isset( $_POST['esp7'] ) ? $_POST['esp1'] : "";
        $desc7 = isset( $_POST['desc7'] ) ? $_POST['desc7'] : "";
        $esp8 = isset( $_POST['esp8'] ) ? $_POST['esp1'] : "";
        $desc8 = isset( $_POST['desc8'] ) ? $_POST['desc8'] : "";
        $esp9 = isset( $_POST['esp9'] ) ? $_POST['esp1'] : "";
        $desc9 = isset( $_POST['desc9'] ) ? $_POST['desc9'] : "";
        $esp10 = isset( $_POST['esp10'] ) ? $_POST['esp1'] : "";
        $desc10 = isset( $_POST['desc10'] ) ? $_POST['desc10'] : "";
        $linea = $_POST['linea'];

        $caracteres_a_reemplazar = ['-'," ","_"];
        $codigo_buscar = str_replace($caracteres_a_reemplazar,'',$itemcode);

        for ($i=0; $i<4; $i++){
            $variable = "imagen" . ($i + 1);
            if ($_FILES[$variable]['type'] =='image/jpeg'){
                $imagen[$i] = $_FILES[$variable]['name'];
                $archivo = $_FILES[$variable]['tmp_name'];
                $ruta = "../../images/fichas-filtros/web";
                $ruta = $ruta . "/" . $imagen[$i];
                move_uploaded_file($archivo,$ruta);
                $nombre = pathinfo($ruta);
                $imagen[$i] = $nombre['filename'];
            }
            else {
                $imagen[$i] = $seleccionado_imagen[$i];
            }
        }


        $base_de_datos->query("UPDATE especificaciones SET itemname = '$itemname' , itemcode = '$itemcode', U_Esp1 = '$esp1', U_Desc1 = '$desc1', U_Esp2 = '$esp2', U_Desc2 = '$desc2', U_escp3 = '$esp3', U_Desc3 = '$desc3', U_Esp4 = '$esp4', U_Desc4 = '$desc4', U_Esp5 = '$esp5', U_Desc5 = '$desc5', U_Esp6 = '$esp6', U_Desc6 = '$desc6', U_Esp7 = '$esp7', U_Desc7 = '$desc7', U_Esp8 = '$esp8', U_Desc8 = '$desc8', U_Esp9 = '$esp9', U_Desc9 = '$desc9', U_Esp10 = '$esp10', U_Desc10 = '$desc10', linea = '$linea', imagen = '$imagen[0]', imagen1 = '$imagen[1]', imagen2 = '$imagen[2]', imagen3 = '$imagen[3]'  WHERE codigo = '$id'") or die("Error al actualizar");
        header("location: especificaciones.php?page=$page&registros=$registros&actualizado=true&id=$id");
    }

?>