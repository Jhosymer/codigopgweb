<?php
    /* -----ARCHIVO PARA ACTUALIZAR LAS IMAGENES UN REGISTRO EN COMBUSTIBLE EN LINEA-------- */

    date_default_timezone_set('America/Caracas');
    session_start();
    
    //Si no existe id te redirigirá a otra ventana
    if( !isset($_POST['id']) ){
        header("location: ./espec_combustiblelinea.php");
    }
    else {
        $id = $_POST['id'];
    }
    
    include_once('./../conexion/conexion.php');

    //Si se pulso el boton para cambiar imagenes
    if( isset( $_POST['btnimg'] ) ){
        $sincronizado = date("Ymd");
        $fecha_updated = date("Y-m-d H:i:s"); 
        $fecha_actualiza = date("d-m-y");  

        //Se buscan las imagenes del filtro
        $seleccionado = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3, codigo, id_codigo
                                                FROM espec_combustiblelinea 
                                                WHERE id = :id") or die('Error al eliminar'); 
        $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);   
        $seleccionado->execute();   
        $seleccionado_imagen = $seleccionado->fetch();

        $codigo = $seleccionado_imagen['codigo'];
        $id_codigo = $seleccionado_imagen['id_codigo'];

        //Componente que devuelve un arreglo con las imagenes por las que se van a colocar ahora,
        //En caso de que no se cambiara una imagen queda igual
        include_once('./../componentes/galeria_update.php');
        
        //Datos a editar en la tabla de combustible en linea
        $argumentos = [$sincronizado, $imagen[0], $imagen[1], $imagen[2], $imagen[3], $fecha_updated, $id];
        //Datos a editar en la tabla de filtro codificación
        $argumentos_filtro_codificacion = [$fecha_actualiza, $sincronizado, $fecha_updated, $id_codigo];

        try {
            //Se crearan los registros en la tabla de combustible y de filtro codificación.
            //En caso de que falle alguna subida, se cancelara todo
            $base_de_datos->beginTransaction();

            $seleccionado = $base_de_datos->prepare("UPDATE espec_combustiblelinea SET sincronizado = ?, imagen = ?, imagen1 = ?, imagen2 = ?, imagen3 = ?, updated_at = ?  WHERE id = ?");
            $seleccionado->execute($argumentos);

            $actualizando = $base_de_datos->prepare("UPDATE filtro_codificacion SET fecha_actualiza = ?, sincronizado = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos_filtro_codificacion);

            $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $aplicacion = $base_de_datos->prepare($sql);
            $aplicacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $aplicacion->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $aplicacion->execute();

            $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $equivalencia_update = $base_de_datos->prepare($sql);
            $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $equivalencia_update->execute();

            //Si todo salio bien
            $base_de_datos->commit();
            
            $_SESSION['actualizado_imagenes'] = true;
            header("location: espec_combustiblelinea.php");
        }
        catch( PDOException $exception ){
            //Si hubo algún error
            $base_de_datos->rollBack(); 
            $_SESSION['error'] = true;
            header( "location: ./editar_imagenes.php?id=$id");
        }
    }
    //Si se pulson un boton para eliminar imagenes
    else if( isset( $_POST['imagen_eliminada'] ) ){
        $sincronizado = date("Ymd");
        $id_codigo = $_POST['id_codigo'];
        $id = $_POST['id'];

        //Intentara eliminar la imagen y cambiar la sincronización de todas las tablas
        try {
            $base_de_datos->beginTransaction();
            
            if( $_POST['imagen_eliminada'] == 'button-eliminar-imagen1' ){
                $sql = "UPDATE espec_combustiblelinea SET sincronizado = :sincronizado, imagen = '' WHERE id = :id";
            }
            else if( $_POST['imagen_eliminada'] == 'button-eliminar-imagen2' ){
                $sql = "UPDATE espec_combustiblelinea SET sincronizado = :sincronizado, imagen1 = '' WHERE id = :id";
            }
            else if( $_POST['imagen_eliminada'] == 'button-eliminar-imagen3' ){
                $sql = "UPDATE espec_combustiblelinea SET sincronizado = :sincronizado, imagen2 = '' WHERE id = :id";
            }
            else if( $_POST['imagen_eliminada'] == 'button-eliminar-imagen4' ){
                $sql = "UPDATE espec_combustiblelinea SET sincronizado = :sincronizado, imagen3 = '' WHERE id = :id";
            }

            $borrar_imagen = $base_de_datos->prepare($sql);
            $borrar_imagen->bindParam(':sincronizado', $sincronizado, PDO::PARAM_INT );
            $borrar_imagen->bindParam(':id', $id, PDO::PARAM_INT );
            $borrar_imagen->execute();

            $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( id_codigo = :id_codigo )";
            $aplicacion = $base_de_datos->prepare($sql);
            $aplicacion->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR);
            $aplicacion->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $aplicacion->execute();

            $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( id_codigo = :id_codigo )";
            $equivalencia_update = $base_de_datos->prepare($sql);
            $equivalencia_update->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR);
            $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $equivalencia_update->execute();

            //Si la imagen se elimino correctamente
            $base_de_datos->commit();
            
            $_SESSION['imagen_eliminada'] = true;
            header( "location: ./editar_imagenes.php?id=$id");
        }
        catch( PDOException $exception ){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
            header( "location: ./editar_imagenes.php?id=$id");
        }
    }

?>