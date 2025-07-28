<?php
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: marcas_aplicaciones.php");
    }

    include_once('./../conexion/conexion.php');
    session_start();

    $id = $_POST['id'];

    $fecha = date("Y-m-d H:i:s");

    //Modificación de la fecha de eliminar para que aparezca como eliminado el registro
    $eliminar_vehiculo = $base_de_datos->prepare("UPDATE aplicacion_marca SET deleted_at = :fecha WHERE ( id = :id )") or die('Error al eliminar');
    $eliminar_vehiculo->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $eliminar_vehiculo->bindParam(':id', $id, PDO::PARAM_INT);
    $eliminar_vehiculo->setFetchMode(PDO::FETCH_ASSOC);
    $eliminar_vehiculo->execute();

    $_SESSION['eliminado'] = true;
    header("location: marcas_aplicaciones.php");