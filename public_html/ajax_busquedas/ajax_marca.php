<?php 
    if( isset($_POST['id_marca']) && isset($_POST['id_aplicacion']) ){
        include("./../../conexion.php");

        $columnas = ['id_vehiculo','modelo', 'cilindrada', 'ano'];
        $campo = isset( $_POST['campo'] ) ? $_POST['campo'] : null;
        if( $campo != null ){
            $campo_busqueda = "%" . $campo . "%";
        }
        
        $id_aplicacion = isset( $_POST['id_aplicacion'] ) ? $_POST['id_aplicacion'] : null;
        $id_marca = isset( $_POST['id_marca'] ) ? $_POST['id_marca'] : null;
        $page = ( isset( $_POST['pagina'] ) && $_POST['pagina'] >= 0) ? $_POST['pagina'] : 1;
        $limit = isset( $_POST['registros'] ) ? $_POST['registros'] : 10;
        $orden_modelo = isset( $_POST['orden_modelo'] ) ? $_POST['orden_modelo'] : 0;
        $orden_cilindrada = isset( $_POST['orden_cilindrada'] ) ? $_POST['orden_cilindrada'] : 0;
        $orden_ano = isset( $_POST['orden_ano'] ) ? $_POST['orden_ano'] : 0;

        $inicio = $limit * ($page - 1);

        $where = '';

        if($campo != null){
            $where .= "WHERE (a.id_marca = :marca2 and a.id_tipo = :id_tipo) and (a_v.modelo LIKE :campo or a_v.cilindrada LIKE :campo or a_v.ano LIKE :campo)  and (a.deleted_at is null) and (a_v.deleted_at is null)";
           // $where .= "and (a.deleted_at is null) ";
        }
        else{
          //  $where .= " WHERE (a.id_tipo = :id_tipo) and (a.deleted_at is null) ";
            $where .= "WHERE (a.id_marca = :marca2 and a.id_tipo = :id_tipo) and (a.deleted_at is null) and (a_v.deleted_at is null)";
        }

        $seleccionado = $base_de_datos->prepare("SELECT COUNT(DISTINCT a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano) FROM aplicacion as a 
                                                                JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
                                                                WHERE (a.id_marca = :marca2 and a.id_tipo = :id_tipo) and (a.deleted_at is null) and (a_v.deleted_at is null)");
        $seleccionado->bindParam(":marca2", $id_marca, PDO::PARAM_INT);
        $seleccionado->bindParam(":id_tipo", $id_aplicacion, PDO::PARAM_INT);
		$seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
		$seleccionado->execute();
        $m = $seleccionado->fetch();
        $cantidad_fila_total = $m['COUNT(DISTINCT a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano)'];

        $sql = "SELECT COUNT(DISTINCT a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano) FROM aplicacion as a 
                                JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
                                $where";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":marca2", $id_marca, PDO::PARAM_INT);
        $seleccionado->bindParam(":id_tipo", $id_aplicacion, PDO::PARAM_INT);
        if( $campo != null ){
            $seleccionado->bindParam(":campo", $campo_busqueda, PDO::PARAM_STR);
        }
		$seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
		$seleccionado->execute();
        $m = $seleccionado->fetch();
        $cantidad_fila_seleccionada = $m['COUNT(DISTINCT a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano)'];

        $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.modelo ASC, a_v.cilindrada, a_v.ano
            LIMIT $inicio, $limit";
        if( $orden_modelo == 1 ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.modelo ASC
            LIMIT $inicio, $limit";
        }
        if(  $orden_modelo == 2  ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.modelo DESC
            LIMIT $inicio, $limit";
        }
        if( $orden_cilindrada == 1 ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.cilindrada ASC
            LIMIT $inicio, $limit";
        }
        if(  $orden_cilindrada == 2  ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.cilindrada DESC
            LIMIT $inicio, $limit";
        }
        if( $orden_ano == 1 ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.ano ASC
            LIMIT $inicio, $limit";
        }
        if(  $orden_ano == 2  ){
            $sql = "SELECT " . implode(",",$columnas) . " FROM aplicacion as a 
            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo 
            $where
            GROUP BY a.id_vehiculo, a_v.modelo, a_v.cilindrada, a_v.ano
            ORDER BY a_v.ano DESC
            LIMIT $inicio, $limit";
        }

        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":marca2", $id_marca, PDO::PARAM_INT);
        $seleccionado->bindParam(":id_tipo", $id_aplicacion, PDO::PARAM_INT);
        if( $campo != null ){
            $seleccionado->bindParam(":campo", $campo_busqueda, PDO::PARAM_STR);
        }
		$seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
		$seleccionado->execute();

        $output['totalRegistros'] = $cantidad_fila_total;
        $output['totalFiltro'] = $cantidad_fila_seleccionada;
        $output['datos'] = "";
        $output['paginacion'] = "";

        $numeroInicio = 1;


        if($cantidad_fila_seleccionada > 0){
            $totalPaginas = ceil($cantidad_fila_seleccionada / $limit);

            if(($page - 1) > 1){
                $numeroInicio = $page - 1;
            }
    
            $numeroFinal = $numeroInicio + 2;
    
            if($numeroFinal > $totalPaginas){
                $numeroFinal = $totalPaginas;
            }

            if($page != 1){
                $anterior = $page - 1;
                $output['paginacion'] .= "<a onclick='getData(1)'  style='cursor: pointer;'>  <<  </a>"; 
                $output['paginacion'] .= "<a onclick='getData($anterior)'  style='cursor: pointer;'>  <  </a>"; 
            }

            for($i = $numeroInicio; $i <= $numeroFinal; $i++){
                if($page == $i){
                    $output['paginacion'] .= "<p>" . $i ." </p>";
                }
            }
            if($page != $totalPaginas){
                $siguiente = $page  + 1;
                $output['paginacion'] .= "<a onclick='getData($siguiente)'  style='cursor: pointer;'>  >  </a>"; 
                $output['paginacion'] .= "<a onclick='getData($totalPaginas)'  style='cursor: pointer;'> >> </a>"; 
            }
        }

        while ($row = $seleccionado->fetch()) {
            $id_vehiculo = $row['id_vehiculo'];

            $output['datos'] = $output['datos'] . '<tr>';
            $output['datos'] = $output['datos'] . "<td class='busqueda_apli'><a onclick='getRegistro($id_vehiculo,$id_aplicacion,$id_marca)' class='link'>" . $row['modelo'] . "</a></td>";
            $output['datos'] = $output['datos'] . '<td class="busqueda_apli">' . $row['cilindrada'] . '</td>';
            $output['datos'] = $output['datos'] . '<td class="busqueda_apli">' . $row['ano'] . '</td>';
            $output['datos'] = $output['datos'] . '</tr>';
		} 

        if(isset($_POST['regreso'])){
            $output['tipo'] = $id_aplicacion;
            $output['marca'] = $id_marca;
        }

        echo json_encode($output);
    }
?>