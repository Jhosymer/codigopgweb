<?php 
    /* ARCHIVO PARA CREAR Y LLENAR LA TABLA DE VEHÍCULOS DE EQUIVALENCIAS */
    //Parametros a Recibir
    //$campo (Opcional), si se esta haciendo una busqueda con parametros
    //$page (Opcional), la página que devolvera. De no existir tendrá la página 1 por defecto
    //$registros (Opcional), el número de registros que devolvera. De no existir tendrá 10 registros por defecto

    include_once('./../conexion/conexion.php');

   //Columnas que se van a mostrar en pantalla
    $columnas = ['id','id_codigo','codigo','codigo_buscar','marca','codigo_marca','codigo_marca_buscar'];

    //Tabla a modificar
    $tabla = "filtro_equivalencia";

    /*----------------VALOR DEL CAMPO--------------*/
    $campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
    $campo_busqueda = '%' . $campo .'%';

    $where = "";
    if($campo != null){
        $where = " WHERE (";
        $contador = count($columnas);
        //SE HACE UN CICLO A TODAS LAS COLUMNAS PARA COMPROBAR SI EL CAMPO 
        //TIENE UNA SEMENJANZA CON EL VALOR DE LA COLUMNA
        for($i = 0; $i < $contador; $i++){
            $where .= $columnas[$i] . " " . "LIKE :campo OR";
            $where .= " ";
        }
        $where = substr_replace($where, "", -3);
        $where .= ") and (deleted_at is null)";
    }
    else {
        $where = " WHERE deleted_at is null";
    }

    /*----------------------SE LLENAN LAS VARIABLES DE REGISTROS Y PAGINA-----------------*/
    $limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
    $page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
    $inicio = $limit * ($page - 1);
    $sLimit = " LIMIT $inicio, $limit";

    /*
        Se busca la cantidad de registros totales (No se toma en cuenta el valor del campo, en caso de haber)
    */ 
    $sql = "SELECT " . implode(",", $columnas) . " FROM $tabla WHERE ( deleted_at is null )";
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->execute();

    $filas_totales = $busqueda->rowCount();

    /*
        Se busca la cantidad de registros filtrado (Se toma en cuenta el valor del campo, en caso de haber)
    */  
    $sql = "SELECT " . implode(",", $columnas) . " FROM $tabla $where ";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){   
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->execute();

    $filas_filtradas = $busqueda->rowCount();

    /*
        Se buscan los valores de las columnas de la tabla que se selecciono
    */
    $sql = "SELECT " . implode(",", $columnas) . " FROM $tabla $where $sLimit";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){   
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->execute();

    /*--------------------SE CREA LA VARIABLE OUTPUT---------------------*/
    $output = [];
    $output['totalRegistros'] = $filas_totales;
    $output['totalFiltro'] = $filas_filtradas;
    $output['data'] = "";

    $j = 0;
    if($filas_filtradas > 0){
        while( $row = $busqueda->fetch() ){ 
            $j = $j + 1;
            $id = $row['id'];
            $codigo = $row['codigo'];
            $id_codigo = $row['id_codigo'];

            //Se ccolocan las columnas de la tabla
            $output['data'] .= "<tr>";
            $output['data'] .= "<td>" . $row['id'] . "</td>";
            $output['data'] .= "<td>" . $row['id_codigo'] . "</td>";
            $output['data'] .= "<td>" . $row['codigo'] . "</td>";
            $output['data'] .= "<td>" . $row['codigo_buscar'] . "</td>";
            $output['data'] .= "<td>" . $row['marca'] . "</td>";
            $output['data'] .= "<td>" . $row['codigo_marca'] . "</td>";
            $output['data'] .= "<td>" . $row['codigo_marca_buscar'] . "</td>";
            $output['data'] .= "</td>";

            //Se coloca la columna de las acciones
            $output['data'] .= '<td>
                    <section class="about_boton">
                        <div class="tex_tabla">
                            <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                                <input value="'. $id .'" name="id" type="hidden" />
                                <input value="'.$row['id_codigo'].'" name="id_codigo" type="hidden" />
                                <input type="submit" onclick="eliminado(event,'.$j.')" value="" name="btnEliminar" class="del input" />
                            </form>
                        </div>
                        <div class="tex_tablas">
                        <form action="ver.php" method="POST">
                            <input value="'. $id .'" name="id" type="hidden" />
                            <input value="' . $page . '" name="page" type="hidden" />
                            <input value="' . $limit . '" name="registros" type="hidden" />
                            <input type="submit" value="" name="btnVer"  class="ver input" />
                        </form>
                    </div>
                    <div class="tex_tablas">
                        <form action="editar.php" method="POST">
                            <input value="'.$id.'" name="id" type="hidden" />
                            <input type="submit" value="" name="btnEditar" class="edi input" />
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
    $numeroInicio = 1;
    $output['paginacion'] = "";

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

    //Devuelve el arreglo $output, que tiene 4 valores dentro:
    //$output['filasTotales'] -> Contiene el número de filas totales, sin contar el campo de busqueda
    //$output['filasFiltradas'] -> Contiene el número de filas totales, contando el campo de busqueda
    //$output['filasFiltradas'] -> Contiene el número de filas totales, contando el campo de busqueda
    //$output['data'] -> Contiene el código HTML para crear la tabla de los vehículos de las aplicaciones
    //$output['paginacion'] -> Contiene el código HTML para la paginación de la tabla
    echo json_encode($output);
?>