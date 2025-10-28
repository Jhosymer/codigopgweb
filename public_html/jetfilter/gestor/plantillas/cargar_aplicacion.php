<?php 
   include '../../../config/conexion.php'; 

    //Las columnas que se van a necesitar para la tabla de la aplicación
    $columnas = ['a.id', 'a.id_tipo', 'a_m.marca', 'a_v.modelo', 'a_v.motor', 'a.aplicacion', 'a.codigo', 'a.id_codigo', 'a.detalle'];

    //El valor del campo de busqueda
    $campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
    $campo_busqueda = '%' . $campo . '%'; //Se utilizara esta campo para las consulta del WHERE (LIKE)
    
    $where = '';

    if($campo != null){
        $where = " WHERE (a.id_tipo = :id_tipo) and ( ";
        $contador = count($columnas);
        //SE HACE UN CICLO A TODAS LAS COLUMNAS PARA COMPROBAR SI EL CAMPO 
        //TIENE UNA SEMENJANZA CON EL VALOR DE LA COLUMNA
        for($i = 0; $i < $contador; $i++){
            $where .= $columnas[$i] . " " . "LIKE :campo OR";
            $where .= " ";
        }
        $where = substr_replace($where, "", -3);
        $where .= ") and (a.deleted_at is null) ";
    }
    else{
        $where .= " WHERE (a.id_tipo = :id_tipo) and (a.deleted_at is null) ";
    }

    //Parametros para hacer la tabla de aplicaciones
    $limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10; //Numero de registros a mostrar
    $page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1; //Página
    $tipo = isset( $_POST['tipo']) ? htmlspecialchars($_POST['tipo']) : 4; //Tipo de aplicación
    $inicio = $limit * ($page - 1);
    $sLimit = " LIMIT $inicio, $limit";

    //Consulta aplicaciones totales que cumplan los parametros (No toma en cuenta el campo de busqueda)
    $sql = "SELECT a.id, a.id_tipo, a_v.modelo, a_v.motor, a_m.marca, a.aplicacion, a.codigo, a.id_codigo, a.detalle FROM aplicacion as a
            JOIN aplicacion_marca as a_m ON a_m.id = a.id_marca
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo
            WHERE (a.id_tipo = :id_tipo) and (a.deleted_at is null)
            ORDER BY a.id ASC";
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->bindParam(':id_tipo',$tipo,PDO::PARAM_INT);
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();

    $filas_totales = $busqueda->rowCount();

    //Consulta aplicaciones filtrada que cumplan los parametros (Toma en cuenta el campo de busqueda)
    $sql = "SELECT a.id, a.id_tipo, a_v.modelo, a_v.motor, a_m.marca, a.aplicacion, a.codigo, a.id_codigo, a.detalle FROM aplicacion as a
            JOIN aplicacion_marca as a_m ON a_m.id = a.id_marca
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo
            $where
            ORDER BY a.id ASC";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->bindParam(':id_tipo',$tipo,PDO::PARAM_INT);
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();
    $filas_filtradas = $busqueda->rowCount();

    //Selecciona los datos de las aplicaciones
    $sql = "SELECT a.id, a.id_tipo, a_m.marca, a_v.modelo, a_v.motor, a.aplicacion, a.id_codigo, a.codigo, a.detalle FROM aplicacion as a
            JOIN aplicacion_marca as a_m ON a_m.id = a.id_marca
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo
            $where
            ORDER BY a.id ASC
            $sLimit";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->bindParam(':id_tipo',$tipo,PDO::PARAM_INT);
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();

    /*--------------------CICLO DE LLENADO DE LA TABLA---------------------*/
    $output = [];
    $output['data'] = "";
    $output['totalRegistros'] = $filas_totales;
    $output['totalFiltro'] = $filas_filtradas;
    $j = 0;

    if($filas_totales > 0){
        while( $row = $busqueda->fetch() ){
            $j = $j + 1;
            $id = $row['id'];
            $codigo = $row['codigo'];
            $id_codigo = $row['id_codigo'];
            $output['data'] .= "<tr>";

            //Se crea el ciclo para colocar las columnas de la tabla
            for($i = 0; $i < (count($columnas)); $i++){
                //Dependiendo del tipo de aplicación, colocará ese nombre
                if($i == 1){
                    Switch($row[$i]){
                        case 1:
                            $row[$i] = "Liviano";
                            break;
                        case 2:
                            $row[$i] = "Comercial";
                            break;
                        case 3:
                            $row[$i] = "Fuera de Carretera";
                            break;
                        case 4:
                            $row[$i] = "Agricola";
                            break;
                    }
                }
                $output['data'] .= "<td>". $row[$i] . "</td>";
            }
            $output['data'] .= '<td>
                    <div class="d-flex">
                         <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                            <input value="'.$row['id'].'" name="id" type="hidden" />
                            <input value="'.$row['id_codigo'].'" name="id_codigo" type="hidden" />
                            <button type="button" name="btnEliminar" class="btn_rweb_form me-2" onclick="eliminado(event, \''.$j.'\')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                   
                        <form action="ver.php" method="POST">
                            <input value="'.$row['id'].'" name="ver" type="hidden" />
                            <button type="submit" class="btn_rweb_form me-2" name="btnVer" ><i class="bx bx-search"></i></button>
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
    else{
        $output['data'] .= "<tr>";
        $output['data'] .= "<td>Sin resultados</td>";
        $output['data'] .= "</tr>";
    }

    /*--------------------SE HACE LA PAGINACIÓN---------------------*/
    $output['paginacion'] = ""; //Contiene el codigo de la paginación

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
            $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData(1, `$tipo`)'><<</button>"; 
        }
        for($i = $numeroInicio; $i <= $numeroFinal; $i++){
            if($page == $i){
            $output['paginacion'] .= "<button class='btn_rweb_form' onclick='getData($i)'>".$i."</button>";
            }
            else{
                $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData($i, `$tipo`)'>".$i."</button>";
            }
        }
        if($page != $totalPaginas){
            $output['paginacion'] .= "<button class='btn_borde_rweb_form' onclick='getData($totalPaginas, `$tipo`)'>>></button>"; 

            
        }
    }

    echo json_encode($output, JSON_UNESCAPED_UNICODE);