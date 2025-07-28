<?php
    function buscar_codigos($linea, $base_de_datos, $codigo){
        switch ($linea):
            case "Aire automotriz":

                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_aireautomotriz WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_aire_automotriz = $coincidencias->fetch();

                if($num_rows_aire_automotriz['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_aireautomotriz 
                                                                            WHERE codigo= :codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro = $filtro['id'];
                    $id_especificacion = $filtro;
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break;
            case "Aire industrial":

                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_aireindustrial WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_aire_industrial = $coincidencias->fetch();

                if($num_rows_aire_industrial['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_aireindustrial 
                                                                                WHERE codigo= :codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro = $filtro['id'];
                    $id_especificacion = $filtro;
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break;

            case "Combustible linea":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_combustiblelinea WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_combustible_linea = $coincidencias->fetch();

                if($num_rows_combustible_linea['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_combustiblelinea
                                                                            WHERE codigo= :codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro = $filtro['id'];
                    $id_especificacion = $filtro;
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }

                break;
            case "Elemento":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_elemento WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_elemento = $coincidencias->fetch();

                if($num_rows_elemento['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_elemento
                                                                            WHERE codigo= :codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro = $filtro['id'];
                    $id_especificacion = $filtro;
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break;
            case "Panel":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_panel WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_elemento = $coincidencias->fetch();

                if($num_rows_elemento['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_panel
                                                                            WHERE codigo= :codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro = $filtro['id'];
                    $id_especificacion = $filtro;

                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break; 
            case "Sellado":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_sellado WHERE codigo= :codigo");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_sellado = $coincidencias->fetch();

                if($num_rows_sellado['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT id FROM espec_sellado
                                                                            WHERE codigo=:codigo");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    $filtro_especificacion = $filtro['id'];
                    $id_especificacion = $filtro_especificacion;
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break;    
            endswitch;

            return $id_especificacion;
        }