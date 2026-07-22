<?php 

if( isset($_POST['especificacion']) ){
    include("./../config/conexion.php");
    include('./../funciones/codigo_existe_en_especificaciones.php');

    $output = $_POST;
    $especificacion = $_POST['especificacion'];
    $tipo = isset( $_POST['tipo'] ) ? $_POST['tipo'] : null;
    $rosca = isset( $_POST['rosca'] ) ? $_POST['rosca'] : null;

    $tabla = 'espec_' . $especificacion; 

    $limit = isset( $_POST['registros'] ) ? $_POST['registros'] : 10;
    $page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
    $campo_ordenar = isset( $_POST['campo_ordenar'] ) ? htmlspecialchars($_POST['campo_ordenar']) : 'filtro_codificacion.codigo';
    $manera_orden = isset( $_POST['manera_orden'] ) ? htmlspecialchars($_POST['manera_orden']) : 'ASC';

    $campo_ordenar2 = isset( $_POST['campo_ordenar2'] ) ? htmlspecialchars($_POST['campo_ordenar2']) : null;
    $manera_orden2 = isset( $_POST['manera_orden2'] ) ? htmlspecialchars($_POST['manera_orden2']) : null;
    $segundo_orden = ( $campo_ordenar2 != '' && $manera_orden2  != '') ? ", $campo_ordenar2 $manera_orden2" : '';

    // En la sección donde verificas los campos de ordenamiento:
if ($campo_ordenar == 'espec_combustiblelinea.entrada') {
    // Si la entrada es por rosca usa rosca, si es por pulgada usa pulgada
    $campo_ordenar = "COALESCE(R_ENT.valor_nominal, P_ENT.valor_nominal, espec_combustiblelinea.entrada)";
}
if ($campo_ordenar == 'espec_combustiblelinea.salida') {
    $campo_ordenar = "COALESCE(R_SAL.valor_nominal, P_SAL.valor_nominal, espec_combustiblelinea.salida)";
}
if ($campo_ordenar == 'espec_sellado.diametroint') {
    $campo_ordenar = "COALESCE(R_INT.valor_nominal, espec_sellado.diametroint)";
}

    $inicio = $limit * ($page - 1);
    $campo = ( isset( $_POST['campo'] ) && $_POST['campo'] != '' ) ? htmlspecialchars($_POST['campo']) : null;
    $campo_busqueda = '%' . $campo . '%';
    $sLimit = "LIMIT $inicio, $limit";

    switch ($tabla) {
        case 'espec_aireautomotriz':
            $columnas = ['espec_aireautomotriz.codigo', 'espec_aireautomotriz.diametroext1', 'espec_aireautomotriz.diametroext2', 'espec_aireautomotriz.diametroint1', 'espec_aireautomotriz.diametroint2', 'espec_aireautomotriz.altura'];
            break;
        case 'espec_aireindustrial':
            $columnas = ['espec_aireindustrial.codigo', 'espec_aireindustrial.diametroext1', 'espec_aireindustrial.diametroext2', 'espec_aireindustrial.diametroint1', 'espec_aireindustrial.diametroint2', 'espec_aireindustrial.altura'];
            break;
        case 'espec_combustiblelinea':
            $columnas = ['espec_combustiblelinea.codigo', 'espec_combustiblelinea.diametroext', 'espec_combustiblelinea.entrada', 'espec_combustiblelinea.salida', 'espec_combustiblelinea.altura','R_ENT.codigo AS rosca_ent_cod', 'R_SAL.codigo AS rosca_sal_cod', 'R_ENT.valor_nominal AS nominal_ent', 'R_SAL.valor_nominal AS nominal_sal', 'P_ENT.codigo AS pulgada_ent_cod', 'P_SAL.codigo AS pulgada_sal_cod', 'P_ENT.valor_nominal AS nominal_ent_p', 'P_SAL.valor_nominal AS nominal_sal_p'];
            break;
        case 'espec_elemento':
            $columnas = ['espec_elemento.codigo', 'espec_elemento.diametroext1', 'espec_elemento.diametroint1', 'espec_elemento.diametroint2', 'espec_elemento.altura'];
            break;
        case 'espec_fluidos':
            $columnas = ['espec_fluidos.codigo', 'espec_fluidos.detalle1', 'espec_fluidos.detalle2'];
            break;
        case 'espec_panel':
            $columnas = ['espec_panel.codigo', 'espec_panel.largo', 'espec_panel.ancho', 'espec_panel.altura'];
            break;
        case 'espec_sellado':
            $columnas = ['espec_sellado.codigo', 'espec_sellado.tipo', 'espec_sellado.diametroint', 'espec_sellado.diametroext', 'espec_sellado.altura', 'espec_sellado.diametroempext', 'espec_sellado.diametroempint', 'espec_sellado.espesoremp', 'espec_sellado.valvulaal', 'espec_sellado.valvulaad', 'R_INT.codigo AS rosca_int_cod', 'R_INT.valor_nominal AS nominal_int'];
            break;
    }

    if( $campo != null ){
        $where = "WHERE (";
        $contador = count($columnas);
        for($i = 0; $i < $contador; $i++){
            $where .= $columnas[$i] . " " . "LIKE :campo OR";
            $where .= " ";
        }
        $where = substr_replace($where, "", -3);
        $where .= ")  and ( filtro_codificacion.clase = :especificacion ) and ( filtro_codificacion.deleted_at is null ) and ( $tabla.deleted_at is null )";
    }
    else {
        $where = "WHERE ( filtro_codificacion.clase = :especificacion ) and ( $tabla.deleted_at is null ) and ( filtro_codificacion.deleted_at is null )";
    }  
    
    $where_tipo = ( $tipo != null ) ? "and ( $tabla.tipo = :tipo )" : "";
$where_diametroint = "";
if ($rosca != null) {
    if ($tabla == 'espec_sellado') {
        if ($rosca === 'null') {
            // Esto es vital: si es el string 'null', buscamos valores nulos en la DB
            $where_diametroint = " AND ( $tabla.id_rosca IS NULL )";
        } else {
            $where_diametroint = " AND ( $tabla.id_rosca = :rosca )";
        }
    } else {
        $where_diametroint = " AND ( $tabla.diametroint = :rosca )";
    }
}
    
    $sql = "SELECT COUNT(*) as total FROM filtro_codificacion
                    JOIN $tabla ON $tabla.id_codigo = filtro_codificacion.id
                    WHERE ( filtro_codificacion.clase = :especificacion ) and ( filtro_codificacion.deleted_at is null ) and ( $tabla.deleted_at is null ) $where_tipo $where_diametroint";             
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->bindParam(':especificacion', $especificacion, PDO::PARAM_STR);
    if( $tipo != null ){
        $busqueda->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    }
    if ($rosca != null && $rosca !== 'null') {
    $busqueda->bindParam(':rosca', $rosca, PDO::PARAM_STR);
}
    $busqueda->setFetchMode(PDO::FETCH_ASSOC);
    $busqueda->execute();
    $busqueda2 = $busqueda->fetch();
    $filas_totales = $busqueda2['total'];

    $sql = "SELECT COUNT(*) as filtradas FROM filtro_codificacion
                    JOIN $tabla ON $tabla.id_codigo = filtro_codificacion.id
                    $where $where_tipo $where_diametroint";
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->bindParam(':especificacion', $especificacion, PDO::PARAM_STR);
    if( $campo != null ){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    if( $tipo != null ){
        $busqueda->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    }
    if ($rosca != null && $rosca !== 'null') {
    $busqueda->bindParam(':rosca', $rosca, PDO::PARAM_STR);
}
    $busqueda->setFetchMode(PDO::FETCH_ASSOC);
    $busqueda->execute();
    $busqueda2 = $busqueda->fetch();
    $filas_filtradas = $busqueda2['filtradas'];

    $output = [];
    $output['totalRegistros'] = $filas_totales;
    $output['totalFiltro'] = $filas_filtradas;
    $output['data'] = '';
    

    $extra_joins = "";
    if ($tabla == 'espec_combustiblelinea') {
    $extra_joins = " LEFT JOIN roscas AS R_ENT ON espec_combustiblelinea.id_rosca_entrada = R_ENT.id 
                     LEFT JOIN roscas AS R_SAL ON espec_combustiblelinea.id_rosca_salida = R_SAL.id
                     LEFT JOIN pulgadas AS P_ENT ON espec_combustiblelinea.id_pulgada_entrada = P_ENT.id 
                     LEFT JOIN pulgadas AS P_SAL ON espec_combustiblelinea.id_pulgada_salida = P_SAL.id ";
}
     elseif ($tabla == 'espec_sellado') {
        $extra_joins = " LEFT JOIN roscas AS R_INT ON espec_sellado.id_rosca = R_INT.id "; 
    }

    $sql = "SELECT " . implode(",", $columnas) . " FROM filtro_codificacion
        JOIN $tabla ON $tabla.id_codigo = filtro_codificacion.id
        $extra_joins
        $where $where_tipo $where_diametroint
        ORDER BY $campo_ordenar $manera_orden $segundo_orden
        $sLimit";

    $output['sql'] = $sql;

    $busqueda = $base_de_datos->prepare($sql);
    $busqueda->bindParam(':especificacion', $especificacion, PDO::PARAM_STR);
    if( $campo != null){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    if( $tipo != null ){
        $busqueda->bindParam(':tipo', $tipo, PDO::PARAM_STR );
    }
    if ($rosca != null && $rosca !== 'null') {
    $busqueda->bindParam(':rosca', $rosca, PDO::PARAM_STR);
}
    $busqueda->setFetchMode(PDO::FETCH_ASSOC);
    $busqueda->execute();

    switch ($tabla) {
        case 'espec_aireautomotriz':
            while( $row = $busqueda->fetch() ){
                $codigo = $row['codigo'];
                $diametroext1 = $row['diametroext1'];
                $diametroext2 = $row['diametroext2'];
                $diametroint1 = $row['diametroint1'];
                $diametroint2 = $row['diametroint2'];
                $altura = $row['altura'];
        
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=aireautomotriz&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroext1 -- $diametroext2</td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroint1 -- $diametroint2</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "</tr>";
            }
            break;
        case 'espec_aireindustrial':
            while( $row = $busqueda-> fetch() ){
                $codigo = $row['codigo'];
                $diametroext1 = $row['diametroext1'];
                $diametroext2 = $row['diametroext2'];
                $diametroint1 = $row['diametroint1'];
                $diametroint2 = $row['diametroint2'];
                $altura = $row['altura'];
        
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=aireindustrial&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroext1 -- $diametroext2</td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroint1 -- $diametroint2</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "</tr>";
            }
            break;
        case 'espec_combustiblelinea':
           while( $row = $busqueda-> fetch() ){
        // Si hay código de rosca lo usamos, sino el campo texto
       $mostrar_entrada = $row['rosca_ent_cod'] ?: ($row['pulgada_ent_cod'] ?: $row['entrada'] . " mm");
        $mostrar_salida = $row['rosca_sal_cod'] ?: ($row['pulgada_sal_cod'] ? : $row['salida'] . " mm");
        
        $output['data'] .= "<tr>";
        $output['data'] .= "<td class='busqueda_apli'><a href='...'>".$row['codigo']."</a></td>";
        $output['data'] .= "<td class='busqueda_apli'>".$row['diametroext']."</td>";
        $output['data'] .= "<td class='busqueda_apli'>".$row['altura']."</td>";
        $output['data'] .= "<td class='busqueda_apli'>Entrada: $mostrar_entrada <br /> Salida: $mostrar_salida</td>";
        $output['data'] .= "</tr>";
    }
            break;
        case 'espec_elemento':
            while( $row = $busqueda-> fetch() ){
                $codigo = $row['codigo'];
                $diametroext = $row['diametroext1'];
                $diametroint1 = $row['diametroint1'];
                $diametroint2 = $row['diametroint2'];
                $altura = $row['altura'];
    
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=elemento&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroext</td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroint1 -- $diametroint2</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "</tr>";
            }
            break;
        case 'espec_panel':
            while( $row = $busqueda-> fetch() ){
                $codigo = $row['codigo'];
                $largo = $row['largo'];
                $ancho = $row['ancho'];
                $altura = $row['altura'];
    
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=panel&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$largo</td>";
                $output['data'] .= "<td class='busqueda_apli'>$ancho</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "</tr>";
            }
            break;
        case 'espec_sellado':
    while( $row = $busqueda->fetch() ){
        $codigo = htmlspecialchars($row['codigo']);
        $tipo = $row['tipo'];
        $altura = $row['altura'];
        $diametroext = $row['diametroext'];
        $diametroempext = $row['diametroempext'];
        $diametroempint = $row['diametroempint'];
        $espesoremp = $row['espesoremp'];
        $valvulaal = $row['valvulaal'];
        $valvulaad = $row['valvulaad'];

        // LÓGICA DE VISUALIZACIÓN DINÁMICA
        if (!empty($row['rosca_int_cod'])) {
            // Si tiene rosca asignada
            $identificador_medida = "Rosca: " . $row['rosca_int_cod'];
        } else {
            // Si es "Sin Rosca", mostramos el diámetro interno manual
            $identificador_medida = "ø int: " . $row['diametroint'] . " mm";
        }

        $output['data'] .= "<tr>";
        $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=sellado&esp=1' style='cursor: pointer'>$codigo</a></td>";
        
        // Aquí mostramos el Tipo y la medida (Rosca o ø int)
        $output['data'] .= "<td class='busqueda_apli'>$tipo <br /> <small class='text-muted'>$identificador_medida</small></td>";
        
        $output['data'] .= "<td class='busqueda_apli'>$diametroext</td>";
        $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
        $output['data'] .= "<td class='busqueda_apli'>ext: $diametroempext <br /> int: $diametroempint <br /> Esp: $espesoremp</td>";
        
        // Columna Válvulas
        $alivio = ($valvulaal == 0) ? "NO" : "Sí ";
        $drain = ($valvulaad == 0) ? "NO" : "Sí ";
        $output['data'] .= "<td class='busqueda_apli'>Alivio: $alivio <br /> Anti Drain: $drain</td>";
        
        $output['data'] .= "</tr>";
    }
    break;
            case 'espec_fluidos':
                while( $row = $busqueda->fetch() ){
                    $codigo = $row['codigo'];
                    $detalle1 = $row['detalle1'];
                    $detalle2 = $row['detalle2'];
        
                    $output['data'] .= "<tr>";
                    $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=fluidos&esp=1' style='cursor: pointer'>$codigo </a></td>";
                    $output['data'] .= "<td class='busqueda_apli'>$detalle1 </td>";
                    $output['data'] .= "<td class='busqueda_apli'>$detalle2</td>";
                    $output['data'] .= "</tr>";
                }
                break;
        }
        

        $output['paginacion'] = "";

        $numeroInicio = 1;
        if($output['totalFiltro'] > 0){
            $totalPaginas = ceil($output['totalFiltro'] / $limit);

            if(($page - 4) > 1){
                $numeroInicio = $page - 3;
            }
                
            $numeroFinal = $numeroInicio + 7;
                
            if($numeroFinal > $totalPaginas){
                $numeroFinal = $totalPaginas;
            }

            $output['paginacion'] .= "";
            if($page != 1){
                $anterior = $page - 1;
                $output['paginacion'] .= "<a onclick='getTabla(1)'  style='cursor: pointer;'>  <<  </a>"; 
                $output['paginacion'] .= "<a onclick='getTabla($anterior)'  style='cursor: pointer;'>  <  </a>"; 
            }

            for($i = $numeroInicio; $i <= $numeroFinal; $i++){
                if($page == $i){
                    $output['paginacion'] .= "<p class='linksp'>" . $i ." </p>";
                }
            }
            if($page != $totalPaginas){
                $siguiente = $page  + 1;
                $output['paginacion'] .= "<a onclick='getTabla($siguiente)'  style='cursor: pointer;'>  >  </a>"; 
                $output['paginacion'] .= "<a onclick='getTabla($totalPaginas)'  style='cursor: pointer;'> >> </a>"; 
            }
        }

        $output['especificacion'] = $especificacion;

        echo json_encode($output);
    }
?>