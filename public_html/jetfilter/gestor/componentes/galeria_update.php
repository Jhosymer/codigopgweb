<?php
    //Comprobará si alguna imagen se ha subido al formulario y si es del tamaño correcto

    for ($i = 0; $i < 4; $i++){
        $variable = "imagen" . ($i + 1);
        if ( $_FILES[$variable]['type'] =='image/jpeg' ){
            if($i == 0){
                $imagen[$i] = $codigo;
            }
            else {
                $imagen[$i] = $codigo . "-" . ($i);
            }
            $archivo = $_FILES[$variable]['tmp_name'];
            $ruta = "./../../../images/fichas-filtros/web/";
            $ruta = $ruta . "/" . $imagen[$i] . ".jpg";
            //Se obtiene el tamaño de la imagen
            $variables = getimagesize($archivo);
            //Si es del tamaño correcto se mueve a la carpeta images/ficha-filtros/web
            if($variables[0] >= 1400 && $variables[0] <= 1600 && $variables[1] >= 1200 && $variables[1] <= 1300){
                move_uploaded_file($archivo, $ruta);
                $nombre = pathinfo($ruta);
            }
            //Sino se deja vacio el campo en la base de datos
            else {
                echo "No cumple con las dimensionmes";
                echo "Ancho: ".$variables[0];
                echo "Largo:".$variables[1];
                $imagen[$i] = "";
            }
        }
        //Si no es del formato se deja vacio el campo en la base de datos
        else {
            $seleccionado_imagen[$i] = isset( $seleccionado_imagen[$i] ) ? $seleccionado_imagen[$i] : '';
            $imagen[$i] = $seleccionado_imagen[$i];
        }
    }