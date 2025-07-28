<?php
    /* -----ARCHIVO PARA ELIMINAR UN REGISTRO EN AIRE AUTOMOTRIZ-------- */

    date_default_timezone_set('America/Caracas');
    session_start();

    //Si no existe id te redirigirá a otra ventana
    if( !isset($_POST['id']) ){
        header("location: ./espec_fluidos.php");
    }

    include_once('./../conexion/conexion.php');
    
    //Se eliminaran los registros en la tabla de fluidos y de filtro codificación.
    //En caso de que falle alguna subida, se cancelara todo
    try {
        $base_de_datos->beginTransaction(); //Hasta que la transacción no haga commit, no se va a guarda en la base de datos ningún cambio

        $eliminar_fluidos = $base_de_datos->prepare("UPDATE espec_fluidos SET deleted_at = :fecha WHERE id = :id") or die('Error al eliminar');
        $eliminar_fluidos->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $eliminar_fluidos->bindParam(':id', $id, PDO::PARAM_STR);
        $eliminar_fluidos->execute();

        $eliminar_filtro_codificacion = $base_de_datos->prepare("UPDATE filtro_codificacion SET deleted_at = :fecha WHERE id = :id") or die('Error al eliminar');
        $eliminar_filtro_codificacion->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $eliminar_filtro_codificacion->bindParam(':id', $id_codigo, PDO::PARAM_STR);
        $eliminar_filtro_codificacion->execute();

        $base_de_datos->commit(); //Se aplican los cambios

        $_SESSION['eliminado'] = true;
        header("location: ./espec_fluidos.php");
    }
    //En caso de que ocurra algún error
    catch(PDOException $exception){
        $base_de_datos->rollBack();
        $_SESSION['error'] = true;
        header("location: ./espec_fluidos");
    }



