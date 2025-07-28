<?php

    if ( isset($_POST['estado']) ) {
        
        include_once('./../../conexion.php');

        /*------------RECIBIR EL ESTADO--------------*/ 
        $est = ( $_POST['estado'] != "" ) ? htmlspecialchars( $_POST['estado'] ) : '';

        /*----------------INICIALIZAR LA VARIABLE OUTPUT-------------------*/
        $output = [];
        $output["distribuidoras"] = "";
        $output["cantidad"] = "";
        $output["titulo"] = "";

        /*------------SELECCIONAR LA PAGINA Y DEFINIR EL LIMITE------------*/ 
        $limit = 4;
        $page = isset( $_POST['pagina'] ) ? htmlspecialchars( $_POST['pagina'] ) : 1;
        $inicio = $limit * ($page - 1);
        $sLimit = "LIMIT $inicio, $limit";

        if( $est != '' ){
            $where = 'WHERE ( ( d.deleted_at is null ) and ( d_e.deleted_at is null ) and ( ( d_e.estado = :estado ) or ( d_e.estado = "Nacional" ) ) and ( d.pais = "Venezuela" ) )';
        }
        else {
            $where = 'WHERE ( ( d.deleted_at is null ) and ( d_e.deleted_at is null ) and ( d.pais = "Venezuela" ) and ( d_e.principal != 0 ) )';
        }

        $sql = "SELECT COUNT(*) as cantidad FROM distribuidoras as d 
                        JOIN distribuidora_estado as d_e ON d.id = d_e.id_distribuidora
                        $where";
        $seleccionado = $base_de_datos->prepare($sql);
        if( $est != '' ){
            $seleccionado->bindParam(':estado', $est, PDO::PARAM_STR );
        }
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
        $seleccionado->execute();
        $total = $seleccionado->fetch();
        $numFilasTotales = $total['cantidad'];

        $sql = "SELECT d_e.id as id, d.nombre as nombre, d.email as email, d.email2 as email2, d_e.direccion as direccion, d_e.ciudad as ciudad, d_e.estado as estado, d.telefono as telefono, d.telefono2 as telefono2, d.instagram as instagram,  d.video_instagram as video_instagram, d.twitter as twitter, d.facebook as facebook, d_e.principal as principal, d_e.id_padre as id_padre
                        FROM distribuidoras as d
                        JOIN distribuidora_estado as d_e ON d.id = d_e.id_distribuidora
                        $where
                        ORDER BY d.calificacion desc, d.nombre ASC
                        $sLimit ";
        $seleccionado = $base_de_datos->prepare($sql);
        if( $est != '' ){
            $seleccionado->bindParam(':estado', $est, PDO::PARAM_STR );
        }
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
        $seleccionado->execute();

        while($row = $seleccionado->fetch()){
            $principal = $row['principal'];
            $nombre = $row['nombre'];
            $email = $row['email'];
            $email2 = $row['email2'];
            $telefono = $row['telefono'];
            $telefono2 = $row['telefono2'];
            $facebook = $row['facebook'];
            $twitter = $row['twitter'];
            $instagram = $row['instagram'];
            $video_instagram = $row['video_instagram'];
            

            if( $principal == 0 ){
                $id_padre = $row['id_padre'];
                $sql = "SELECT * FROM distribuidora_estado WHERE id = :id_padre and deleted_at is null ";
                $distribuidora_principal = $base_de_datos->prepare($sql);
                $distribuidora_principal->bindParam(':id_padre', $id_padre, PDO::PARAM_INT);
                $distribuidora_principal->setFetchMode( PDO::FETCH_ASSOC );
                $distribuidora_principal->execute();
                $distribuidora_principal = $distribuidora_principal->fetch();

                $direccion = $distribuidora_principal['direccion'];
                $estado = $distribuidora_principal['estado'];
                $ciudad = $distribuidora_principal['ciudad'];

                $sql = "SELECT estado FROM distribuidora_estado WHERE id_padre = :id_padre and deleted_at is null ";
                $distribuidora_secundarias = $base_de_datos->prepare($sql);
                $distribuidora_secundarias->bindParam(':id_padre', $id_padre, PDO::PARAM_INT);
                $distribuidora_secundarias->setFetchMode( PDO::FETCH_ASSOC );
                $distribuidora_secundarias->execute();
                $distribuidoras_secun = "$estado, ";
                while($row2 = $distribuidora_secundarias->fetch()){
                    $distribuidoras_secun .= $row2['estado'];
                    $distribuidoras_secun .= ", ";
                }
                $distribuidoras_secun = substr_replace($distribuidoras_secun, "", -2);

                $output['distribuidoras'] .= "<div>"; 
                $output['distribuidoras'] .= "<h2 class='n_nombre_distri' id='nombre_distribuidor'>$nombre</h2>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Correo: </b> $email / $email2</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Teléfono: </b> $telefono / $telefono2</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>Direccion Principal: </b> $direccion, Estado $estado, $ciudad</p>";
                if( $distribuidora_secundarias->rowCount() > 0 ){
                    $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>Distribuye a: </b> $distribuidoras_secun </p>";
                }
                 $output['distribuidoras'] .= "<div class='flex_distr'>";
                if( $facebook != null ){
                    $output['distribuidoras'] .= "<a href='$facebook' target='_blank' class='redes_distr face'></a>";
                }
                if( $twitter != null ){
                    $output['distribuidoras'] .= "<a href='$twitter' target='_blank'  class='redes_distr twi'></a>";
                }
                if( $instagram != null ){
                    $output['distribuidoras'] .= "<a href='$instagram' target='_blank'   class='redes_distr inst'></a>";
                }
                   if( $video_instagram != null ){
                    $output['distribuidoras'] .= "<a href='$video_instagram' target='_blank'   class='redes_distr video_inst'></a>";
                }
    
                $output['distribuidoras'] .= "</div>";
                $output['distribuidoras'] .= "</div>";
            }
            else if( $principal == 1 ){
                $ciudad = $row['ciudad'];
                $direccion = $row['direccion'];
                $estado = $row['estado'];
                $id = $row['id'];
                $facebook = $row['facebook'];
                $twitter = $row['twitter'];
                $instagram = $row['instagram'];
                $video_instagram = $row['video_instagram'];

                $sql = "SELECT estado FROM distribuidora_estado WHERE id_padre = :id_padre and deleted_at is null ";
                $distribuidora_secundarias = $base_de_datos->prepare($sql);
                $distribuidora_secundarias->bindParam(':id_padre', $id, PDO::PARAM_INT);
                $distribuidora_secundarias->setFetchMode( PDO::FETCH_ASSOC );
                $distribuidora_secundarias->execute();
                $distribuidoras_secun = "$estado, ";
                while($row2 = $distribuidora_secundarias->fetch()){
                    $distribuidoras_secun .= $row2['estado'];
                    $distribuidoras_secun .= ", ";
                }
                $distribuidoras_secun = substr_replace($distribuidoras_secun, "", -2);

                $output['distribuidoras'] .= "<div>"; 
                $output['distribuidoras'] .= "<h2 class='n_nombre_distri' id='nombre_distribuidor'>$nombre</h2>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Correo: </b> $email / $email2</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Teléfono: </b> $telefono / $telefono2 </p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>Direccion Principal: </b> $direccion, Estado $estado, $ciudad</p>";
                if( $distribuidora_secundarias->rowCount() > 0 ){
                    $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>Distribuye a: </b> $distribuidoras_secun </p>";
                }
                $output['distribuidoras'] .= "<div class='flex_distr'>";
                if( $facebook != null ){
                    $output['distribuidoras'] .= "<a href='$facebook' target='_blank' class='redes_distr face'></a>";
                }
                if( $twitter != null ){
                    $output['distribuidoras'] .= "<a href='$twitter' target='_blank'  class='redes_distr twi'></a>";
                }
                if( $instagram != null ){
                    $output['distribuidoras'] .= "<a href='$instagram' target='_blank'   class='redes_distr inst'></a>";
                }
                    if( $video_instagram != null ){
                    $output['distribuidoras'] .= "<a href='$video_instagram' target='_blank'   class='redes_distr video_inst'></a>";
                }
                $output['distribuidoras'] .= "</div>";
                $output['distribuidoras'] .= "</div>";
            }
            else if( $principal == 2 ){
                $ciudad = $row['ciudad'];
                $direccion = $row['direccion'];
                $estado = $row['estado'];
                $facebook = $row['facebook'];
                $twitter = $row['twitter'];
                $instagram = $row['instagram'];
                $video_instagram = $row['video_instagram'];

                $output['distribuidoras'] .= "<div>"; 
                $output['distribuidoras'] .= "<h2 class='n_nombre_distri' id='nombre_distribuidor'>$nombre</h2>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Correo: </b> $email / $email2</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='telefono_distribuidor'><b>Teléfono: </b> $telefono / $telefono2</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>Direccion Principal: </b> $direccion, Estado $estado, $ciudad</p>";
                $output['distribuidoras'] .= "<p class='info_distri' id='direccion_distribuidor'><b>COBERTURA NACIONAL</b> </p>";
                 $output['distribuidoras'] .= "<div class='flex_distr'>";
                if( $facebook != null ){
                    $output['distribuidoras'] .= "<a href='$facebook' class='redes_distr face'></a>";
                }
                if( $twitter != null ){
                    $output['distribuidoras'] .= "<a href='$twitter' class='redes_distr twi'></a>";
                }
                if( $instagram != null ){
                    $output['distribuidoras'] .= "<a href='$instagram'  class='redes_distr inst'></a>";
                }
                  if( $video_instagram != null ){
                    $output['distribuidoras'] .= "<a href='$video_instagram' target='_blank'   class='redes_distr video_inst'></a>";
                }
                $output['distribuidoras'] .= "</div>";
                 $output['distribuidoras'] .= "</div>";
            }
        }

        $output['paginacion'] = "";

        $numeroInicio = 1;
        if($numFilasTotales > 0){
            $totalPaginas = ceil($numFilasTotales / $limit);

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
                $output['paginacion'] .= "<a onclick='getData(1, `$est`)'  style='cursor: pointer;'>  <<  </a>"; 
                $output['paginacion'] .= "<a onclick='getData($anterior, `$est`)'  style='cursor: pointer;'>  <  </a>"; 
            }

            for($i = $numeroInicio; $i <= $numeroFinal; $i++){
                if($page == $i){
                    $output['paginacion'] .= "<p>" . $i ." </p>";
                }
            }
            if($page != $totalPaginas){
                $siguiente = $page  + 1;
                $output['paginacion'] .= "<a onclick='getData($siguiente, `$est`)'  style='cursor: pointer;'>  >  </a>"; 
                $output['paginacion'] .= "<a onclick='getData($totalPaginas, `$est`)'  style='cursor: pointer;'> >> </a>"; 
            }
        }
        
        echo json_encode($output);
    }
