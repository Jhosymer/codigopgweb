<?php
/* 
    Archivo utilizado para llenar las tablas de especificaciones
    Recibe el número de la página (Int), cantidad de registros (Int), nombre de la tabla (Str)
    y el campo de busqueda (Str).
    Solo el nombre de la tabla es obligatorio

    Devuelve un arreglo llamado $output 
    $output['datos'] -> Devuelve el codigo HTML para la tabla
    $output['totalRegistros'] -> Devuelve el número total de registros, sin tomar en cuenta el valor del campo de busqueda
    $output['totalFiltro'] -> Devuelve el número de registros, tomando en cuenta el valor del campo de busqueda
    $output['paginacion'] -> Devuelve el codigo HTML para la paginación de la tabla
*/


include_once('./../conexion/conexion.php');

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
        $columnas = ["e_a.id", "e_a.id_codigo", "e_a.codigo", "e_a.codigo_buscar", "e_a.tipo", 'f_c.filtracion', "e_a.diametroext1", "e_a.diametroext2", "e_a.diametroint1", "e_a.diametroint2", "e_a.altura", 'f_c.und_empaque', "e_a.detalle1", "e_a.detalle2", "e_a.deleted_at"];
        break;
    case 'espec_aireindustrial':
        $apodo = "e_a";
        $columnas = ["e_a.id", "e_a.id_codigo", "e_a.codigo", "e_a.codigo_buscar", "e_a.tipo", 'f_c.filtracion', "e_a.diametroext1", "e_a.diametroext2", "e_a.diametroint1", "e_a.diametroint2", "e_a.altura", 'f_c.und_empaque', "e_a.detalle1", "e_a.detalle2", "e_a.deleted_at"];
        break;
    case 'espec_combustiblelinea':
        $apodo = "e_c";
        $columnas = ["e_c.id", "e_c.id_codigo", "e_c.codigo", "e_c.codigo_buscar", "e_c.tipo", 'f_c.filtracion', "e_c.diametroext", "e_c.altura", "e_c.entrada", "e_c.salida", 'f_c.und_empaque', "e_c.detalle1", "e_c.detalle2", "e_c.deleted_at"];
        break;
    case 'espec_elemento':
        $apodo = "e_e";
        $columnas = ["e_e.id", "e_e.id_codigo", "e_e.codigo", "e_e.codigo_buscar", "e_e.tipo", 'f_c.filtracion', "e_e.diametroext1", "e_e.diametroint1", "e_e.diametroint2", "e_e.altura", 'f_c.und_empaque', "e_e.detalle1", "e_e.detalle2", "e_e.deleted_at"];
        break;
    case 'espec_fluidos':
        $apodo = "e_f";
        $columnas = ["e_f.id", "e_f.id_codigo", "e_f.codigo", "e_f.codigo_buscar", "e_f.tipo", "e_f.detalle1", "e_f.detalle2", "e_f.deleted_at"];
        break;
    case 'espec_panel':
        $apodo = "e_p";
        $columnas = ["e_p.id", "e_p.id_codigo", "e_p.codigo", "e_p.codigo_buscar", "e_p.tipo", 'f_c.filtracion', "e_p.largo", "e_p.ancho", "e_p.altura", 'f_c.und_empaque', "e_p.detalle1", "e_p.detalle2", "e_p.deleted_at"];
        break;

    case 'espec_cabina':
        $apodo = "e_p";
        $columnas = ["e_p.id", "e_p.id_codigo", "e_p.codigo", "e_p.codigo_buscar", "e_p.tipo", 'f_c.filtracion', "e_p.largo", "e_p.ancho", "e_p.altura", 'f_c.und_empaque', "e_p.detalle1", "e_p.detalle2", "e_p.deleted_at"];
    break;
    
    case 'espec_sellado':
        $apodo = "e_s";
        $columnas = ["e_s.id", "e_s.id_codigo", "e_s.codigo", "e_s.codigo_buscar", "e_s.tipo", 'f_c.filtracion', "e_s.diametroext", "e_s.diametroint", "e_s.altura", "e_s.diametroempext", "e_s.diametroempint", "e_s.espesoremp", "e_s.valvulaal", "e_s.apertura", "e_s.valvulaad", 'f_c.und_empaque', "e_s.detalle1", "e_s.detalle2", "e_s.deleted_at"];
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
    //SE HACE UN CICLO A TODAS LAS COLUMNAS PARA COMPROBAR SI EL CAMPO 
    //TIENE UNA SEMENJANZA CON EL VALOR DE LA COLUMNA
    for($i = 0; $i < $contador; $i++){
        $where .= $columnas[$i] . " " . "LIKE :campo OR";
        $where .= " ";
    }
    $where = substr_replace($where, "", -3);
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

/*
    Se busca la cantidad de registros totales (No se toma en cuenta el valor del campo, en caso de haber)
*/ 
$sql = "SELECT " . implode(',', $columnas) ."
                FROM $tabla as $apodo
                JOIN filtro_codificacion as f_c ON f_c.id = $apodo.id_codigo
                WHERE ( $apodo.deleted_at is null ) and ( f_c.deleted_at is null )";
                
$busqueda =  $base_de_datos->prepare($sql);
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_totales = $busqueda->rowCount();

/*
    Se busca la cantidad de registros filtrado (Se toma en cuenta el valor del campo, en caso de haber)
*/ 
$sql = "SELECT " . implode(",", $columnas) . " 
                FROM $tabla as $apodo
                JOIN filtro_codificacion as f_c ON f_c.id = $apodo.id_codigo
                $where";         
$busqueda =  $base_de_datos->prepare($sql);
if( $campo != null ){
    $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
}
$busqueda->setFetchMode(PDO::FETCH_ASSOC);
$busqueda->execute();

$filas_filtradas = $busqueda->rowCount();

/*
    Se buscan los valores de las columnas de la tabla que se selecciono
*/
$sql = "SELECT " . implode(",", $columnas) . " 
                FROM $tabla as $apodo
                JOIN filtro_codificacion as f_c ON f_c.id = $apodo.id_codigo
                $where $sLimit";
$busqueda =  $base_de_datos->prepare($sql);
if( $campo != null ){
    $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
}
$busqueda->setFetchMode(PDO::FETCH_BOTH);
$busqueda->execute();

/*--------------------SE CREA LA VARIABLE OUTPUT---------------------*/
$output = [];
$output['totalRegistros'] = $filas_totales;
$output['totalFiltro'] = $filas_filtradas;
$output['data'] = "";

/*--------------------CICLO DE LLENADO DE LA TABLA---------------------*/
$j = 0;
if($filas_filtradas > 0){
    while( $row = $busqueda->fetch() ){
        $j = $j + 1;
        $id = $row['id'];
        $codigo = $row['codigo'];
        $id_codigo = $row['id_codigo'];
        $id_tipo = $row['tipo'];

        $output['data'] .= "<tr>";
        //Se crea el ciclo para colocar las columnas de la tabla
        for($i = 0; $i < (count($columnas) - 1); $i++){
            //En caso de ser valvulaal se colocará SI o NO (Ni 1, ni 0)
            if( $columnas[$i] == 'valvulaal' ){
                if( $row[$i] == 1 ){
                    $output['data'] .= "<td>SI</td>";
                }
                else {
                    $output['data'] .= "<td>NO</td>";
                }
            }
            //Mismo caso en valvulaad se colocará SI o NO (Ni 1, ni 0)
            else if( $columnas[$i] == 'valvulaad' ){
                if( $row[$i] == 1 ){
                    $output['data'] .= "<td>SI</td>";
                }
                else {
                    $output['data'] .= "<td>NO</td>";
                }
            }
            //En caso de no existir valor de filtración, no se colocará
            else if( $columnas[$i] == 'f_c.filtracion' ){
                if( $row[$i] != null && $row[$i] != '' ){
                    $output['data'] .= "<td>". $row[$i] . "</td>";
                }
                else {
                    $output['data'] .= "<td>No tiene</td>";
                }
            }
            //En caso de no existir valor de unidades de empaque, no se colocará
            else if( $columnas[$i] == 'f_c.und_empaque' ){
                if( $row[$i] != null && $row[$i] != 0 && $row[$i] != '' ){
                    $output['data'] .= "<td>". $row[$i] . "</td>";
                }
                else {
                    $output['data'] .= "<td>No tiene</td>";
                }
            }
            //En caso de no existir valor de apertura, no se colocará
            else if( $columnas[$i] == 'apertura' && $row[$i] == null ){
                $output['data'] .= "<td>No tiene</td>";
            }
            //No se colocará nada si es id_codigo
            else if( $columnas[$i] == 'e_s.id_codigo' || $columnas[$i] == 'e_p.id_codigo' || $columnas[$i] == 'e_a.id_codigo' || $columnas[$i] == 'e_e.id_codigo' || $columnas[$i] == 'e_f.id_codigo' || $columnas[$i] == 'e_c.id_codigo' ){

            }
            //Por defecto se colocará el valor de la columna
            else {
                $output['data'] .= "<td>". $row[$i] . "</td>";
            }
        }
        //Botones de Acciones
        $output['data'] .= '<td>
                <section class="about_boton">
                    <div class="tex_tabla">
                        <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                            <input value="'.$row['id'].'" name="id" type="hidden" />
                            <input value="'.$row['id_codigo'].'" name="id_codigo" type="hidden" />
                            <input type="submit" onclick="eliminado(event,'.$j.')" value="" name="btnEliminar" class="del input" />
                        </form>
                    </div>
                    <div class="tex_tablas">
                    <form action="ver.php" method="POST">
                        <input value="'.$row['id'].'" name="id" type="hidden" />
                        <input value="'.$row['id_codigo'].'" name="id_codigo" type="hidden" />
                        <input type="submit" value="" name="btnVer"  class="ver input" />
                    </form>
                </div>
                <div class="tex_tablas">
                    <form action="editar.php" method="POST">
                        <input value="'.$id.'" name="id" type="hidden" />
                        <input type="submit" value="" name="btnEditar" class="edi input" />
                    </form>
                </div>
                    <div class="tex_tablas">
                        <form action="editar_imagenes.php" method="POST">
                            <input value="'. $id. '" name="id" type="hidden" />
                            <input value="'. $codigo. '" name="codigo" type="hidden" />
                            <input type="submit" value="" name="btnimg" class="foto input" />
                        </form>
                    </div>

                      <div class="tex_tablas">
                        <form action="editar_pdf.php" method="POST">
                            <input value="'. $id. '" name="id" type="hidden" />
                            <input value="'. $codigo. '" name="codigo" type="hidden" />
                             <input value="'. $id_codigo. '" name="idcodigo" type="hidden" />
                            <input type="submit" value="" name="btnpdf" class="pdf input" />
                        </form>
                    </div>
                    

                   
                    
                </section>
        </td>';
        $output['data'] .= "</tr>";
    }
} 
else {
    $output['data'] .= "<tr>";
    $output['data'] .= "<td>Sin resultados</td>";
    $output['data'] .= "</tr>";
}

/*--------------------SE HACE LA PAGINACIÓN---------------------*/
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
        $output['paginacion'] .= "<a onclick='getData(1)'  style='cursor: pointer;'>Primero</a>"; 
    }
    for($i = $numeroInicio; $i <= $numeroFinal; $i++){
        if($page == $i){
           $output['paginacion'] .= "<p>" . $i ." </p>";
        }
        else{
            $output['paginacion'] .= "<a onclick='getData($i)'  style='cursor: pointer;'>".$i."</a>";
        }
    }
    if($page != $totalPaginas){
        $output['paginacion'] .= "<a onclick='getData($totalPaginas)'  style='cursor: pointer;'>Último</a>"; 
    }
}

echo json_encode($output);
