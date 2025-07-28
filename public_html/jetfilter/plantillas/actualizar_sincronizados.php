<?php 
    //Actualizar Sincronizado de todas las aplicaciones
    $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
    $aplicacion_update = $base_de_datos->prepare($sql);
    $aplicacion_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $aplicacion_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
    $aplicacion_update->execute();

    //Actualizar Sincronizado de todas las equivalencias
    $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
    $equivalencia_update = $base_de_datos->prepare($sql);
    $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
    $equivalencia_update->execute();

    //Actualizar Sincronizado de filtro codificacion
    $sql = "UPDATE filtro_codificacion SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
    $filtro_update = $base_de_datos->prepare($sql);
    $filtro_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $filtro_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
    $filtro_update->execute();

    //Actualizar Sincronizado de Especeficaciones
    $sql = "UPDATE $tabla SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(":sincronizado", $sincronizado, PDO::PARAM_STR);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $seleccionado->execute();