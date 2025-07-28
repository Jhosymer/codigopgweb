<?php 
    if( isset( $_POST['tipo'] ) ){
        include("./../../conexion.php");

        /*-------RECOGE TIPO Y CATEGORIA---------------*/
        $tipo = htmlspecialchars($_POST['tipo']);
        $categoria = htmlspecialchars($_POST['categoria']);
        $clase = htmlspecialchars($_POST['clase']);
        $tabla = "espec_$clase";

        switch($tabla){
            case 'espec_aireautomotriz':
                $columnas = ["$tabla.codigo", 'diametroext1', 'diametroext2', 'diametroint1', 'diametroint2', 'altura'];
                break;
            case 'espec_aireindustrial':
                $columnas = ["$tabla.codigo", 'diametroext1', 'diametroext2', 'diametroint1', 'diametroint2', 'altura'];
                break;
            case 'espec_combustiblelinea':
                $columnas = ["$tabla.codigo", 'diametroext', 'altura', 'entrada', 'salida'];
                break;
            case 'espec_panel':
                $columnas = ["$tabla.codigo", 'largo', 'ancho', 'altura'];
                break;
            case 'espec_elemento':
                $columnas = ["$tabla.codigo", 'diametroext1', 'diametroint1', 'diametroint2', 'altura'];
                break;
            case 'espec_fluidos':
                $columnas = ["$tabla.codigo", 'detalle1', 'detalle2'];
                break;
            case 'espec_sellado':
                $columnas = ["$tabla.codigo", 'diametroint', 'diametroext', 'altura', 'diametroempext', 'diametroempint', 'espesoremp', 'valvulaal', 'valvulaad'];
                break;
        }

        /*-----------RECOJO CAMPO------------*/
        $campo = ( isset( $_POST['campo'] ) && $_POST['campo'] != '' ) ? $_POST['campo'] : null;
        if( $campo != null ){
            $campo_busqueda = '%' . $campo . '%';
        }

        /*SE RECOJE LAS VARIABLES PARA ORDENAR COLUMNAS*/ 
        $orderType = isset( $_POST['orderType'] ) ? htmlspecialchars($_POST['orderType']) : 'tipo';
        $orderCol = isset( $_POST['orderCol'] ) ? htmlspecialchars($_POST['orderCol']) : 'ASC';
        $sOrder = "ORDER BY " . $columnas[intval($orderCol)] . ' ' . $orderType;

        /*------------SE RECOGE LA PAGINA Y EL NUMERO DE REGISTROS--------*/ 
        $page = ( isset( $_POST['pagina'] ) && $_POST['pagina'] >= 0) ? $_POST['pagina'] : 1;
        $limit = isset( $_POST['registros'] ) ? $_POST['registros'] : 10;
        $inicio = $limit * ($page - 1);
        $sLimit = " LIMIT $inicio, $limit";

        $where = '';

        if($campo != null){
            $where = " WHERE ( t.tipo = :tipo ) and ( cat.categoria = :categoria ) and ( $tabla.deleted_at is null ) and ( ";
            $contador = count($columnas);
            for($i = 0; $i < $contador; $i++){
                $where .= $columnas[$i] . " " . "LIKE :campo OR";
                $where .= " ";
            }
            $where = substr_replace($where, "", -3);
            $where .= ") and (f_c.deleted_at is null) and ( t.deleted_at is null ) ";
        }
        else{
            $where .= "WHERE ( f_c.deleted_at is null ) and ( t.deleted_at is null ) and ( t.tipo = :tipo ) and ( cat.categoria = :categoria )";
        }

        $sql = "SELECT COUNT(*) as total FROM tipos as t
                        JOIN filtro_codificacion as f_c ON f_c.id_tipo = t.id
                        JOIN $tabla ON f_c.id = $tabla.id_codigo
                        JOIN categorias as cat ON cat.id = t.categoria_id
                        WHERE ( f_c.deleted_at is null ) and ( t.deleted_at is null ) and ( cat.categoria = :categoria ) and ( t.tipo = :tipo )";
        $tipos_selec = $base_de_datos->prepare($sql);
        $tipos_selec->bindParam(':tipo', $tipo, PDO::PARAM_STR );
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        $filas_totales = $tipos_selec->fetch();
        $filas_totales = $filas_totales['total'];

        $sql = "SELECT COUNT(*) as filtrado FROM tipos as t
                    JOIN filtro_codificacion as f_c ON f_c.id_tipo = t.id
                    JOIN $tabla ON f_c.id = $tabla.id_codigo
                    JOIN categorias as cat ON cat.id = t.categoria_id
                    $where";
        $tipos_selec = $base_de_datos->prepare($sql);
        if( $_POST['campo'] != null ){
            $tipos_selec->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR );
        }
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->bindParam(':tipo', $tipo, PDO::PARAM_STR );
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        $filas_filtradas = $tipos_selec->fetch();
        $filas_filtradas = $filas_filtradas['filtrado'];

        $sql = "SELECT ". implode(",", $columnas) . " FROM tipos as t
                            JOIN filtro_codificacion as f_c ON f_c.id_tipo = t.id
                            JOIN $tabla ON f_c.id = $tabla.id_codigo
                            JOIN categorias as cat ON cat.id = t.categoria_id
                            $where
                            $sOrder
                            $sLimit";
        $tipos_selec = $base_de_datos->prepare($sql);  
        if( $_POST['campo'] != null ){
            $tipos_selec->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR );
        }
        $tipos_selec->bindParam(':tipo', $tipo, PDO::PARAM_STR );
        $tipos_selec->bindParam(':categoria', $categoria, PDO::PARAM_STR );
        $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
        $tipos_selec->execute();
        while( $fila = $tipos_selec->fetch() ){
            $filtros []= $fila;
        }

        $output = [];
        $output['totalFiltro'] = $filas_filtradas;
        $output['totalRegistros'] = $filas_totales;
        $output['clase'] = $clase;
        $output['data'] = '';

        if( $filas_filtradas > 0){
            foreach( $filtros as $filtro ){
                $codigo = $filtro['codigo'];

                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='pro'>";
                $output['data'] .= "<a href='./../filtro/filtro.php?codigo=$codigo&tip=$tipo' >$codigo</a>";
                $output['data'] .= "</td>";
                if( $clase == 'aireautomotriz' ){        
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroext1'] . " -- " . $filtro['diametroext2'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroint1'] . " -- " . $filtro['diametroint2'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'aireindustrial' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroext1'] . " -- " . $filtro['diametroext2'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroint1'] . " -- " . $filtro['diametroint2'];;
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'combustiblelinea' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroext'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['entrada'] . "<br />" . $filtro['salida'] ;
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'elemento' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroext1'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroint1'] . " -- " . $filtro['diametroint2'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'fluidos' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['detalle1'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['detalle2'];
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'panel' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['largo'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['ancho'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                }
                else if( $clase == 'sellado' ){
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroint'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['diametroext'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= $filtro['altura'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= "ø ext: " . $filtro['diametroempext'] . "<br />" . "ø int: " . $filtro['diametroempint'] . "<br />" . "Espesor: " . $filtro['espesoremp'];
                    $output['data'] .= "</td>";
                    $output['data'] .= "<td class='pro'>";
                    $output['data'] .= "Alivio: ";
                    if ( $filtro['valvulaal'] == 0 ) {
                        $output['data'] .= 'NO';
                    }
                    else if ( $filtro['valvulaal'] == 1 ) {
                        $output['data'] .= 'SI';
                    }
                    $output['data'] .= "<br />";
                    $output['data'] .= "Anti drain: ";
                    if ( $filtro['valvulaad'] == 0 ) {
                        $output['data'] .= 'NO';
                    }
                    else if ( $filtro['valvulaad'] == 1 ) {
                        $output['data'] .= 'SI';
                    }
                    $output['data'] .= "</td>";
                }
                $output['data'] .= "</tr>";
            }
        }
        else {
            $output['data'] .= "<tr> Sin Resultados </td>";
        }

        $output['paginacion'] = "";
        $output['where'] = $where;

        $numeroInicio = 1;
        if($output['totalFiltro'] > 0){
            $totalPaginas = ceil($output['totalFiltro'] / $limit);

            if(($page - 4) > 1){
                $numeroInicio = $page - 3;
            }
            
            $numeroFinal = $numeroInicio + 7;
            
            $numeroFinal = $numeroInicio + 7;
            
            if($numeroFinal > $totalPaginas){
                $numeroFinal = $totalPaginas;
            }

            $output['paginacion'] .= "";
            if($page != 1){
                $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro(1)'  style='cursor: pointer;'>Primero</a>"; 
            }
            for($i = $numeroInicio; $i <= $numeroFinal; $i++){
                if($page == $i){
                $output['paginacion'] .= "<p>" . $i ." </p>";
                }
                else{
                    $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro($i)'  style='cursor: pointer;'>".$i."</a>";
                }
            }
            if($page != $totalPaginas){
                $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro($totalPaginas)'  style='cursor: pointer;'>Último</a>"; 
            }
        }

        echo json_encode($output);
    }

?>