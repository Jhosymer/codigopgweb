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

$columnas = ['categorias.id', 'categorias.categoria', 'categorias.clase', 'productos.nombre'];
$campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;

$where = "";
if($campo != null){
    $where = "WHERE (";
    $contador = count($columnas);
    for($i = 0; $i < $contador; $i++){
        $where .= $columnas[$i] . " " . "LIKE '%" . $campo . "%' OR";
        $where .= " ";
    }
    $where = substr_replace($where, "", -3);
    $where .= ") and ( categorias.deleted_at is null )";
}
else {
    $where = "WHERE ( categorias.deleted_at is null )";
}

$limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
$page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
$inicio = $limit * ($page - 1);
$sLimit = "LIMIT $inicio, $limit";

$sql = "SELECT " . implode(',',$columnas) ."
                FROM categorias 
                JOIN productos ON productos.id = categorias.producto_id
                WHERE ( categorias.deleted_at is null )";
                
$busqueda =  $base_de_datos->prepare($sql);
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_totales = $busqueda->rowCount();

$sql = "SELECT " . implode(",",$columnas) . " 
                FROM categorias 
                JOIN productos ON productos.id = categorias.producto_id
                $where";         
$busqueda =  $base_de_datos->prepare($sql);
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_filtradas = $busqueda->rowCount();

$sql = "SELECT " . implode(",",$columnas) . " 
                FROM categorias 
                JOIN productos ON productos.id = categorias.producto_id
                $where $sLimit";
$busqueda =  $base_de_datos->prepare($sql);
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
        $id = $row['id'];
        $output['data'] .= "<tr>";
        $output['data'] .= "<td>" . $id . "</td>";
        $output['data'] .= "<td>" . $row['categoria'] . "</td>";
        $output['data'] .= "<td>" . $row['clase'] . "</td>";
        $output['data'] .= "<td>" . $row['nombre'] . "</td>";
        $output['data'] .= "</td>";
        
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
