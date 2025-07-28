<?php 
    if( isset( $_POST['codigo'] ) ){
        include("./../../conexion.php");
        include('./../funciones/codigo_existe_en_especificaciones.php');
        $rann = date('H:i:s');

        $codigo = $_POST['codigo'];

        $resultado = json_decode(queTablaCodigo($codigo, $base_de_datos));
        $output = [];
        $output['carrusel'] = "";
        $output['titulo'] = "";

        $output['especificaciones'] = "";
        if( $resultado == 'aireautomotriz' ){
            $sql = "SELECT * FROM espec_aireautomotriz WHERE ( codigo = :codigo ) and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_automotriz = $seleccionado->fetch();

            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Aire Automotriz</h3>";
            
            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .= "</tr></thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_automotriz['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext1:</td>";
            $output['especificaciones'] .= "<td>" . number_format( $reg_aire_automotriz['diametroext1'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext2:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_automotriz['diametroext2'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_automotriz['diametroint1'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int2:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_automotriz['diametroint2'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Altura:</td>";
            $output['especificaciones'] .= "<td>" . number_format( $reg_aire_automotriz['altura'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
                 if ($reg_aire_automotriz['detalle1'] != null && $reg_aire_automotriz['detalle1']!= "N/D") {
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_automotriz['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
                 }
                   if ($reg_aire_automotriz['detalle2'] != null && $reg_aire_automotriz['detalle2']!= "N/D") {
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_automotriz['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
              }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_automotriz['imagen'];
            $imagen1 = $reg_aire_automotriz['imagen1'];
            $imagen2 = $reg_aire_automotriz['imagen2'];

        }
        else if( $resultado == 'aireindustrial' ){
            $sql = "SELECT * FROM espec_aireindustrial WHERE codigo = :codigo and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();
            
            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Aire Industrial</h3>";

            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext1'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext2:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext2'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroint1'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int2:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroint2'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Altura:</td>";
            $output['especificaciones'] .= "<td>" .number_format( $reg_aire_industrial['altura'],2,",","."). " mm</td>";
            $output['especificaciones'] .= "</tr>";
              if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") {
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
             }
              if ($reg_aire_industrial['detalle2'] != null && $reg_aire_industrial['detalle2']!= "N/D") {
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
             }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
        }
        else if( $resultado == 'combustiblelinea' ){
            $sql = "SELECT * FROM espec_combustiblelinea WHERE codigo = :codigo and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Combustible en Linea</h3>";
            
            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø Altura:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['altura'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Entrada:</td>";
            $output['especificaciones'] .= "<td>" .number_format( $reg_aire_industrial['entrada'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Salida:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['salida'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
             if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") { 
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            }
             if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") { 
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
              }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
        }
        else if( $resultado == 'elemento' ){
            $sql = "SELECT * FROM espec_elemento WHERE codigo = :codigo and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Elemento</h3>";
            
            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext1'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroint1'],2,",",".") . " mm </td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø int2:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroint2'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Altura:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['altura'],2,",",".") . "mm</td>";
            $output['especificaciones'] .= "</tr>";
              if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") {      
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            } if ($reg_aire_industrial['detalle2'] != null && $reg_aire_industrial['detalle2']!= "N/D") {   
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
             }
            $output['especificaciones'] .= "</tbody></table>";
        
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
        }
        else if( $resultado == 'panel' ){
            $sql = "SELECT * FROM espec_panel WHERE codigo = :codigo and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Panel</h3>";
            
            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Largo:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['largo'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Ancho:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['ancho'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Altura:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['altura'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
             if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") {     
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            }  
               if ($reg_aire_industrial['detalle2'] != null && $reg_aire_industrial['detalle2']!= "N/D") {     
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
                } 
            $output['especificaciones'] .= "</tbody></table>";
           
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
        }
        else if( $resultado == 'sellado' ){
            $sql = "SELECT * FROM espec_sellado WHERE ( codigo = :codigo ) and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h3 class='titulo_detalle'>Filtro de Sellado</h3>";
            
            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Rosca:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['diametroint'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Altura:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['altura'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Empacadura:</td>";
            $output['especificaciones'] .= "<td>ø ext: " . number_format($reg_aire_industrial['diametroempext'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td></td>";
            $output['especificaciones'] .= "<td>ø int: " . number_format($reg_aire_industrial['diametroempint'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td></td>";
            $output['especificaciones'] .= "<td>Espesor: " . number_format($reg_aire_industrial['espesoremp'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Valvula de Alivio</td>";
            if($reg_aire_industrial['valvulaal'] == 1){
                $output['especificaciones'] .= "<td>SI</td>";
            }
            if($reg_aire_industrial['valvulaal'] == 0){
                $output['especificaciones'] .= "<td>NO</td>";
            }
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Valvula Anti-Drain</td>";
            if($reg_aire_industrial['valvulaad'] == 1){
                $output['especificaciones'] .= "<td>SI</td>";
            }
            if($reg_aire_industrial['valvulaad'] == 0){
                $output['especificaciones'] .= "<td>NO</td>";
            }
            $output['especificaciones'] .= "</tr>";
             if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") { 
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['detalle2'] != null && $reg_aire_industrial['detalle2']!= "N/D") { 
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
             }
            $output['especificaciones'] .= "</tbody></table>";
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];

        }
        else if( $resultado == 'fluidos' ){
            $sql = "SELECT * FROM espec_fluidos WHERE ( codigo = :codigo ) and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();
            
            $output['titulo'] = "<h3 class='titulo_detalle'>Fluidos</h3>";

            $output['especificaciones'] .= "<table class='vehiculo_detalles_seleccionado'>";
            $output['especificaciones'] .= '<thead class="equivalencias"><tr><td class="equivalencias tilt_blanco">especificaciones</td>';
            $output['especificaciones'] .= '<td class="equivalencias tilt_blanco">' . $codigo . "</td>";
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
                if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") {  
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
              }
               if ($reg_aire_industrial['detalle2'] != null && $reg_aire_industrial['detalle2']!= "N/D") {  
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 2:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle2'] . "</td>";
            $output['especificaciones'] .= "</tr>";
             }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
        }
        else{
            $output['especificaciones'] .= "<p>Sin Resultados</p>";
        }

        $comprobar_imagen = "./../images/fichas-filtros/web/$imagen.jpg";
            $comprobar_imagen1 = "./../images/fichas-filtros/web/$imagen1.jpg";
            $comprobar_imagen2 = "./../images/fichas-filtros/web/$imagen2.jpg";

            $mostrar_imagen = "./../images/fichas-filtros/web/$imagen.jpg";
            $mostrar_imagen1 = "./../images/fichas-filtros/web/$imagen1.jpg";
            $mostrar_imagen2 = "./../images/fichas-filtros/web/$imagen2.jpg";
   
            $num_imagenes = 0;
            $num_imagenes = file_exists ( $comprobar_imagen ) ? $num_imagenes + 1 : $num_imagenes; 
            $num_imagenes = file_exists ( $comprobar_imagen1 ) ? $num_imagenes + 1 : $num_imagenes; 
            $num_imagenes = file_exists ( $comprobar_imagen2 ) ? $num_imagenes + 1 : $num_imagenes;

        $output['carrusel'] .= "<div class='container-all'>";  

        if($num_imagenes > 1){
            if( file_exists( $comprobar_imagen )){
                $output['carrusel'] .= "<input type='radio' id='1' name='image-slide' hidden/>";
            }
            if( file_exists( $comprobar_imagen1 )){
                $output['carrusel'] .= "<input type='radio' id='2' name='image-slide' hidden/>"; 
            }
            if( file_exists( $comprobar_imagen2 )){
                $output['carrusel'] .= "<input type='radio' id='3' name='image-slide' hidden/>";
            }
        }

            if($num_imagenes > 1){
                $output['carrusel'] .= "<div class='slide'>";
            }

                if( file_exists ( $comprobar_imagen )){
                   
                    $output['carrusel'] .= "<div class='item-slide'>
                                <img src='$mostrar_imagen?t=<?php echo $rann?>' alt='Imagen de Filtro 1' class='imagen' data='$mostrar_imagen?t=<?php echo $rann?>' >
                            </div>";
                }
                if( file_exists ( $comprobar_imagen1 )){
                    $output['carrusel'] .= "<div class='item-slide'>
                                <img src='$mostrar_imagen1?t=<?php echo $rann?>' alt='Imagen de Filtro 2' class='imagen' data='$mostrar_imagen1?t=<?php echo $rann?>' >
                            </div>";
                }
                if( file_exists ( $comprobar_imagen2 )){
                $output['carrusel'] .= "<div class='item-slide'>
                                <img src='$mostrar_imagen2?t=<?php echo $rann?>' alt='Imagen de Filtro 3' class='imagen' data='$mostrar_imagen2?t=<?php echo $rann?>' >
                            </div>";
                }
                if( $num_imagenes == 0 ){
                    $output['carrusel'] .= "<div class='item-slide'>
                                <img src='./../images/fichas-filtros/web/no-img.jpg' alt='' class='imagen' data='./../images/fichas-filtros/web/no-img.jpg' >
                            </div>";
                }

            if($num_imagenes > 1){
                $output['carrusel'] .= "</div>";
            }

            $i = 1;
            if($num_imagenes > 1){
                $output['carrusel'] .= "<div class='pagination'>";
                if( file_exists ( $comprobar_imagen )){
                    $output['carrusel'] .= "
                        <label class='pagination-item' for='1'>
                            <img src='$mostrar_imagen?t=<?php echo $rann?>' alt='Carrusel 1' >
                        </label>";
                    $i++;
                }
                if( file_exists ( $comprobar_imagen1 )){   
                $output['carrusel'] .= "<label class='pagination-item' for='2'>
                        <img src='$mostrar_imagen1?t=<?php echo $rann?>' alt='Carrusel 2' >
                    </label>";
                    $i++;
                }
                if( file_exists ( $comprobar_imagen2 )){
                $output['carrusel'] .= "<label class='pagination-item' for='3'>
                        <img src='$mostrar_imagen2?t=<?php echo $rann?>' alt='Carrusel 3' >
                    </label>";
                    $i++;
                }
                $output['carrusel'] .= "</div>";
            }

        $output['carrusel'] .= "</div>";

        if( isset($_POST['codigoVehiculo']) ){
            $sql = "SELECT id_tipo, id_marca, id_vehiculo FROM aplicacion WHERE ( id = :id ) and ( deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":id", $_POST['codigoVehiculo'], PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            while ($row = $seleccionado->fetch()) {
                $idVehiculo = $row['id_vehiculo'];
                $idMarca = $row['id_marca'];
                $idTipo = $row['id_tipo'];
            } 

            $output['carrusel'] .= "<a href='./../busqueda_aplicacion/aplicaciones.php?aplic=$idTipo&marca=$idMarca&vehic=$idVehiculo' ><img src='./../img/tipo/bt_volver.png' alt='' class='bt_busq'></a>";
        }
        else if( isset( $_POST['buscarCodigo'] ) ){
            $codigo = $_POST['codigo'];
            $output['carrusel'] .= "<a href='./../busqueda_codigo/porcodigo.php?codigo=$codigo' ><img src='./../img/tipo/bt_volver.png' alt='' class='bt_busq'></a>";
        }
        else if( isset( $_POST['buscarEspecificacion'] ) ){
            $codigo = $_POST['codigo'];
            $output['carrusel'] .= "<a href='./../busqueda_especificaciones/especificaciones.php' ><img src='./../img/tipo/bt_volver.png' alt='' class='bt_busq'></a>";
        }
        else if( isset( $_POST['buscarTipo'] ) ){
            $codigo = $_POST['codigo'];
            $producto = $_POST['buscarTipo'];

            $sql = "SELECT cat.categoria as categoria, tip.id as id_tipo, tipo as tipo, p.nombre as producto, f_c.clase as clase FROM filtro_codificacion as f_c 
                JOIN tipos as tip ON tip.id = f_c.id_tipo
                JOIN categorias as cat ON cat.id = tip.categoria_id
                JOIN productos as p ON p.id = cat.producto_id
                WHERE codigo = :codigo";
            $cat_tip = $base_de_datos->prepare($sql);
            $cat_tip->bindParam(':codigo', $codigo, PDO::PARAM_STR );
            $cat_tip->setFetchMode( PDO::FETCH_ASSOC );
            $cat_tip->execute();
            $categoria_tipo = $cat_tip->fetch();
            $categoria = $categoria_tipo['categoria'];
            $clase = $categoria_tipo['clase'];
            $producto = $categoria_tipo['producto'];
            $producto_minusculas = strtolower($producto);
            $tipo = $categoria_tipo['tipo'];

            $output['carrusel'] .= "<a href='./../productos/p_$producto_minusculas.php?categoria=$categoria&tipo=$tipo&clase=$clase' ><img src='./../img/tipo/bt_volver.png' alt='' class='bt_busq'></a>";
        }

        $sql = "SELECT a_t.aplicacion, a_m.marca, a_v.modelo, a.id_tipo, a.id_marca, a.id_vehiculo FROM aplicacion as a
                                                            JOIN aplicacion_tipo as a_t ON a.id_tipo = a_t.id
                                                            JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id
                                                            JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id
                                                            WHERE (codigo = :codigo ) and ( a_m.deleted_at is null ) and ( a.deleted_at is null ) and (a_v.deleted_at is null)
                                                            ORDER BY a.id_tipo, a_m.marca, a_v.modelo";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
        $seleccionado->execute();

        $aplicacion = "";
        $aplicacion_marca = "";
        $output['aplicacion'] = "";
        $output['aplicacion'] .= "<table class='apli'>";
        $output['aplicacion'] .= "<thead class='apli'>"; 
        if( $seleccionado->rowCount() > 0 ){
            $output['aplicacion'] .= "<tr>"; 
            $output['aplicacion'] .= "<td class='tilt_blanco'><b>Aplicaciones</b></td>";
            $output['aplicacion'] .= "<td></td>";
            $output['aplicacion'] .= "<td></td>";
            $output['aplicacion'] .= "</tr>"; 
            $output['aplicacion'] .= "</thead>"; 
            $output['aplicacion'] .= "<tbody>";
        }
        while($reg_aplicacion = $seleccionado->fetch()){
            $aplicacion_colocar = substr($reg_aplicacion['aplicacion'], 1);
      //      $aplicacion_colocar = utf8_encode($aplicacion_colocar);
            $aplicacion_colocar_marca = $reg_aplicacion['marca'];
            $aplicacion_vehiculo = $reg_aplicacion['modelo'];

            $id_tipo = $reg_aplicacion['id_tipo'];
            $id_marca = $reg_aplicacion['id_marca'];
            $id_vehiculo = $reg_aplicacion['id_vehiculo'];

            $output['aplicacion'] .= "<tr>"; 
            if($aplicacion != $reg_aplicacion['aplicacion']){
                $output['aplicacion'] .= "<td class='apli'><b>$aplicacion_colocar</b></td>";
            }
            else {
                $output['aplicacion'] .= "<td class='apli'></td>";
            }
            if($aplicacion_marca != $reg_aplicacion['marca']){
                $output['aplicacion'] .= "<td class='apli'><a href='./../busqueda_aplicacion/aplicaciones.php?aplic=$id_tipo&marca=$id_marca' class='link'>".$aplicacion_colocar_marca."</a></td>";
            }
            else {
                $output['aplicacion'] .= "<td class='apli'></td>";
            }
            $output['aplicacion'] .= "<td class='apli'><a href='./../busqueda_aplicacion/aplicaciones.php?aplic=$id_tipo&marca=$id_marca&vehic=$id_vehiculo' class='link'>$aplicacion_vehiculo</a></td>";
            $output['aplicacion'] .= "</tr>";  
            $aplicacion = $reg_aplicacion['aplicacion']; 
            $aplicacion_marca =  $reg_aplicacion['marca'];         
        }
        $output['aplicacion'] .= "</tbody>";
        $output['aplicacion'] .= "</table>";

        $sql = "SELECT marca, codigo_marca FROM filtro_equivalencia as f_e
                                WHERE (codigo = :codigo || id_codigo = :id_codigo) and ( deleted_at is null )
                                ORDER BY f_e.marca, f_e.codigo_marca";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $seleccionado->bindParam(":id_codigo", $reg_aire_industrial['id_codigo'], PDO::PARAM_STR);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
        $seleccionado->execute();

        $equivalencia = "";
        $output['equivalencia'] = "";
        $output['equivalencia'] .= "<table class='eq'>";
        if( $seleccionado->rowCount() > 0 ){
            $output['equivalencia'] .= "<thead class='eq'>"; 
            $output['equivalencia'] .= "<tr>"; 
            $output['equivalencia'] .= "<td class='tilt_blanco'>Equivalencias </td>";
            $output['equivalencia'] .= "<td></td>";
            $output['equivalencia'] .= "</tr>"; 
            $output['equivalencia'] .= "</thead>"; 
        }
        while($reg_equivalencia = $seleccionado->fetch()){
            $equivalencia_colocar = $reg_equivalencia['marca'];
            $equivalencia_codigo_marca = $reg_equivalencia['codigo_marca'];
            $output['equivalencia'] .= "<tr>";
            if($equivalencia != $reg_equivalencia['marca']){
                $output['equivalencia'] .= "<td class='eq'>$equivalencia_colocar </td>";
            }
            else {
                $output['equivalencia'] .= "<td class='eq'></td>";
            } 
            $output['equivalencia'] .= "<td class='eq'>$equivalencia_codigo_marca</td>";
            $output['equivalencia'] .= "</tr>";
            $equivalencia = $reg_equivalencia['marca'];              
        }
        $output['equivalencia'] .= "</table>"; 
    

        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }


    function plantillaEspecificaciones($tabla){

    }
?>