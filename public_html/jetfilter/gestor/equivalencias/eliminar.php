<?php

    date_default_timezone_set('America/Caracas');   
    if( !isset( $_POST['id'] ) ){
        header("location: equivalencias.php");
    }
    session_start();

    include_once('../../../config/conexion.php');

    $id = $_POST['id'];
    $fecha = date("Y-m-d H:i:s");

    $sql = "UPDATE filtro_equivalencia SET deleted_at = :fecha WHERE ( id = :id )";
    $eliminar_equivalencia = $base_de_datos->prepare($sql) or die('Error al eliminar');
    $eliminar_equivalencia->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $eliminar_equivalencia->bindParam(':id', $id, PDO::PARAM_INT);
    $eliminar_equivalencia->execute();

    $_SESSION['eliminado'] = true;
    header('location: ./equivalencias.php');
