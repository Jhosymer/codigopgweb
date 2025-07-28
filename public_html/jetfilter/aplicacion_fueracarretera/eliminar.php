<?php

date_default_timezone_set('America/Caracas');
    //Si no existe el id de la aplicación a eliminar te redirigirá
    if( !isset($_POST['id']) ){
        header("location: ./aplicacion_fueracarretera.php");
    }
    session_start();

    include_once('./../conexion/conexion.php');

    $id = $_POST['id'];
    $fecha = date("Y-m-d H:i:s");
    
    //Se cambia la fecha de eliminado
    try {
        //A partir de este momento los cambios no se guaradan directamente en la base de datos
        $base_de_datos->beginTransaction();

        $eliminar_aplicacion_carretera = $base_de_datos->prepare("UPDATE aplicacion SET deleted_at = :fecha 
                                                    WHERE id = :id") or die('Error al eliminar');
        $eliminar_aplicacion_carretera->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $eliminar_aplicacion_carretera->bindParam(':id', $id, PDO::PARAM_STR);
        $eliminar_aplicacion_carretera->execute();

        //Instrucción que mandara todos los cambios a la base de datos. 
        //Hasta que no se ejecute, los cambios no se veran
        $base_de_datos->commit();

        //Si tuvo exito
        $_SESSION['eliminado'] = true;
        header("location: ./aplicacion_fueracarretera.php");
    }
    catch(Exception $e){
        $base_de_datos->rollback();
        $_SESSION['error'] = true;
        header("location: ./aplicacion_fueracarretera.php");
    }