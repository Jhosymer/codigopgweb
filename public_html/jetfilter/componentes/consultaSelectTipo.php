<?php 
    //Devuelve los datos del tipo que es el filtro en la variable $tipos_seleccionado
    $sql = "SELECT id, tipo, categoria_id FROM tipos WHERE id = :id_tipo";
    $seleccionar_tipo = $base_de_datos->prepare($sql);
    $seleccionar_tipo->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);
    $seleccionar_tipo->execute();
    $tipos_seleccionado = $seleccionar_tipo->fetch( PDO::FETCH_ASSOC );
?>