<?php 
    include_once('../../../config/conexion.php');
    session_start();

    if(isset($_POST['editar_principal'])){
    
        try {
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Ha sucedido un error con la conexión a la base de datos',
                    })
                </script>
            <?php
        }

        $codigo = $_POST['codigo_filtro'];
        $id = $_POST['id'];
        $marca_filtro_1_post = $_POST['marca_filtro_1'] ?? ''; 
        $marca_filtro_2_post = $_POST['marca_filtro_2'] ?? ''; 
        $marca_filtro_3_post = $_POST['marca_filtro_3'] ?? ''; 

       $marca_filtro_1 = ($marca_filtro_1_post === '') ? null : (int)$marca_filtro_1_post;
       $marca_filtro_2 = ($marca_filtro_2_post === '') ? null : (int)$marca_filtro_2_post;
       $marca_filtro_3 = ($marca_filtro_3_post === '') ? null : (int)$marca_filtro_3_post;


        $seleccionado = $base_de_datos->prepare("SELECT COUNT(*) as total FROM nuevos_filtros");
        $seleccionado->execute();
        $limite_filtros = $seleccionado->fetch(PDO::FETCH_ASSOC);
        $limite = $limite_filtros['total'];
        if( $limite >= 21 ){
            $_SESSION['error_limite'] = true;
            header("location: ./editar.php?id=$id");
        
        }
        else {
            $unico = $base_de_datos->prepare("SELECT COUNT(*) as total FROM nuevos_filtros WHERE ( codigo = :codigo ) and ( id != :id )");
            $unico->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $unico->bindParam(':id', $id, PDO::PARAM_INT);
            $unico->execute();
            $unico = $unico->fetch(PDO::FETCH_ASSOC);
            $unico = $unico['total'];

            if( $unico == 0 ){
                $coincidencias = $base_de_datos->prepare("SELECT clase FROM filtro_codificacion 
                                                            WHERE ( codigo = :codigo ) and ( deleted_at is null )");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->setFetchMode( PDO::FETCH_ASSOC );
                $coincidencias->execute();
                if( $coincidencias->rowCount() > 0 ){
                    $filtro = $coincidencias->fetch(PDO::FETCH_ASSOC);
                    $clase = $filtro['clase'];
                    $tabla = "espec_" . $clase;
                    
                    $imagen = $base_de_datos->prepare("SELECT COUNT(*) as cantidad FROM $tabla 
                                                                    WHERE ( codigo = :codigo ) and ( deleted_at is null ) and (( imagen != '' ) or ( imagen is not null )) ");
                    $imagen->bindParam(':codigo', $codigo, PDO::PARAM_STR);
                    $imagen->setFetchMode(PDO::FETCH_ASSOC);
                    $imagen->execute();
                    $imagen = $imagen->fetch();
                    $imagen = $imagen['cantidad'];                                                

                    if( $imagen > 0 ){
                        switch($clase){
                            case "aireautomotriz": 
                                $clase = "Aire Automotriz";
                                break;
                            case "aireindustrial": 
                                $clase = "Aire Industrial";
                                break;
                            case "combustiblelinea": 
                                $clase = "Combustible en Linea";
                                break;
                            case "elemento": 
                                $clase = "Elemento";
                                break;
                            case "fluidos": 
                                $clase = "Fluidos";
                                break;
                            case "panel": 
                                $clase = "Panel";
                                break;
                            case "sellado": 
                                $clase = "Sellado";
                                break;
                        }
                        $insertar_registro_editar = $base_de_datos->prepare("UPDATE nuevos_filtros SET linea = :linea, codigo = :codigo, id_marca = :marca_filtro_1, id_marca1 = :marca_filtro_2, id_marca2 = :marca_filtro_3 WHERE id = :id") or die("Error al actualizar");
                        $insertar_registro_editar->bindParam(':linea', $clase, PDO::PARAM_STR);
                        $insertar_registro_editar->bindParam(':codigo', $codigo, PDO::PARAM_STR);
                        $insertar_registro_editar->bindParam(':id', $id, PDO::PARAM_INT);
                        $insertar_registro_editar->bindParam(':marca_filtro_1', $marca_filtro_1, PDO::PARAM_INT);
                        $insertar_registro_editar->bindParam(':marca_filtro_2', $marca_filtro_2, PDO::PARAM_INT);
                        $insertar_registro_editar->bindParam(':marca_filtro_3', $marca_filtro_3, PDO::PARAM_INT);
            
            $insertar_registro_editar->execute();

                        $_SESSION['actualizado'] = true;
                        
                        header("location: ./nuevos_filtros.php");
                    }
                    else {
                        $_SESSION['num_imagenes'] = true;
                           header("location: ./editar.php?id=$id");
                    }
                }
                else {
                    header("location: editar.php?id=$id&codigo_error=true");
                }
            }
            else {
                $_SESSION['repetido'] = true;
                header("location: ./editar.php?id=$id");
            }
        }
    }

?>