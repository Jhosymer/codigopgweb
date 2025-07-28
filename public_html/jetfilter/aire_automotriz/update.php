<?php 
    /* -----ARCHIVO PARA ACTUALIZAR UN REGISTRO EN AIRE AUTOMOTRIZ-------- */

    date_default_timezone_set('America/Caracas');
    session_start();

    //Si no existe id te redirigirá a otra ventana
    if( !isset($_POST['id']) ){
        header("location: ./espec_aireautomotriz.php");
    }
    else {
        $id = $_POST['id'];
    }

    include_once('./../conexion/conexion.php');
    
    //Si se pulso el boton para cambiar imagenes
    if( isset($_POST['codigo']) ){
        $codigo = $_POST['codigo'];
        $id_codigo = $_POST['id_codigo'];

        //Se ejecuta la funcion, que devolverá el numero de filtros
        //que no esten eliminados que contengas ese código, no tomara en cuenta el id que tenga el id agregado
        //Parametro $codigo (String): El codigo que se va a comprobar.
        //Parametro $base_de_datos (object): Es el objeto que tiene la conexión a la base de datos
        //Parametro $id (Int): Este es el id actual del filtro actual (no se tomara en cuenta en la busqueda)
        include_once('./../plantillas/comprobar_existencia_filtro.php');
        $numeroFiltros = comprobarExistenciaFiltro($codigo, $base_de_datos, $id_codigo);

        //Si no existe filtros con ese código se creará
        if( $numeroFiltros == 0 ){
            $codigoActual = $_POST['codigo_actual'];
            $id_tipo = $_POST['tipo'];

            $sincronizado = date("Ymd");
            $fecha_updated = date("Y-m-d H:i:s"); 
            $fecha_actualiza = date("d-m-y"); 

            $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_aireautomotriz
                                                    WHERE id = :id"); 
            $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                        
            $seleccionado->execute();
            $seleccionado_imagen = $seleccionado->fetch();
            
            $seleccionado = $base_de_datos->prepare("SELECT codigo, id_codigo
                                                    FROM espec_aireautomotriz
                                                    WHERE id = :id"); 
            $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);     
            $seleccionado->setFetchMode( PDO::FETCH_ASSOC );                                      
            $seleccionado->execute();
            $codigo = $seleccionado->fetch();
            $id_codigo = $codigo['id_codigo'];
            $codigo_anterior = $codigo['codigo'];

            //Verificá si se escogio un tipo o se dejo la opción N/D (que no tiene).
            if( $id_tipo == 'N/D' ){
                $tipo = 'N/D';
                $id_tipo = null;
            }
            else {
                //Si se seleccionó se busca en la base de datos y se guarda el id y el tipo
                $seleccionado = $base_de_datos->prepare("SELECT id, tipo
                                                        FROM tipos
                                                        WHERE id = :id_tipo"); 
                $seleccionado->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);                                        
                $seleccionado->execute();
                $seleccionado_tipo = $seleccionado->fetch();
                $tipo = $seleccionado_tipo['tipo'];
                $id_tipo = $seleccionado_tipo['id'];
            }

            // Se guardan los elementos enviados por el formulario
            $filtracion = ( $_POST['filtracion'] == '' ) ? null : $_POST['filtracion'];
            $codigo = $_POST['codigo'];
            $diametro_ext = $_POST['diametro_ext1'];
            $diametro_ext2 = $_POST['diametro_ext2'];
            $diametro_int = $_POST['diametro_int1'];
            $diametro_int2 = $_POST['diametro_int2'];
            $altura = $_POST['altura'];
            $und_empaque = ( $_POST['und_empaque'] == '' ) ? 0 : $_POST['und_empaque'];
            $detalle1 = ( $_POST['detalle1'] == '' ) ? 'N/D' : $_POST['detalle1'];
            $detalle2 = ( $_POST['detalle2'] == '' ) ? 'N/D' : $_POST['detalle2'];
            $codigo_barra = $_POST['codigo_barra'];
            

            //Se eliminan los caracteres especiales y espacios, para la variable $codigo_buscar.
            $caracteres_a_reemplazar = ['-'," ","_"];
            $codigo_buscar = str_replace($caracteres_a_reemplazar,'',$codigo);

            //Componente que guarda en un arreglo $imagen las imagenes que se enviaron del formulario
            //Estan ordenados las imagenes en el arreglo (0 => imagen, 1 => imagen1, 2 => imagen2, 3 => imagen3)
            include_once('./../componentes/galeria_update.php');

            //Datos a guardar en la tabla de aire automotriz
            $argumentos = [$codigo, $codigo_buscar, $tipo, $diametro_ext, $diametro_ext2, $diametro_int, $diametro_int2, $altura, $detalle1, $detalle2, $sincronizado, $imagen[0], $imagen[1], $imagen[2], $imagen[3], $fecha_updated, $id];
            //Datos a guardar en la tabla de filtro codificación
            $argumentos_filtro_codificacion = [$codigo, $codigo_buscar, $codigo_barra, $id_tipo, $filtracion, $und_empaque, $fecha_actualiza, $sincronizado, $fecha_updated, $id_codigo];

            try {
                //Se crearan los registros en la tabla de aire automotriz y de filtro codificación.
                //En caso de que falle alguna subida, se cancelara todo
                $base_de_datos->beginTransaction();

                if( $codigo_anterior != $codigo ){
                    $sql = "UPDATE aplicacion SET codigo = :codigo WHERE id_codigo = :id_codigo"; 
                    $aplicacion_update = $base_de_datos->prepare($sql);
                    $aplicacion_update->bindParam(':codigo', $codigo, PDO::PARAM_STR );
                    $aplicacion_update->bindParam(':id_codigo', $id_codigo, PDO::PARAM_INT);
                    $aplicacion_update->execute();

                    $sql = "UPDATE filtro_equivalencia SET codigo = :codigo WHERE id_codigo = :id_codigo"; 
                    $equivalencia_update = $base_de_datos->prepare($sql);
                    $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR );
                    $equivalencia_update->bindParam(':id_codigo', $id_codigo, PDO::PARAM_INT);
                    $equivalencia_update->execute();
                }

                $actualizando = $base_de_datos->prepare("UPDATE espec_aireautomotriz SET codigo = ?, codigo_buscar = ?, tipo = ?, diametroext1 = ?, diametroext2 = ?, diametroint1 = ?, diametroint2 = ?, altura = ?, detalle1 = ?, detalle2 = ?, sincronizado = ?, imagen = ?, imagen1 = ?, imagen2 = ?, imagen3 = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
                $actualizando->execute($argumentos);
                
                $actualizando = $base_de_datos->prepare("UPDATE filtro_codificacion SET codigo = ?, codigo_buscar = ?, codigo_barra = ?, id_tipo = ?, filtracion = ?, und_empaque = ?, fecha_actualiza = ?, sincronizado = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
                $actualizando->execute($argumentos_filtro_codificacion);

                $sql = "UPDATE aplicacion SET sincronizado = :sincronizado, codigo = :codigo WHERE ( codigo = :codigoActual )";
                $aplicacion_update = $base_de_datos->prepare($sql);
                $aplicacion_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
                $aplicacion_update->bindParam(':codigoActual', $codigoActual, PDO::PARAM_STR);
                $aplicacion_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
                $aplicacion_update->execute();

                $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado, codigo = :codigo WHERE codigo = :codigoActual";
                $equivalencia_update = $base_de_datos->prepare($sql);
                $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
                $equivalencia_update->bindParam(':codigoActual', $codigoActual, PDO::PARAM_STR);
                $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
                $equivalencia_update->execute();

                //Si todo va bien
                $base_de_datos->commit();

                $_SESSION['actualizado'] = true;
                header("location: ./espec_aireautomotriz.php");
            }
            catch(PDOException $exception){
                //Si sucedió algún error
                $base_de_datos->rollBack();
                $_SESSION['error'] = true;
                header("location: ./editar.php?id=$id");
            }
        }
        //Si un filtro con ese código existía
        else {
            $_SESSION['yaExistencia'] = true;
            header("location: ./editar.php?id=$id");
        }
    } 
    //Si no se ha enviado un formulario con el campo código
    else {
        header("location: ./espec_aireautomotriz.php");
    }
?>