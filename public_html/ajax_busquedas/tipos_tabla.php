<?php 

    if( $_POST['categoria'] ){
        include("./../config/conexion.php");

        //Recoges los valores a recibir
        $categoria = $_POST['categoria'];
        $producto_id = $_POST['producto_id'];
        $campo = isset( $_POST['campo'] ) ? $_POST['campo'] : null;
        if( $campo != null ){
            $campo_busqueda = '%' . $campo . '%';
        }

        //Se seleccionan las columnas
        $columnas = ['tipo', 'cantidad'];

        //Se recogen los valores de orden
        $orderType = isset( $_POST['orderType'] ) ? htmlspecialchars($_POST['orderType']) : 'tipo';
        $orderCol = isset( $_POST['orderCol'] ) ? htmlspecialchars($_POST['orderCol']) : 'ASC';
        $sOrder = "ORDER BY " . $columnas[intval($orderCol)] . ' ' . $orderType;
        
        //Se selecciona el numero de pagina y de registros
        $page = ( isset( $_POST['pagina'] ) && $_POST['pagina'] >= 0) ? $_POST['pagina'] : 1;
        $limit = isset( $_POST['registros'] ) ? $_POST['registros'] : 10;
        $inicio = $limit * ($page - 1);
        $sLimit = " LIMIT $inicio, $limit";

        //Inicializa el Where
        $where = '';

        //Si el campo no esta vacio se hace el condicional del campo
        if($campo != null){
            $where = " WHERE ( cat.categoria = :categoria ) and ( cat.producto_id = :producto_id ) and ( ";
            $contador = count($columnas) - 1;
            for($i = 0; $i < $contador; $i++){
                $where .= $columnas[$i] . " " . "LIKE :campo OR";
                $where .= " ";
            }
            $where = substr_replace($where, "", -3);
            $where .= ") and (cat.deleted_at is null) and ( tip.deleted_at is null ) ";
        }
        //Si el campo esta vacio solo se agregan las condicionales normales
        else{
            $where .= "WHERE ( cat.deleted_at is null ) and ( tip.deleted_at is null ) and ( cat.categoria = :categoria ) and ( cat.producto_id = :producto_id )";
        }


        //Se calcula el numero total de filtros con este tipo
        $sql = "SELECT count(*) as cantidad, tip.tipo as tipo FROM filtro_codificacion as f_c
                        JOIN tipos as tip ON tip.id = f_c.id_tipo
                        JOIN categorias as cat ON cat.id = tip.categoria_id
                        WHERE cat.categoria = :categoria and cat.producto_id = :producto_id
                        GROUP BY f_c.id_tipo;";
        $tipos_selec = $base_de_datos->prepare($sql);
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->bindParam(':producto_id', $producto_id, PDO::PARAM_INT );
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        $filas_totales = $tipos_selec->rowCount();

        //Se calcula el numero total de filtros con la restricción del campo de texto
        $sql = "SELECT count(*) as cantidad, tip.tipo as tipo FROM filtro_codificacion as f_c
                    JOIN tipos as tip ON tip.id = f_c.id_tipo
                    JOIN categorias as cat ON cat.id = tip.categoria_id
                    $where
                    GROUP BY f_c.id_tipo";
        $tipos_selec = $base_de_datos->prepare($sql);
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->bindParam(':producto_id', $producto_id, PDO::PARAM_INT );
        if( $campo != null ){
            $tipos_selec->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR );
        }
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        $filas_filtradas = $tipos_selec->rowCount();
 
        //Se recogen los datos de los filtros que cumplen la condicion 
        $sql = "SELECT count(*) as cantidad, tip.tipo, f_c.clase FROM filtro_codificacion as f_c
                    JOIN tipos as tip ON tip.id = f_c.id_tipo
                    JOIN categorias as cat ON cat.id = tip.categoria_id
                    $where
                    GROUP BY f_c.id_tipo, f_c.clase
                    $sOrder
                    $sLimit";
        $tipos_selec = $base_de_datos->prepare($sql);
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->bindParam(':producto_id', $producto_id, PDO::PARAM_INT );
        if( $campo != null ){
            $tipos_selec->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR );
        }
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        while( $fila = $tipos_selec->fetch() ){
            $tipos []= $fila;
        }

        //Se llena la variable output
        $output = [];
        $output['totalFiltro'] = $filas_filtradas;
        $output['totalRegistros'] = $filas_totales;
        $output['data'] = '';

        //Se llena la variable output data
        if( $filas_filtradas > 0){
            foreach( $tipos as $tipo ){
                $tip = $tipo['tipo'];
                $cantidad = $tipo['cantidad'];
                $clase = $tipo['clase'];
                $output['clase'] = $clase;

                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='pro' id='$tip' >";
                $output['data'] .= "<a onclick='pulsarTipo(`$tip`)' style='cursor: pointer;' class='a_web'>$tip</a>";
                $output['data'] .= "</td>";
                $output['data'] .= "<td class='pro'>$cantidad</td>";
                $output['data'] .= "</tr>";
            }
        }
        else {
            $output['data'] .= "<tr> Sin Resultados </td>";
        }

       $output['paginacion'] = "";

if ($output['totalFiltro'] > 0) {
    $totalPaginas = ceil($output['totalFiltro'] / $limit);

    // Enlace para ir a la primera página ('Primero')
    if ($page != 1) {
        $output['paginacion'] .= "<a onclick='cambiarPagina(1)' style='cursor: pointer;'>Primero</a>";
    }

    // Enlace para ir a la página anterior ('<')
    if ($page > 1) {
        $prevPage = $page - 1;
        $output['paginacion'] .= "<a onclick='cambiarPagina($prevPage)' style='cursor: pointer;'><</a>";
    }

    // Muestra el número de la página actual
    $output['paginacion'] .= "<p class='linksp'>" . $page . " </p>";

    // Enlace para ir a la página siguiente ('>')
    if ($page < $totalPaginas) {
        $nextPage = $page + 1;
        $output['paginacion'] .= "<a onclick='cambiarPagina($nextPage)' style='cursor: pointer;'>></a>";
    }

    // Enlace para ir a la última página ('Último')
    if ($page != $totalPaginas) {
        $output['paginacion'] .= "<a onclick='cambiarPagina($totalPaginas)' style='cursor: pointer;'>Último</a>";
    }
}

        echo json_encode( $output );
    }
?>


