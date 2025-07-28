<?php 
    if( $tipos_seleccionado == true ){
        //Se busca la categoria a la que pertenece el tipo
        $sql = "SELECT categorias.id, categorias.categoria, productos.nombre FROM categorias 
                                JOIN productos ON categorias.producto_id = productos.id
                                WHERE ( categorias.id = :categoria_id ) and ( categorias.deleted_at is null )";
        $selec_categoria = $base_de_datos->prepare($sql);
        $selec_categoria->bindParam(':categoria_id', $tipos_seleccionado['categoria_id'], PDO::PARAM_INT);
        $selec_categoria->execute();
        $categoria_seleccionada = $selec_categoria->fetch( PDO::FETCH_ASSOC );

        //Todos los tipos que derivan de la categoria 
        $sql = "SELECT * FROM tipos WHERE ( categoria_id = :categoria_id and deleted_at is null )";
        $seleccionar_tipo = $base_de_datos->prepare($sql);
        $seleccionar_tipo->bindParam(':categoria_id', $tipos_seleccionado['categoria_id'], PDO::PARAM_INT);
        $seleccionar_tipo->execute();
        while( $fila = $seleccionar_tipo->fetch( PDO::FETCH_ASSOC ) ){
            $tipos []= $fila;
        }
    }
    else {
        $categoria_seleccionada = false;
    }