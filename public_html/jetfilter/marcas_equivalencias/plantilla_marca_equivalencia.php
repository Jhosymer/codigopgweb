<?php 
    /* ARCHIVO PARA CREAR Y LLENAR LA TABLA DE MARCAS DE EQUIVALENCIA */
    //Parametros a Recibir
    //$campo (Opcional), si se esta haciendo una busqueda con parametros
    //$page (Opcional), la página que devolvera. De no existir tendrá la página 1 por defecto
    //$registros (Opcional), el número de registros que devolvera. De no existir tendrá 10 registros por defecto

    include_once('./../conexion/conexion.php');
    //Columnas que se van a mostrar
    $columnas = ['id', 'marca', 'mostrar', 'sincronizado'];

    //Si el campo de busqueda no esta vacio, se enviara. En tal caso de que no haya campo de busqueda
    //$campo quedara como nulo
    $campo = isset( $_POST['campo'] ) ? htmlspecialchars($_POST['campo']) : null;
    $campo_busqueda = '%' . $campo . '%';

    $where = "";

    //Se inicializa el arreglo $output (Este el arreglo que se va a a devolver)
    $output = [];

    //Se llenan los datos de número de registros y la página que se va a mostrar
    $limit = isset( $_POST['num_registros'] ) ? $_POST['num_registros'] : 10;
    $page = isset( $_POST['pagina']) ? htmlspecialchars($_POST['pagina']) : 1;
    $inicio = $limit * ($page - 1);
    $sLimit = " LIMIT $inicio, $limit";

    //Se llena la variable where, que contendrá las condiciones para llenar la tabla
    if($campo != null){
        $where = " WHERE ( ";
        $contador = count($columnas);
        for($i = 0; $i < $contador; $i++){
            $where .= $columnas[$i] . " " . "LIKE :campo OR";
            $where .= " ";
        }
        $where = substr_replace($where, "", -3);
        $where .= ") and (deleted_at is null) ";
    }
    else{
        $where .= "WHERE (deleted_at is null) ";
    }
    
    //Consulta para devolver el total de registros (No se toma en cuenta el campo de busqueda);
    $sql = "SELECT id, marca, mostrar, sincronizado FROM equivalencia_marca
        WHERE (deleted_at is null)";
    $busqueda =  $base_de_datos->prepare($sql);
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();

    
    $filas_totales = $busqueda->rowCount();

    //Consulta para devolver el total de registros (Se toma en cuenta el campo de busqueda);
     $sql = "SELECT id, marca, mostrar, sincronizado FROM equivalencia_marca
       $where";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();
    $filas_filtradas = $busqueda->rowCount();

    //Consulta para devolver los datos a mostrar de los filtros
    $sql = "SELECT id, marca, mostrar, sincronizado FROM equivalencia_marca
        $where 
        $sLimit";
    $busqueda =  $base_de_datos->prepare($sql);
    if( $campo != null ){
        $busqueda->bindParam(':campo', $campo_busqueda, PDO::PARAM_STR);
    }
    $busqueda->setFetchMode(PDO::FETCH_BOTH);
    $busqueda->execute();

    $j = 0;
    $output['data'] = "";
    $output['totalRegistros'] = $filas_totales;
    $output['totalFiltro'] = $filas_filtradas;

    //Si hay registros se mostraran 
    if($filas_totales > 0){
        while( $row = $busqueda->fetch() ){
            $j = $j + 1;
            $id = $row['id'];
            $marca = $row['marca'];
            $mostar = $row['mostrar'];
            $sincronizado = $row['sincronizado'];

            //Se crean las columnas con los datos
            $output['data'] .= "<tr>";
            $output['data'] .= "<td>$id</td>";
            $output['data'] .= "<td>$marca</td>";
            $mostarTexto = ($mostar == 0) ? "NO" : "SI";
            $output['data'] .= "<td>$mostarTexto</td>";
            $output['data'] .= "<td>$sincronizado</td>";

            //Se crea la columna de acciones
            $output['data'] .= '<td>
                    <section class="about_boton">
                    <div class="tex_tabla">
                        <form action="eliminar.php" id="formulario-eliminar-'.$j.'" method="POST" name="formu" class="formulario-eliminar">
                            <input value="'.$id.'" name="id" type="hidden" />    
                            <input type="submit" onclick="eliminado(event,'.$j.')" value="" name="btnEliminar" class="del input" />
                        </form>
                    </div>
                    <div class="tex_tablas">
                        <form action="ver.php" method="POST">
                            <input value="'.$id.'" name="ver" type="hidden" />
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
    //Si no hay registros
    else {
        $output['data'] .= "<tr>";
        $output['data'] .= "<td>Sin resultados</td>";
        $output['data'] .= "</tr>";
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
            $output['paginacion'] .= "<a onclick='getMarcas(1)'  style='cursor: pointer;'>Primero</a>"; 
        }
        for($i = $numeroInicio; $i <= $numeroFinal; $i++){
            if($page == $i){
               $output['paginacion'] .= "<p>" . $i ." </p>";
            }
            else{
                $output['paginacion'] .= "<a onclick='getMarcas($i)'  style='cursor: pointer;'>".$i."</a>";
            }
        }
        if($page != $totalPaginas){
            $output['paginacion'] .= "<a onclick='getMarcas($totalPaginas)'  style='cursor: pointer;'>Último</a>"; 
        }
    }

    //Devuelve el arreglo $output, que tiene 4 valores dentro:
    //$output['filasTotales'] -> Contiene el número de filas totales, sin contar el campo de busqueda
    //$output['filasFiltradas'] -> Contiene el número de filas totales, contando el campo de busqueda
    //$output['filasFiltradas'] -> Contiene el número de filas totales, contando el campo de busqueda
    //$output['data'] -> Contiene el código HTML para crear la tabla de las marcas
    //$output['paginacion'] -> Contiene el código HTML para la paginación de la tabla
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>