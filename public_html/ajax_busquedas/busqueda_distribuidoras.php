<?php

    if ( isset($_POST['estado']) ) {
        
        include_once('./../config/conexion.php');

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

        $sql = "SELECT d_e.id as id, d.nombre as nombre, d.email as email,  d.direcmaps as direcmaps , d.email2 as email2, d_e.direccion as direccion, d_e.ciudad as ciudad, d_e.estado as estado, d.telefono as telefono, d.telefono2 as telefono2, d.instagram as instagram,  d.video_instagram as video_instagram, d.twitter as twitter, d.facebook as facebook, d_e.principal as principal, d_e.id_padre as id_padre
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
            $direcmaps = $row['direcmaps'];
            

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

                $output['distribuidoras'] .= "<div class='col-12 col-md-6 col-lg-6 mb-5'>";
                $output['distribuidoras'] .= "<div class='card h-100 shadow-lg'>"; // Card con sombra grande
                $output['distribuidoras'] .= "<div class='card-body'>"; // Contenido principal del card 
                // Título del distribuidor
                $output['distribuidoras'] .= "<h5 class='subtito_ms rojoweb' id='nombre_distribuidor'>$nombre</h5>";

                 // Información del distribuidor
                 $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Correo: </b> $email";
                  
                 if ($email2 != null) {
                    $output['distribuidoras'] .= " / $email2</p>";
                } else {
                    $output['distribuidoras'] .= "</p>";

                }

                 $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Teléfono: </b> $telefono";

                 if ($telefono2 != null) {
                    $output['distribuidoras'] .= " / $telefono2</p>";
                } else {
                    $output['distribuidoras'] .= "</p>";

                }
                 $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>Dirección Principal: </b> $direccion, Estado $estado, $ciudad</p>";

                if( $distribuidora_secundarias->rowCount() > 0 ){
                    $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>Distribuye a: </b> $distribuidoras_secun </p>";
                }
                // Redes sociales
                $output['distribuidoras'] .= "<div class='d-flex redes-container justify-content-start mt-3'>";
                if ($facebook != null) {
                    $output['distribuidoras'] .= "<a href='$facebook' class='btn redes_distr face' target='_blank'></a>";
                }
                if ($twitter != null) {
                    $output['distribuidoras'] .= "<a href='$twitter' class='btn redes_distr twi' target='_blank'></a>";
                }
                if ($instagram != null) {
                    $output['distribuidoras'] .= "<a href='$instagram' class='btn redes_distr inst' target='_blank'></a>";
                }
                if ($video_instagram != null) {
                    $output['distribuidoras'] .= "<a href='$video_instagram' class='btn redes_distr video_inst' target='_blank'></a>";
                }
                $output['distribuidoras'] .= "</div>"; // Fin de las redes sociales

                if ($direcmaps != null) {
                    $output['distribuidoras'] .= "<div class='text-center mt-5 mb-3'>";
                    $output['distribuidoras'] .= "<a href='$direcmaps' target='_blank' class='btn btn-icon-gris d-inline-flex align-items-center shadow-sm'>";
                    $output['distribuidoras'] .= "<i class='bx bx-map me-2' style='color: #e2001a;'></i> Ver en Google Maps";
                    $output['distribuidoras'] .= "</a>";
                    $output['distribuidoras'] .= "</div>";
                }
                
                $output['distribuidoras'] .= "</div>"; // Fin del card-body
                $output['distribuidoras'] .= "</div>"; // Fin del card
                $output['distribuidoras'] .= "</div>"; // Fin de la columna
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
                $direcmaps = $row['direcmaps'];

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

                $output['distribuidoras'] .= "<div class='col-12 col-md-6 col-lg-6 mb-5'>";
                $output['distribuidoras'] .= "<div class='card h-100 shadow-lg'>"; // Card con sombra grande
                $output['distribuidoras'] .= "<div class='card-body'>"; // Contenido principal del card 
                // Título del distribuidor
                $output['distribuidoras'] .= "<h5 class='subtito_ms rojoweb' id='nombre_distribuidor'>$nombre</h5>";

                 // Información del distribuidor
                 $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Correo: </b> $email";
                  
                 if ($email2 != null) {
                    $output['distribuidoras'] .= " / $email2</p>";
                } else {
                    $output['distribuidoras'] .= "</p>";

                }

                 $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Teléfono: </b> $telefono";

                 if ($telefono2 != null) {
                    $output['distribuidoras'] .= " / $telefono2</p>";
                } else {
                    $output['distribuidoras'] .= "</p>";

                }
                 $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>Dirección Principal: </b> $direccion, Estado $estado, $ciudad</p>";

                if( $distribuidora_secundarias->rowCount() > 0 ){
                    $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>Distribuye a: </b> $distribuidoras_secun </p>";
                }
                // Redes sociales
                $output['distribuidoras'] .= "<div class='d-flex redes-container justify-content-start mt-3'>";
                if ($facebook != null) {
                    $output['distribuidoras'] .= "<a href='$facebook' class='btn redes_distr face' target='_blank'></a>";
                }
                if ($twitter != null) {
                    $output['distribuidoras'] .= "<a href='$twitter' class='btn redes_distr twi' target='_blank'></a>";
                }
                if ($instagram != null) {
                    $output['distribuidoras'] .= "<a href='$instagram' class='btn redes_distr inst' target='_blank'></a>";
                }
                if ($video_instagram != null) {
                    $output['distribuidoras'] .= "<a href='$video_instagram' class='btn redes_distr video_inst' target='_blank'></a>";
                }
                $output['distribuidoras'] .= "</div>"; // Fin de las redes sociales
                if ($direcmaps != null) {
                    $output['distribuidoras'] .= "<div class='text-center mt-5 mb-3'>";
                    $output['distribuidoras'] .= "<a href='$direcmaps' target='_blank' class='btn btn-icon-gris d-inline-flex align-items-center shadow-sm'>";
                    $output['distribuidoras'] .= "<i class='bx bx-map me-2' style='color: #e2001a;'></i> Ver en Google Maps";
                    $output['distribuidoras'] .= "</a>";
                    $output['distribuidoras'] .= "</div>";
                }
                $output['distribuidoras'] .= "</div>"; // Fin del card-body
                $output['distribuidoras'] .= "</div>"; // Fin del card
                $output['distribuidoras'] .= "</div>"; // Fin de la columna
            }
            else if( $principal == 2 ){
                $ciudad = $row['ciudad'];
                $direccion = $row['direccion'];
                $estado = $row['estado'];
                $facebook = $row['facebook'];
                $twitter = $row['twitter'];
                $instagram = $row['instagram'];
                $video_instagram = $row['video_instagram'];
                $direcmaps = $row['direcmaps'];

                $output['distribuidoras'] .= "<div class='col-12 col-md-6 col-lg-6 mb-5'>";
                $output['distribuidoras'] .= "<div class='card h-100 shadow-lg'>"; // Card con sombra grande
                $output['distribuidoras'] .= "<div class='card-body'>"; // Contenido principal del card
                
                // Título del distribuidor
                $output['distribuidoras'] .= "<h5 class='subtito_ms rojoweb' id='nombre_distribuidor'>$nombre</h5>";
                
                // Información del distribuidor
                $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Correo: </b> $email";
                  
                if ($email2 != null) {
                   $output['distribuidoras'] .= " / $email2</p>";
               } else {
                   $output['distribuidoras'] .= "</p>";

               }

                $output['distribuidoras'] .= "<p class='card-text' id='telefono_distribuidor'><b>Teléfono: </b> $telefono";

                if ($telefono2 != null) {
                   $output['distribuidoras'] .= " / $telefono2</p>";
               } else {
                   $output['distribuidoras'] .= "</p>";

               }
                $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>Dirección Principal: </b> $direccion, Estado $estado, $ciudad</p>";
                $output['distribuidoras'] .= "<p class='card-text' id='direccion_distribuidor'><b>COBERTURA NACIONAL</b></p>";
                
                // Redes sociales
                $output['distribuidoras'] .= "<div class='d-flex redes-container justify-content-start mt-3'>";
                if ($facebook != null) {
                    $output['distribuidoras'] .= "<a href='$facebook' class='btn redes_distr face' target='_blank'></a>";
                }
                if ($twitter != null) {
                    $output['distribuidoras'] .= "<a href='$twitter' class='btn redes_distr twi' target='_blank'></a>";
                }
                if ($instagram != null) {
                    $output['distribuidoras'] .= "<a href='$instagram' class='btn redes_distr inst' target='_blank'></a>";
                }
                if ($video_instagram != null) {
                    $output['distribuidoras'] .= "<a href='$video_instagram' class='btn redes_distr video_inst' target='_blank'></a>";
                }
                $output['distribuidoras'] .= "</div>"; // Fin de las redes sociales
                
                if ($direcmaps != null) {
                    $output['distribuidoras'] .= "<div class='text-center mt-5 mb-3'>";
                    $output['distribuidoras'] .= "<a href='$direcmaps' target='_blank' class='btn btn-icon-gris d-inline-flex align-items-center shadow-sm'>";
                    $output['distribuidoras'] .= "<i class='bx bx-map me-2' style='color: #e2001a;'></i> Ver en Google Maps";
                    $output['distribuidoras'] .= "</a>";
                    $output['distribuidoras'] .= "</div>";
                }
                $output['distribuidoras'] .= "</div>"; // Fin del card-body
                $output['distribuidoras'] .= "</div>"; // Fin del card
                $output['distribuidoras'] .= "</div>"; // Fin de la columna
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
                    $output['paginacion'] .= "<p class='linksp'>" . $i ." </p>";
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
