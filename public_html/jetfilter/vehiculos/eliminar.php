<?php
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: vehiculos.php");
    }
    session_start();

    include_once('./../conexion/conexion.php');

    $id = $_POST['id'];

    $fecha = date("Y-m-d H:i:s");

    //Se modifica la fecha de deleted_at para que se muestre como eliminado
    $eliminar_vehiculo = $base_de_datos->prepare("UPDATE aplicacion_vehiculo SET deleted_at = :fecha WHERE ( id = :id )") or die('Error al eliminar');
    $eliminar_vehiculo->bindParam(':fecha',$fecha,PDO::PARAM_STR);
    $eliminar_vehiculo->bindParam(':id',$id,PDO::PARAM_INT);
    $eliminar_vehiculo->setFetchMode(PDO::FETCH_ASSOC);
    $eliminar_vehiculo->execute();

    $_SESSION['eliminado'] = true;
    header("location: vehiculos.php");