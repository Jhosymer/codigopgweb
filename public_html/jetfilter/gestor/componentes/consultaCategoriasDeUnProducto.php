<?php 
    //Funcion que retorna todas las categorias de un producto
    //Primer Parametro el Nombre de la Clase
    //Segundo Parametro la variable de conexion a la base de datos
    function categoriaDeUnProducto($clase, $base_de_datos){
        $sql = "SELECT categorias.id, categoria, productos.nombre FROM categorias 
            JOIN productos ON categorias.producto_id = productos.id
            WHERE ( clase = :clase ) and ( categorias.deleted_at is null )";
        $seleccionar_categoria = $base_de_datos->prepare($sql);
        $seleccionar_categoria->bindParam(':clase', $clase, PDO::PARAM_STR);
        $seleccionar_categoria->setFetchMode( PDO::FETCH_ASSOC );
        $seleccionar_categoria->execute();
        while( $fila = $seleccionar_categoria->fetch() ){
            $categorias []= $fila;
        }
        return $categorias;
    }