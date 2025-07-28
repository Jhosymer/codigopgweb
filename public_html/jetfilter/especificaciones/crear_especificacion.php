<?php 
    include_once('./../conexion/conexion.php');

    if(isset($_POST['especificacion'])){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $itemname = ($_POST['itemname'] != null) ? $_POST['itemname'] : "";
        $itemcode = ($_POST['itemcode'] != null) ? $_POST['itemcode'] : "";
        $u_esp_1 = ($_POST['u_esp_1'] != null) ? $_POST['u_esp_1'] : "";
        $u_desc_1 = ($_POST['u_desc_1'] != null) ? $_POST['u_desc_1'] : "";
        $u_esp_2 = ($_POST['u_esp_2'] != null) ? $_POST['u_esp_2'] : "";
        $u_desc_2 = ($_POST['u_desc_2'] != null) ? $_POST['u_desc_2'] : "";
        $u_esp_3 = ($_POST['u_esp_3'] != null) ? $_POST['u_esp_3'] : "";
        $u_desc_3 = ($_POST['u_desc_3'] != null) ? $_POST['u_desc_3'] : "";
        $u_esp_4 = ($_POST['u_esp_4'] != null) ? $_POST['u_esp_4'] : "";
        $u_desc_4 = ($_POST['u_desc_4'] != null) ? $_POST['u_desc_4'] : "";
        $u_esp_5 = ($_POST['u_esp_5'] != null) ? $_POST['u_esp_5'] : "";
        $u_desc_5 = ($_POST['u_desc_5'] != null) ? $_POST['u_desc_5'] : "";
        $u_esp_6 = ($_POST['u_esp_6'] != null) ? $_POST['u_esp_6'] : "";
        $u_desc_6 = ($_POST['u_desc_6'] != null) ? $_POST['u_desc_6'] : "";
        $u_esp_7 = ($_POST['u_esp_7'] != null) ? $_POST['u_esp_7'] : "";
        $u_desc_7 = ($_POST['u_desc_7'] != null) ? $_POST['u_desc_7'] : "";
        $u_esp_8 = ($_POST['u_esp_8'] != null) ? $_POST['u_esp_8'] : "";
        $u_desc_8 = ($_POST['u_desc_8'] != null) ? $_POST['u_desc_8'] : "";
        $u_esp_9 = ($_POST['u_esp_9'] != null) ? $_POST['u_esp_9'] : "";
        $u_desc_9 = ($_POST['u_desc_9'] != null) ? $_POST['u_desc_9'] : "";
        $u_esp_10 = ($_POST['u_esp_10'] != null) ? $_POST['u_esp_10'] : "";
        $u_desc_10 = ($_POST['u_desc_10'] != null) ? $_POST['u_desc_10'] : "";
        $linea = ($_POST['linea'] != null) ? $_POST['linea'] : "";

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
                $imagen[$i] = substr($imagen[$i], 0, -4);
            }
            else {
                $imagen[$i] = "";
            }
        }

        $base_de_datos->query("INSERT INTO especificaciones (itemname, itemcode, codigobuscar, U_Esp1, U_Desc1, U_Esp2, U_Desc2, U_Escp3, U_Desc3, U_Esp4, U_Desc4, U_Esp5, U_Desc5, U_Esp6, U_Desc6, U_Esp7, U_Desc7, U_Esp8, U_Desc8, U_Esp9, U_Desc9, U_Esp10, U_Desc10, linea, imagen, imagen1, imagen2, imagen3) VALUES ('$itemname', '$itemcode', '$codigo_buscar', '$u_esp_1', '$u_desc_1', '$u_esp_2', '$u_desc_2', '$u_esp_3', '$u_desc_3', '$u_esp_4', '$u_desc_4', '$u_esp_5', '$u_desc_5', '$u_esp_6', '$u_desc_6', '$u_esp_7', '$u_desc_7', '$u_esp_8', '$u_desc_8', '$u_esp_9', '$u_desc_9', '$u_esp_10', '$u_desc_10', '$linea', '$imagen[0]', '$imagen[1]', '$imagen[2]', '$imagen[3]')");
        header("location: especificaciones.php?nuevo=true");
    }