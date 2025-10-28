<?php 
    include_once('../../../config/conexion.php');

    if( !isset( $_POST['categoria'] ) ){
        header('./../');
    }

    $categoria = $_POST['categoria'];

    $seleccionar_tipo = $base_de_datos->prepare('SELECT * FROM tipos WHERE ( categoria_id = :categoria_id and deleted_at is null )');
    $seleccionar_tipo->bindParam(':categoria_id', $categoria, PDO::PARAM_INT);
    $seleccionar_tipo->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionar_tipo->execute();

    $output = '';
    while( $tipo_seleccionado = $seleccionar_tipo->fetch() ){
        $id = $tipo_seleccionado['id'];
        $tip = $tipo_seleccionado['tipo'];
        $output .= "<option value='$id' >$tip</option>";
    }

    echo json_encode($output);
?>