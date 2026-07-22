<?php 
if( isset( $_POST['tipo'] ) ){

    include("./../config/conexion.php");

    /*------- RECOGE TIPO, CATEGORIA Y CLASE -------*/
    $tipo = htmlspecialchars($_POST['tipo']);
    $categoria = htmlspecialchars($_POST['categoria']);
    $clase = htmlspecialchars($_POST['clase']);
    $tabla = "espec_$clase";

    $join_adicional = ""; 

    // Configuración de JOINS dinámicos para roscas
    if ($clase == 'sellado') {
        $join_adicional = " LEFT JOIN roscas ON roscas.id = $tabla.id_rosca ";
    } else if ($clase == 'combustiblelinea') {
    $join_adicional = " LEFT JOIN roscas as re ON re.id = $tabla.id_rosca_entrada 
                        LEFT JOIN roscas as rs ON rs.id = $tabla.id_rosca_salida  
                        LEFT JOIN pulgadas as ps ON ps.id = $tabla.id_pulgada_salida
                        LEFT JOIN pulgadas as pe ON pe.id = $tabla.id_pulgada_entrada";
             
}

    // Definición de columnas según la clase
    switch($tabla){
        case 'espec_aireautomotriz':
        case 'espec_aireindustrial':
            $columnas = ["$tabla.codigo", 'diametroext1', 'diametroext2', 'diametroint1', 'diametroint2', 'altura'];
            break;
        case 'espec_combustiblelinea':
            $columnas = [
                "$tabla.codigo", "$tabla.diametroext", "$tabla.altura", 
                "$tabla.entrada", "$tabla.salida",
                "re.codigo as rosca_entrada_nom", "rs.codigo as rosca_salida_nom",
                "re.valor_nominal as v_nom_e", "rs.valor_nominal as v_nom_s",
                "pe.codigo as pulgada_entrada_nom", "ps.codigo as pulgada_salida_nom",
                "pe.valor_nominal as v_nom_ep", "ps.valor_nominal as v_nom_sp"
            ];
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
            $columnas = [
                "$tabla.codigo", "roscas.codigo as rosca_nombre", "$tabla.diametroint", 
                "$tabla.diametroext", "$tabla.altura", "diametroempext", 
                "diametroempint", "espesoremp", "valvulaal", "valvulaad", "roscas.valor_nominal"
            ];
            break;
    }

    /*--- CONFIGURACIÓN DE ORDENAMIENTO ---*/
    $orderType = isset( $_POST['orderType'] ) ? htmlspecialchars($_POST['orderType']) : 'ASC';
    $orderCol = isset( $_POST['orderCol'] ) ? htmlspecialchars($_POST['orderCol']) : 0;
    
    $campoOrdenRaw = isset($columnas[intval($orderCol)]) ? $columnas[intval($orderCol)] : $columnas[0];
    // Limpiar alias para el ORDER BY
    $campoOrden = (strpos($campoOrdenRaw, ' as ') !== false) ? explode(' as ', $campoOrdenRaw)[0] : $campoOrdenRaw;

    // Ajustes de orden lógico para valores nominales
    if ($clase == 'sellado' && intval($orderCol) == 1) {
        $campoOrden = "roscas.valor_nominal";
    }  else if ($clase == 'combustiblelinea' && intval($orderCol) == 3) {
    // Ordena priorizando el valor nominal de la rosca, si no hay, el de la pulgada
    $campoOrden = "COALESCE(re.valor_nominal, pe.valor_nominal)";
} else if ($clase == 'combustiblelinea' && intval($orderCol) == 4) {
    // Si tienes otra columna de orden para salida (ajusta el índice 4 según tu tabla)
    $campoOrden = "COALESCE(rs.valor_nominal, ps.valor_nominal)";
}

    $sOrder = "ORDER BY " . $campoOrden . ' ' . $orderType;

    /*--- PAGINACIÓN Y FILTRADO ---*/
    $page = ( isset( $_POST['pagina'] ) && $_POST['pagina'] >= 1) ? $_POST['pagina'] : 1;
    $limit = isset( $_POST['registros'] ) ? $_POST['registros'] : 10;
    $inicio = $limit * ($page - 1);
    
    $campo = ( isset( $_POST['campo'] ) && $_POST['campo'] != '' ) ? $_POST['campo'] : null;
    $params = [':tipo' => $tipo, ':categoria' => $categoria];

    if($campo != null){
        $campo_busqueda = '%' . $campo . '%';
        $params[':campo'] = $campo_busqueda;
        
        $where = " WHERE ( t.tipo = :tipo ) and ( cat.categoria = :categoria ) and ( $tabla.deleted_at is null ) and ( ";
        foreach($columnas as $col){
            $limpia = (strpos($col, ' as ') !== false) ? explode(' as ', $col)[0] : $col;
            $where .= "$limpia LIKE :campo OR ";
        }
        $where = substr($where, 0, -4) . ") and (f_c.deleted_at is null) and ( t.deleted_at is null ) ";
    } else {
        $where = " WHERE ( f_c.deleted_at is null ) and ( t.deleted_at is null ) and ( t.tipo = :tipo ) and ( cat.categoria = :categoria )";
    }

    $sql_join_base = " FROM tipos as t
                       JOIN filtro_codificacion as f_c ON f_c.id_tipo = t.id
                       JOIN $tabla ON f_c.id = $tabla.id_codigo
                       $join_adicional
                       JOIN categorias as cat ON cat.id = t.categoria_id ";

    // 1. Conteo Total
    $stmt_tot = $base_de_datos->prepare("SELECT COUNT(*) as total $sql_join_base WHERE ( f_c.deleted_at is null ) and ( t.deleted_at is null ) and ( cat.categoria = :categoria ) and ( t.tipo = :tipo )");
    $stmt_tot->execute([':tipo' => $tipo, ':categoria' => $categoria]);
    $filas_totales = $stmt_tot->fetch(PDO::FETCH_ASSOC)['total'];

    // 2. Conteo Filtrado
    $stmt_fil = $base_de_datos->prepare("SELECT COUNT(*) as filtrado $sql_join_base $where");
    $stmt_fil->execute($params);
    $filas_filtradas = $stmt_fil->fetch(PDO::FETCH_ASSOC)['filtrado'];

    // 3. Obtención de Datos
    $stmt_dat = $base_de_datos->prepare("SELECT " . implode(",", $columnas) . " $sql_join_base $where $sOrder LIMIT $inicio, $limit");
    $stmt_dat->execute($params);
    $filtros = $stmt_dat->fetchAll(PDO::FETCH_ASSOC);

    $output = [
        'totalFiltro' => $filas_filtradas, 
        'totalRegistros' => $filas_totales, 
        'clase' => $clase, 
        'data' => '',
        'paginacion' => '',
        'where' => $where
    ];

    if( $filas_filtradas > 0){
        foreach( $filtros as $filtro ){
            $codigo = $filtro['codigo'];
            $output['data'] .= "<tr>";
            $output['data'] .= "<td class='pro'><a href='./../../catalogo/ficha_tecnica/index.php?codigo=$codigo&tip=$tipo' class='a_web'>$codigo</a></td>";

            if( $clase == 'aireautomotriz' || $clase == 'aireindustrial' ){        
                $output['data'] .= "<td class='pro'>".$filtro['diametroext1']." -- ".$filtro['diametroext2']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['diametroint1']." -- ".$filtro['diametroint2']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['altura']."</td>";
            }
            else if( $clase == 'elemento' ){
                $output['data'] .= "<td class='pro'>".$filtro['diametroext1']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['diametroint1']." -- ".$filtro['diametroint2']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['altura']."</td>";
            }
            else if( $clase == 'panel' ){
                $output['data'] .= "<td class='pro'>".$filtro['largo']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['ancho']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['altura']."</td>";
            }
            else if( $clase == 'fluidos' ){
                $output['data'] .= "<td class='pro'>".$filtro['detalle1']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['detalle2']."</td>";
            }
            else if( $clase == 'combustiblelinea' ){
                $ent = !empty($filtro['rosca_entrada_nom']) ? $filtro['rosca_entrada_nom'] : 
           (!empty($filtro['pulgada_entrada_nom']) ? $filtro['pulgada_entrada_nom']: $filtro['entrada'] . " mm");
    
    // Lógica para Salida
    $sal = !empty($filtro['rosca_salida_nom']) ? $filtro['rosca_salida_nom'] : 
           (!empty($filtro['pulgada_salida_nom']) ? $filtro['pulgada_salida_nom'] : $filtro['salida'] . " mm");
                $output['data'] .= "<td class='pro'>".$filtro['diametroext']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['altura']."</td>";
                $output['data'] .= "<td class='pro'>Ent: $ent<br>Sal: $sal</td>";
            }
            else if( $clase == 'sellado' ){
                $output['data'] .= "<td class='pro'>".(!empty($filtro['rosca_nombre']) ? $filtro['rosca_nombre'] : " N/A")."</td>";
                $output['data'] .= "<td class='pro'>".(!empty($filtro['diametroint']) ? $filtro['diametroint'] : "N/A")."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['diametroext']."</td>";
                $output['data'] .= "<td class='pro'>".$filtro['altura']."</td>";
                $output['data'] .= "<td class='pro'>ø ext: ".$filtro['diametroempext']."<br>ø int: ".$filtro['diametroempint']."<br>Esp: ".$filtro['espesoremp']."</td>";
                $output['data'] .= "<td class='pro'>Alivio: ".($filtro['valvulaal']==1?'SI':'NO')."<br>Anti: ".($filtro['valvulaad']==1?'SI':'NO')."</td>";
            }
            $output['data'] .= "</tr>";
        }

        // --- GENERACIÓN DE PAGINACIÓN HTML ---
        $totalPaginas = ceil($filas_filtradas / $limit);
        
        if($page != 1){
            $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro(1)' style='cursor: pointer;'><<</a>"; 
        }
        if($page > 1){
            $prevPage = $page - 1;
            $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro($prevPage)' style='cursor: pointer;'><</a>"; 
        }

        $output['paginacion'] .= "<p class='linksp'>$page</p>";

        if($page < $totalPaginas){
            $nextPage = $page + 1;
            $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro($nextPage)' style='cursor: pointer;'> > </a>";
        }
        if($page != $totalPaginas){
            $output['paginacion'] .= "<a onclick='cambiarPaginaFiltro($totalPaginas)' style='cursor: pointer;'>>></a>"; 
        }

    } else {
        $output['data'] .= "<tr><td colspan='10' style='text-align:center'>Sin Resultados</td></tr>";
    }

    echo json_encode($output);
}
?>