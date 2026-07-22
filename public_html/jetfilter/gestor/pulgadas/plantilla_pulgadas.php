<?php 
    /* ARCHIVO PARA CREAR Y LLENAR LA TABLA DE ROSCAS */
    include_once('../../../config/conexion.php');
    
    // 1. Columnas que se van a mostrar (ajustadas a la tabla pulgadas)
    $columnas = ['id', 'codigo', 'valor_nominal'];

    $campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
    $campo_busqueda = '%' . $campo . '%';

    $where = "";
    $output = [];

    $limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
    $page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
    $inicio = $limit * ($page - 1);
    $sLimit = " LIMIT $inicio, $limit";

    if($campo != null){
        $where = " WHERE ( ";
        for($i = 0; $i < count($columnas); $i++){
            $where .= $columnas[$i] . " LIKE :campo OR ";
        }
        $where = substr_replace($where, "", -4); // Quitar el último OR
        $where .= ") AND (deleted_at IS NULL) ";
    } else {
        $where .= "WHERE (deleted_at IS NULL) ";
    }
    
    // 2. Consultas usando la tabla 'pulgadas'
    $sql_total = "SELECT id FROM pulgadas WHERE (deleted_at IS NULL)";
    $busqueda_total = $base_de_datos->prepare($sql_total);
    $busqueda_total->execute();
    $filas_totales = $busqueda_total->rowCount();

    $sql_filtrado = "SELECT id, codigo, valor_nominal FROM pulgadas $where";
    $busqueda_filtro = $base_de_datos->prepare($sql_filtrado);
    if($campo != null) $busqueda_filtro->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    $busqueda_filtro->execute();
    $filas_filtradas = $busqueda_filtro->rowCount();

$sql_datos = "SELECT id, codigo, valor_nominal FROM pulgadas $where ORDER BY valor_nominal ASC $sLimit";
    $busqueda_datos = $base_de_datos->prepare($sql_datos);
    if($campo != null) $busqueda_datos->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    $busqueda_datos->execute();

    $output['data'] = "";
    $output['totalRegistros'] = $filas_totales;
    $output['totalFiltro'] = $filas_filtradas;

    if($filas_filtradas > 0){
        $j = 0;
        while( $row = $busqueda_datos->fetch() ){
            $j++;
            $id = $row['id'];
            
            $output['data'] .= "<tr>";
            $output['data'] .= "<td>$j</td>";
            $output['data'] .= "<td>" . htmlspecialchars($row['codigo']) . "</td>";
            $output['data'] .= "<td>" . htmlspecialchars($row['valor_nominal']) . " mm</td>";
            
            // Acciones (manteniendo la lógica de tus formularios)
            $output['data'] .= '<td>
                <div class="d-flex">
                    <button type="button" class="btn_rweb_form me-2" onclick="abrirModal('.$id.', \''.$row['codigo'].'\', '.$row['valor_nominal'].', \'ver\')">
        <i class="bx bx-search"></i>
    </button>
    <button type="button" class="btn_rweb_form me-2" onclick="abrirModal('.$id.', \''.$row['codigo'].'\', '.$row['valor_nominal'].', \'editar\')">
        <i class="bx bx-edit"></i>
    </button>
                </div>
            </td>';
            $output['data'] .= "</tr>";
        }
    } else {
        $output['data'] .= "<tr><td colspan='4'>Sin resultados</td></tr>";
    }

  //Para crear la paginación
    $output['paginacion'] = "";
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
            $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData(1)'><<</button>"; 
        }
        for($i = $numeroInicio; $i <= $numeroFinal; $i++){
            if($page == $i){
               $output['paginacion'] .= "<button class='btn_rweb_form'>".$i."</button>";
            }
            else{
                $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData($i)'>".$i."</button>";
            }
        }
        if($page != $totalPaginas){
            $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData($totalPaginas)'>>></button>"; 
        }
    }
    
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>