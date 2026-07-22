<?php
/* Archivo utilizado para llenar las tablas de especificaciones
    Recibe el número de la página (Int), cantidad de registros (Int), nombre de la tabla (Str)
    y el campo de busqueda (Str).
    Solo el nombre de la tabla es obligatorio
*/

include '../../../config/conexion.php';

/*
    Las tablas dentro de la lista serán validas
*/ 
function listaTablas($nombreTabla){
    $tablasPermitidas = array('espec_aireautomotriz', 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado', 'espec_fluidos', 'espec_cabina');
    $estadoValidacion = in_array($nombreTabla, $tablasPermitidas);
    return $estadoValidacion;
}

/*------------SE C0MPRUEBA LA TABLA----------------*/
$tabla = listaTablas( $_POST['tabla'] ) ? htmlspecialchars($_POST['tabla']) : null;

/*
    Dependiendo de la tabla de especificaciones que se vaya a buscar, se guardan esas columnas
*/
switch($tabla){
    case 'espec_aireautomotriz':
        $apodo = "e_a";
        $columnas = ["e_a.id", "e_a.id_codigo", "e_a.codigo", "e_a.codigo_buscar", "e_a.tipo", 'f_c.filtracion', "e_a.diametroext1", "e_a.diametroext2", "e_a.diametroint1", "e_a.diametroint2", "e_a.altura", 'f_c.und_empaque', 'f_c.codigo_barra', "e_a.detalle1", "e_a.detalle2", "e_a.deleted_at"];
        break;
    case 'espec_aireindustrial':
        $apodo = "e_a";
        $columnas = ["e_a.id", "e_a.id_codigo", "e_a.codigo", "e_a.codigo_buscar", "e_a.tipo", 'f_c.filtracion', "e_a.diametroext1", "e_a.diametroext2", "e_a.diametroint1", "e_a.diametroint2", "e_a.altura", 'f_c.und_empaque', 'f_c.codigo_barra', "e_a.detalle1", "e_a.detalle2", "e_a.deleted_at"];
        break;
    case 'espec_combustiblelinea':
        $apodo = "e_c";
        $columnas = ["e_c.id", "e_c.id_codigo", "e_c.codigo", "e_c.codigo_buscar", "e_c.tipo", 'f_c.filtracion', "e_c.diametroext", "e_c.altura", "r_ent.codigo as rosca_entrada", "e_c.entrada", "p_ent.codigo as pulgada_entrada",  "r_sal.codigo as rosca_salida",  "e_c.salida", "p_sal.codigo as pulgada_salida", 'f_c.und_empaque', 'f_c.codigo_barra', "e_c.detalle1", "e_c.detalle2",  "e_c.deleted_at"];
        break;
    case 'espec_elemento':
        $apodo = "e_e";
        $columnas = ["e_e.id", "e_e.id_codigo", "e_e.codigo", "e_e.codigo_buscar", "e_e.tipo", 'f_c.filtracion', "e_e.diametroext1", "e_e.diametroint1", "e_e.diametroint2", "e_e.altura", 'f_c.und_empaque', 'f_c.codigo_barra', "e_e.detalle1", "e_e.detalle2", "e_e.deleted_at"];
        break;
    case 'espec_fluidos':
        $apodo = "e_f";
        $columnas = ["e_f.id", "e_f.id_codigo", "e_f.codigo", "e_f.codigo_buscar", "e_f.tipo", "e_f.etilenglicol", 'f_c.und_empaque', 'f_c.codigo_barra', "e_f.detalle1", "e_f.detalle2", "e_f.deleted_at"];
        break;
    case 'espec_panel':
    case 'espec_cabina':
        $apodo = "e_p";
        $columnas = ["e_p.id", "e_p.id_codigo", "e_p.codigo", "e_p.codigo_buscar", "e_p.tipo", 'f_c.filtracion', "e_p.largo", "e_p.ancho", "e_p.altura", 'f_c.und_empaque', 'f_c.codigo_barra', "e_p.detalle1", "e_p.detalle2", "e_p.deleted_at"];
        break;
    case 'espec_sellado':
        $apodo = "e_s";
        // Se agrega r.codigo as rosca para obtener el nombre de la rosca
        $columnas = ["e_s.id", "e_s.id_codigo", "e_s.codigo", "e_s.codigo_buscar", "e_s.tipo", 'f_c.filtracion',  "e_s.altura", "r.codigo as rosca", "e_s.diametroint", "e_s.diametroext", "e_s.diametroempext",  "e_s.diametroempint", "e_s.espesoremp", "e_s.valvulaal", "e_s.apertura", "e_s.valvulaad", 'f_c.und_empaque', 'f_c.codigo_barra', "e_s.detalle1", "e_s.detalle2", "e_s.deleted_at"];
        break;
}

/*----------------VALOR DEL CAMPO--------------*/
$campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
$campo_busqueda = "%" . $campo . "%";

/*------------------SE COLOCAN LAS CONDICIONES-----------------*/ 
$where = "";
if($campo != null){
    $where = "WHERE (";
    $contador = count($columnas);
    for($i = 0; $i < $contador; $i++){
        // Limpiamos el alias para que el LIKE no de error
        $colReal = (strpos($columnas[$i], ' as ') !== false) ? explode(' as ', $columnas[$i])[0] : $columnas[$i];
        $where .= $colReal . " LIKE :campo OR ";
    }
    $where = substr($where, 0, -4);
    $where .= ") and ( $apodo.deleted_at is null ) and ( f_c.deleted_at is null )";
}
else {
    $where = "WHERE ( $apodo.deleted_at is null ) and ( f_c.deleted_at is null )";
}

/*----------------------SE LLENAN LAS VARIABLES DE REGISTROS Y PAGINA-----------------*/
$limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
$page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
$inicio = $limit * ($page - 1);
$sLimit = "LIMIT $inicio, $limit";

// JOIN condicional para la tabla de roscas
$joinRosca = "";
if ($tabla == 'espec_sellado') {
    $joinRosca = "LEFT JOIN roscas as r ON r.id = $apodo.id_rosca";
} else if ($tabla == 'espec_combustiblelinea') {
    $joinRosca = "LEFT JOIN roscas as r_ent ON r_ent.id = $apodo.id_rosca_entrada 
                  LEFT JOIN roscas as r_sal ON r_sal.id = $apodo.id_rosca_salida
                  LEFT JOIN pulgadas as p_ent ON p_ent.id = $apodo.id_pulgada_entrada 
                  LEFT JOIN pulgadas as p_sal ON p_sal.id = $apodo.id_pulgada_salida";
}

/*
    Se busca la cantidad de registros totales filtrados
*/ 
$sql_count = "SELECT COUNT(*) FROM $tabla as $apodo 
              JOIN filtro_codificacion as f_c ON f_c.id = $apodo.id_codigo 
              $joinRosca $where";
$res_count = $base_de_datos->prepare($sql_count);
if( $campo != null ) $res_count->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
$res_count->execute();
$filas_filtradas = $res_count->fetchColumn();

// Para total sin filtro (opcional según tu lógica original)
$filas_totales = $filas_filtradas; 

/*
    Se buscan los valores de las columnas
*/
$sql = "SELECT " . implode(",", $columnas) . " 
        FROM $tabla as $apodo
        JOIN filtro_codificacion as f_c ON f_c.id = $apodo.id_codigo
        $joinRosca
        $where $sLimit";

$busqueda = $base_de_datos->prepare($sql);
if( $campo != null ) $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
$busqueda->setFetchMode(PDO::FETCH_BOTH);
$busqueda->execute();

/*--------------------SE CREA LA VARIABLE OUTPUT---------------------*/
$output = [];
$output['totalRegistros'] = $filas_totales;
$output['totalFiltro'] = $filas_filtradas;
$output['data'] = "";

/*--------------------CICLO DE LLENADO DE LA TABLA---------------------*/
$j = $inicio;
if($filas_filtradas > 0){
    while( $row = $busqueda->fetch() ){
        $j++;
        $id = $row['id'];
        $codigo = $row['codigo'];
        $id_codigo = $row['id_codigo'];
        
        $output['data'] .= "<tr>";
        
        for($i = 0; $i < (count($columnas) - 1); $i++){
            
            // Caso: Válvulas (sí/NO)
            if( strpos($columnas[$i], 'valvulaal') !== false || strpos($columnas[$i], 'valvulaad') !== false ){
                $output['data'] .= "<td>" . ($row[$i] == 1 ? "sí" : "NO") . "</td>";
            }
            // Caso: Filtración o Unidades
            else if( strpos($columnas[$i], 'f_c.filtracion') !== false || strpos($columnas[$i], 'f_c.und_empaque') !== false ){
                $output['data'] .= "<td>" . (!empty($row[$i]) ? $row[$i] : "No tiene") . "</td>";
            }
            // Caso: Código de Barras
            else if( strpos($columnas[$i], 'f_c.codigo_barra') !== false ){
                $output['data'] .= "<td>" . (!empty($row[$i]) ? $row[$i] : "") . "</td>";
            }
            else if( strpos($columnas[$i], 'as rosca_entrada') !== false || strpos($columnas[$i], 'as rosca_salida') !== false ){
    // Si el valor existe en el row, lo muestra; de lo contrario, muestra N/D (No Definido)
    $valor_rosca = (!empty($row[$i])) ? $row[$i] : "N/A";
    $output['data'] .= "<td>" . $valor_rosca . "</td>";
}

 else if( strpos($columnas[$i], 'as pulgada_entrada') !== false || strpos($columnas[$i], 'as pulgada_salida') !== false ){
    // Si el valor existe en el row, lo muestra; de lo contrario, muestra N/D (No Definido)
    $valor_rosca = (!empty($row[$i])) ? $row[$i] : "N/A";
    $output['data'] .= "<td>" . $valor_rosca . "</td>";
}

// Caso: Rosca única (Para la tabla de Sellado)
else if( strpos($columnas[$i], 'as rosca') !== false ){
    $output['data'] .= "<td>" . (!empty($row[$i]) ? $row[$i] : "N/A") . "</td>";
}
else if( strpos($columnas[$i], 'e_s.diametroint') !== false ){
    // Verificamos si es null, vacío o 0 (ya que en base de datos de medidas el 0 suele ser equivalente a no tener)
    $valor_diametro = ($row[$i] === null || $row[$i] === "" || $row[$i] == 0) ? "N/A" : $row[$i];
    $output['data'] .= "<td>" . $valor_diametro . "</td>";
}
            // Caso: Apertura
            else if( strpos($columnas[$i], 'apertura') !== false && $row[$i] == null ){
                $output['data'] .= "<td>No tiene</td>";
            }
            // Caso: Rosca (Aquí es donde se muestra el código de la rosca)
            
            // Caso: Ocultar IDs de códigos internos
            else if( strpos($columnas[$i], '.id_codigo') !== false ){
                // Se salta el ID
            }
            // Por defecto
            else {
                $output['data'] .= "<td>". $row[$i] . "</td>";
            }
        }

        // Botones de Acciones
        $output['data'] .= '<td>
            <div class="d-flex">
                <form action="ver.php" method="POST">
                    <input value="'.$id.'" name="id" type="hidden" />
                    <input value="'.$id_codigo.'" name="id_codigo" type="hidden" />
                    <button type="submit" class="btn_rweb_form me-2"><i class="bx bx-search"></i></button>
                </form>
                <form action="editar.php" method="POST">
                    <input value="'.$id.'" name="id" type="hidden" />
                    <button type="submit" class="btn_rweb_form me-2"><i class="bx bx-edit"></i></button>
                </form>
                <form action="editar_imagenes.php" method="POST">
                    <input value="'.$id.'" name="id" type="hidden" /><input value="'.$codigo.'" name="codigo" type="hidden" />
                    <button type="submit" class="btn_rweb_form me-2"><i class="bx bx-image"></i></button>
                </form>
                <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                            <input value="'.$row['id'].'" name="id" type="hidden" />
                            <input value="'.$row['id_codigo'].'" name="id_codigo" type="hidden" />
                            <button type="button" name="btnEliminar" class="btn_rweb_form me-2" onclick="eliminado(event, \''.$j.'\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
            </div>
        </td></tr>';
    }
} else {
    $output['data'] .= "<tr><td colspan='100%'>Sin resultados</td></tr>";
}

/*--------------------PAGINACIÓN---------------------*/
$output['paginacion'] = "";
if($output['totalFiltro'] > 0){
    $totalPaginas = ceil($output['totalFiltro'] / $limit);
    $numeroInicio = max(1, $page - 3);
    $numeroFinal = min($totalPaginas, $numeroInicio + 7);

    if($page != 1) $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData(1)'><<</button>"; 
    for($i = $numeroInicio; $i <= $numeroFinal; $i++){
        $clase = ($page == $i) ? 'btn_rweb_form' : 'btn_borde_rweb_form';
        $output['paginacion'] .= "<button class='$clase' onclick='getData($i)'>$i</button>";
    }
    if($page != $totalPaginas) $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData($totalPaginas)'>>></button>"; 
}

echo json_encode($output);