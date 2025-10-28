<?php
include_once('../../../config/conexion.php');
  session_start();

    if(isset($_POST['nuevo_principal'])){
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
        $id_marca1_post = $_POST['marca_filtro_1'];
    $id_marca2_post = $_POST['marca_filtro_2'];
    $id_marca3_post = $_POST['marca_filtro_3'];

   

    $id_marca1 = ($id_marca1_post === '') ? null : (int)$id_marca1_post;
    $id_marca2 = ($id_marca2_post === '') ? null : (int)$id_marca2_post;
    $id_marca3 = ($id_marca3_post === '') ? null : (int)$id_marca3_post;

        $seleccionado = $base_de_datos->prepare("SELECT COUNT(*) as total FROM nuevos_filtros");
        $seleccionado->execute();
        $limite_filtros = $seleccionado->fetch(PDO::FETCH_ASSOC);
        $limite = $limite_filtros['total'];
        if( $limite >= 20 ){
            $_SESSION['error_limite'] = true;
            header("location: ./nuevo.php");
        }
        else {
            $unico = $base_de_datos->prepare("SELECT COUNT(*) as total FROM nuevos_filtros WHERE ( codigo = :codigo )");
            $unico->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $unico->execute();
            $unico = $unico->fetch(PDO::FETCH_ASSOC);
            $unico = $unico['total'];


            if( $unico == 0 ){
                $coincidencias = $base_de_datos->prepare("SELECT clase,codigo AS codigo_db FROM filtro_codificacion 
                                                            WHERE ( codigo = :codigo ) and ( deleted_at is null )");
                $coincidencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
                $coincidencias->setFetchMode( PDO::FETCH_ASSOC );
                $coincidencias->execute();
                if( $coincidencias->rowCount() > 0 ){
                    $filtro = $coincidencias->fetch(PDO::FETCH_ASSOC);
                    $clase = $filtro['clase'];
                    $codigo_db = $filtro['codigo_db'];
                    $tabla = "espec_" . $clase;
                    
                    $imagen = $base_de_datos->prepare("SELECT COUNT(*) as cantidad FROM $tabla 
                                                                    WHERE ( codigo = :codigo ) and ( deleted_at is null ) and (( imagen != '' ) or ( imagen is not null ))");
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
                        $argumentos = [$clase, $codigo_db, $id_marca1, $id_marca2, $id_marca3]; 
                        $insertar_registro_nuevo = $base_de_datos->prepare("INSERT INTO nuevos_filtros (linea, codigo, id_marca, id_marca1, id_marca2) VALUES (?, ?, ?, ?, ?) ") or die("Error al actualizar");
                        $insertar_registro_nuevo->execute($argumentos);

                        $_SESSION['nuevo'] = true;
                        header("location: nuevos_filtros.php?imagen=$imagen");
                    }
                    else {
                        $_SESSION['num_imagenes'] = true;
                        header("location: ./nuevo.php");
                    }
                }
                else {
                    header("location: nuevo.php?codigo_error=true");
                }
            }
            else {
                $_SESSION['repetido'] = true;
                header("location: ./nuevo.php");
            }
        }
    }

?>