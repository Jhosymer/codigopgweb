<?php
    function buscar_imagenes($linea, $base_de_datos, $codigo){
        switch ($linea):
            case "Aire automotriz":

                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_aireautomotriz WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_aire_automotriz = $coincidencias->fetch();

                if($num_rows_aire_automotriz['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_aireautomotriz 
                                                                            WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
                break;
            case "Aire industrial":

                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_aireindustrial WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_aire_industrial = $coincidencias->fetch();

                if($num_rows_aire_industrial['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_aireindustrial 
                                                                                WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }
                break;

            case "Combustible linea":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_combustiblelinea WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_combustible_linea = $coincidencias->fetch();

                if($num_rows_combustible_linea['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_combustiblelinea
                                                                            WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }

                break;
            case "Elemento":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_elemento WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_elemento = $coincidencias->fetch();

                if($num_rows_elemento['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_elemento
                                                                            WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }
                break;
            case "Fluidos":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_fluidos WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_fluidos = $coincidencias->fetch();

                if($num_rows_fluidos['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_fluidos
                                                                            WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }
                break;
            case "Panel":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_panel WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_panel = $coincidencias->fetch();

                if($num_rows_panel['count(*)'] > 0){

                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_panel
                                                                            WHERE codigo= :codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }
                break; 
            case "Sellado":
                $coincidencias = $base_de_datos->prepare("SELECT count(*) FROM espec_sellado WHERE codigo= :codigo and deleted_at is null");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->execute();
                $num_rows_sellado = $coincidencias->fetch();

                if($num_rows_sellado['count(*)'] > 0){
                    $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 FROM espec_sellado
                                                                            WHERE codigo=:codigo and deleted_at is null");
                    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                    $seleccionado->execute();
                    $imagenes = $seleccionado->fetch(PDO::FETCH_ASSOC);
                    return $imagenes;
                }
                break;    
            endswitch;

    }