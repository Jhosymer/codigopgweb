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
            $columnas = ['espec_combustiblelinea.codigo', 'espec_combustiblelinea.diametroext', 'espec_combustiblelinea.entrada', 'espec_combustiblelinea.salida', 'espec_combustiblelinea.altura'];
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
            $columnas = ['espec_sellado.codigo', 'espec_sellado.tipo', 'espec_sellado.diametroint', 'espec_sellado.diametroext', 'espec_sellado.altura', 'espec_sellado.diametroempext', 'espec_sellado.diametroempint', 'espec_sellado.espesoremp', 'espec_sellado.valvulaal', 'espec_sellado.valvulaad'];
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
    $where_diametroint = ( $rosca != null ) ? "and ( $tabla.diametroint = :rosca )" : "";
    
    $sql = "SELECT COUNT(*) as total FROM filtro_codificacion
                    JOIN $tabla ON $tabla.id_codigo = filtro_codificacion.id
                    WHERE ( filtro_codificacion.clase = :especificacion ) and ( filtro_codificacion.deleted_at is null ) and ( $tabla.deleted_at is null ) $where_tipo $where_diametroint";             
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->bindParam(':especificacion', $especificacion, PDO::PARAM_STR);
    if( $tipo != null ){
        $busqueda->bindParam(':tipo', $tipo, PDO::PARAM_STR);
    }
    if( $rosca != null ){
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
    if( $rosca != null ){
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

    $sql = "SELECT " . implode(",", $columnas) . " FROM filtro_codificacion
                                    JOIN $tabla ON $tabla.id_codigo = filtro_codificacion.id
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
    if( $rosca != null ){
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
                $codigo = $row['codigo'];
                $diametroext = $row['diametroext'];
                $entrada = $row['entrada'];
                $salida = $row['salida'];
                $altura = $row['altura'];
    
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=combustiblelinea&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroext</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "<td class='busqueda_apli'>Entrada: $entrada <br /> Salida: $salida</td>";
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
                $codigo = $row['codigo'];
                $tipo = $row['tipo'];
                $diametroint = $row['diametroint'];
                $diametroext = $row['diametroext'];
                $altura = $row['altura'];
                $diametroempext = $row['diametroempext'];
                $diametroempint = $row['diametroempint'];
                $espesoremp = $row['espesoremp'];
                $valvulaal = $row['valvulaal'];
                $valvulaad = $row['valvulaad'];
    
                $output['data'] .= "<tr>";
                $output['data'] .= "<td class='busqueda_apli'><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=sellado&esp=1' style='cursor: pointer'>$codigo </a></td>";
                $output['data'] .= "<td class='busqueda_apli'>$tipo <br /> $diametroint </td>";
                $output['data'] .= "<td class='busqueda_apli'>$diametroext</td>";
                $output['data'] .= "<td class='busqueda_apli'>$altura</td>";
                $output['data'] .= "<td class='busqueda_apli'>ø ext: $diametroempext <br /> ø int: $diametroempint <br /> Espesor: $espesoremp</td>";
                if( $valvulaal == 0 ){
                    $output['data'] .= "<td class='busqueda_apli'>Alivio: NO <br />";
                }
                else {
                    $output['data'] .= "<td class='busqueda_apli'>Alivio: SI <br />";
                }
                if( $valvulaad == 0 ){
                    $output['data'] .= "Anti Drain: NO <br />";
                }
                else {
                    $output['data'] .= "Anti Drain: SI <br /></td>";
                }
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