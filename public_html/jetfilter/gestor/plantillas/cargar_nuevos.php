<?php
try{
    $url_base_datos = '../../../config/conexion.php';
    if ( !file_exists( $url_base_datos ) ){
        throw new Exception ('No encontró la base de datos');
    }
    else {
        include_once($url_base_datos);
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
catch(Exception $e){
    echo "
    <script>
        Swal.fire({
            icon: 'error',
            title: '" . $e->getMessage() . "',
        })
    </script>";
}
catch(PDOException $e){
    ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Ha sucedido un error con la conexión a la base de datos',
            })
        </script>
    <?php
}

$columnas = ['n_f.id', 'n_f.codigo', 'f_c.codigo_buscar', 'f_c.clase', 'm1.marca AS marca_1', 'm2.marca AS marca_2','m3.marca AS marca_3'];
$campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
$campo_busqueda = '%' . $campo . '%';

$where = "";
if($campo != null){
    $where = "WHERE (";
    $contador = count($columnas);
    for($i = 0; $i < $contador; $i++){
        $where .= $columnas[$i] . " " . "LIKE :campo OR";
        $where .= " ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ") and ( f_c.deleted_at is null )";
}
else {
    $where = "WHERE ( f_c.deleted_at is null )";
}

$limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
$page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
$inicio = $limit * ($page - 1);
$sLimit = "LIMIT $inicio, $limit";

$sql = "SELECT " . implode(',',$columnas) ."
                FROM nuevos_filtros as n_f
                JOIN filtro_codificacion AS f_c ON f_c.codigo = n_f.codigo
                LEFT JOIN aplicacion_marca AS m1 ON m1.id = n_f.id_marca
                LEFT JOIN aplicacion_marca AS m2 ON m2.id = n_f.id_marca1
                LEFT JOIN aplicacion_marca AS m3 ON m3.id = n_f.id_marca2
                WHERE ( f_c.deleted_at is null )";
                
$busqueda =  $base_de_datos->prepare($sql);
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_totales = $busqueda->rowCount();

$sql = "SELECT " . implode(",",$columnas) . " 
                FROM nuevos_filtros as n_f
                JOIN filtro_codificacion AS f_c ON f_c.codigo = n_f.codigo
                LEFT JOIN aplicacion_marca AS m1 ON m1.id = n_f.id_marca
                LEFT JOIN aplicacion_marca AS m2 ON m2.id = n_f.id_marca1
                LEFT JOIN aplicacion_marca AS m3 ON m3.id = n_f.id_marca2
                $where";         
$busqueda =  $base_de_datos->prepare($sql);
if( $campo != null ){
    $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
}
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_filtradas = $busqueda->rowCount();

$sql = "SELECT " . implode(",",$columnas) . " 
                FROM nuevos_filtros as n_f
                JOIN filtro_codificacion AS f_c ON f_c.codigo = n_f.codigo
                LEFT JOIN aplicacion_marca AS m1 ON m1.id = n_f.id_marca
                LEFT JOIN aplicacion_marca AS m2 ON m2.id = n_f.id_marca1
                LEFT JOIN aplicacion_marca AS m3 ON m3.id = n_f.id_marca2 
                $where $sLimit";
$busqueda =  $base_de_datos->prepare($sql);
if( $campo != null ){
    $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
}
$busqueda->setFetchMode(PDO::FETCH_BOTH);
$busqueda->execute();

$output = [];
$output['totalRegistros'] = $filas_totales;
$output['totalFiltro'] = $filas_filtradas;
$output['data'] = "";

$j = 0;

if($filas_filtradas > 0){
    while( $row = $busqueda->fetch() ){ 
        $j = $j + 1;
        $registro = $j + ( ( $page - 1 ) * 10 );
        $id = $row['id'];
        $codigo = $row['codigo'];
        $codigo_buscar = $row['codigo_buscar'];
        $clase = $row['clase'];
        $marca_1 = $row['marca_1'];
        $marca_2 = $row['marca_2'];
        $marca_3 = $row['marca_3'];

        $output['data'] .= "<tr>";
        $output['data'] .= "<td>" . $registro . "</td>";
        $output['data'] .= "<td>$codigo</td>";
        $output['data'] .= "<td>$codigo_buscar</td>";
        $output['data'] .= "<td>$clase</td>";
        $output['data'] .= "<td>$marca_1</td>";
        $output['data'] .= "<td>$marca_2</td>";
        $output['data'] .= "<td>$marca_3</td>";
        
        $output['data'] .= '<td>
                    <div class="d-flex">
                        <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                            <input value="'. $id .'" name="id" type="hidden" />
                           <button type="button" name="btnEliminar" class="btn_rweb_form me-2" onclick="eliminado(event, \''.$j.'\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                   
                        <form action="editar.php" method="POST">
                            <input value="'.$id.'" name="id" type="hidden" />
                           <button type="submit" class="btn_rweb_form me-2" name="btnEditar" ><i class="bx bx-edit"></i></button>
                        </form>
                    </div>
            </td>';


        $output['data'] .= "</tr>";
    }
}
else {
    $output['data'] .= "<tr>";
    $output['data'] .= "<td>Sin resultados</td>";
    $output['data'] .= "</tr>";
}

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

echo json_encode($output);
