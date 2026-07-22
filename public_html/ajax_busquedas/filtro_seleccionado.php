<?php 
    if( isset( $_POST['codigo'] ) ){
        include("./../config/conexion.php");
        include('./../funciones/codigo_existe_en_especificaciones.php');
        $rann = date('H:i:s');

        $codigo = $_POST['codigo'];

        $sql = "SELECT boletin, instalacion, codigo_barra FROM filtro_codificacion WHERE codigo = :codigo and deleted_at is null ";
        $consulta = $base_de_datos->prepare($sql);
        $consulta->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();

     // Obtener el resultado de la consulta
       $registro_codificacion = $consulta->fetch();

     // Obtener el valor del campo "instalacion"
      $boletin = $registro_codificacion['boletin'];
       $instalacion = $registro_codificacion['instalacion'];
        $codigo_barra = $registro_codificacion['codigo_barra'];
     

      $output['boletin'] = $boletin;
       $output['instalacion'] = $instalacion;
        $output['codigo_barra'] = $codigo_barra;

        $resultado = json_decode(queTablaCodigo($codigo, $base_de_datos));
        $output = [];
        $output['carrusel'] = "";
        $output['titulo'] = "";
        

        $output['especificaciones'] = "";
        if( $resultado == 'aireautomotriz' ){
            $sql = "SELECT e_a.tipo, f_c.filtracion, f_c.und_empaque, e_a.diametroext1, e_a.diametroext2, e_a.diametroint1, e_a.diametroint2, e_a.altura, e_a.detalle1, e_a.detalle2, e_a.imagen, e_a.imagen1, e_a.imagen2 FROM espec_aireautomotriz as e_a
                JOIN filtro_codificacion as f_c ON f_c.id = e_a.id_codigo
                WHERE ( e_a.codigo = :codigo ) and ( e_a.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_automotriz = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Aire Automotriz  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .= "</tr></thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_automotriz['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_automotriz['filtracion'] != null && $reg_aire_automotriz['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_automotriz['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_automotriz['und_empaque'] != null && $reg_aire_automotriz['und_empaque'] != "N/D" && $reg_aire_automotriz['und_empaque'] != 0 ) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_automotriz['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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

            if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            
            
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_automotriz['imagen'];
            $imagen1 = $reg_aire_automotriz['imagen1'];
            $imagen2 = $reg_aire_automotriz['imagen2'];
        }
        else if( $resultado == 'aireindustrial' ){
            $sql = "SELECT e_a.tipo, f_c.filtracion, f_c.und_empaque, e_a.diametroext1, e_a.diametroext2, e_a.diametroint1, e_a.diametroint2, e_a.altura, e_a.detalle1, e_a.detalle2, e_a.imagen, e_a.imagen1, e_a.imagen2 FROM espec_aireindustrial as e_a
                        JOIN filtro_codificacion as f_c ON f_c.id = e_a.id_codigo
                        WHERE ( e_a.codigo = :codigo ) and ( e_a.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();
            
            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Aire Industrial  ". $codigo . "</h1>";

            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque'] != "N/D" && $reg_aire_industrial['und_empaque'] != 0 ) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
             if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];

        }
        else if( $resultado == 'combustiblelinea' ){
          $sql = "SELECT e_c.tipo, f_c.filtracion, f_c.und_empaque, e_c.diametroext, r_e.codigo as nombre_rosca_entrada, r_s.codigo as nombre_rosca_salida, p_e.codigo as nombre_pulgada_entrada, p_s.codigo as nombre_pulgada_salida, e_c.altura, e_c.entrada, e_c.salida, e_c.detalle1, e_c.detalle2, e_c.imagen, e_c.imagen1, e_c.imagen2 FROM espec_combustiblelinea as e_c
                            JOIN filtro_codificacion as f_c ON f_c.id = e_c.id_codigo
                            LEFT JOIN roscas as r_e ON e_c.id_rosca_entrada = r_e.id
                            LEFT JOIN roscas as r_s ON e_c.id_rosca_salida = r_s.id
                            LEFT JOIN pulgadas as p_e ON e_c.id_pulgada_entrada = p_e.id
                            LEFT JOIN pulgadas as p_s ON e_c.id_pulgada_salida = p_s.id
                            WHERE ( e_c.codigo = :codigo ) and ( e_c.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Combustible en Linea  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque'] != "N/D" && $reg_aire_industrial['und_empaque'] != 0 ) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
           if (!empty($reg_aire_industrial['nombre_rosca_entrada'])) {
                $mostrar_entrada = $reg_aire_industrial['nombre_rosca_entrada'];
            }
            else if (!empty($reg_aire_industrial['nombre_pulgada_entrada'])) {
                $mostrar_entrada = $reg_aire_industrial['nombre_pulgada_entrada'];
            }
             else {
                $mostrar_entrada = number_format($reg_aire_industrial['entrada'], 2, ",", ".") . " mm";
            }
            
            $output['especificaciones'] .= "<td>" . $mostrar_entrada . "</td>";
            $output['especificaciones'] .= "</tr>";

            // --- SALIDA ---
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Salida:</td>";
            
            if (!empty($reg_aire_industrial['nombre_rosca_salida'])) {
                $mostrar_salida = $reg_aire_industrial['nombre_rosca_salida'];
            } else  if (!empty($reg_aire_industrial['nombre_pulgada_salida'])) {
                $mostrar_salida = $reg_aire_industrial['nombre_pulgada_salida'];
            } else {
                $mostrar_salida = number_format($reg_aire_industrial['salida'], 2, ",", ".") . " mm";
            }
            
            $output['especificaciones'] .= "<td>" . $mostrar_salida . "</td>";
            $output['especificaciones'] .= "</tr>";
             if ($reg_aire_industrial['detalle1'] != null && $reg_aire_industrial['detalle1']!= "N/D") { 
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Detalle 1:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['detalle1'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            }
             if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            $output['especificaciones'] .= "</tbody></table>";

            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];

        }
        else if( $resultado == 'elemento' ){

            $sql = "SELECT e_e.tipo, f_c.filtracion, f_c.und_empaque, e_e.diametroext1, e_e.diametroint1, e_e.diametroint2, e_e.altura, e_e.detalle1, e_e.detalle2, e_e.imagen, e_e.imagen1, e_e.imagen2 FROM espec_elemento as e_e
                                                JOIN filtro_codificacion as f_c ON f_c.id = e_e.id_codigo
                                                WHERE ( e_e.codigo = :codigo ) and ( e_e.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Elemento  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque'] != "N/D" && $reg_aire_industrial['und_empaque'] != 0 ) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
            if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            $output['especificaciones'] .= "</tbody></table>";
        
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];

        }
        else if( $resultado == 'panel' ){
            $sql = "SELECT e_p.tipo, f_c.filtracion, f_c.und_empaque, e_p.largo, e_p.ancho, e_p.altura, e_p.detalle1, e_p.detalle2, e_p.imagen, e_p.imagen1, e_p.imagen2 FROM espec_panel as e_p
            JOIN filtro_codificacion as f_c ON f_c.id = e_p.id_codigo
            WHERE ( e_p.codigo = :codigo ) and ( e_p.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Panel  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque']!= "N/D" && $reg_aire_industrial['und_empaque'] != 0) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
             if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            $output['especificaciones'] .= "</tbody></table>";
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
           
        }
      else if( $resultado == 'cabina' ){
            $sql = "SELECT e_p.tipo, f_c.filtracion, f_c.und_empaque, e_p.largo, e_p.ancho, e_p.altura, e_p.detalle1, e_p.detalle2, e_p.imagen, e_p.imagen1, e_p.imagen2 FROM espec_cabina as e_p
            JOIN filtro_codificacion as f_c ON f_c.id = e_p.id_codigo
            WHERE ( e_p.codigo = :codigo ) and ( e_p.deleted_at is null ) and ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Filtro de Cabina  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque']!= "N/D" && $reg_aire_industrial['und_empaque'] != 0) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
            if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
                $output['especificaciones'] .= "</tr>";
            }
            
            $output['especificaciones'] .= "</tbody></table>";
            $imagen = $reg_aire_industrial['imagen'];
            $imagen1 = $reg_aire_industrial['imagen1'];
            $imagen2 = $reg_aire_industrial['imagen2'];
           
        }
       

        else if( $resultado == 'sellado' ){
            $sql = "SELECT e_s.*, f_c.filtracion, f_c.und_empaque, r.codigo as nombre_rosca 
            FROM espec_sellado as e_s
            JOIN filtro_codificacion as f_c ON f_c.id = e_s.id_codigo
            LEFT JOIN roscas as r ON e_s.id_rosca = r.id
            WHERE ( e_s.codigo = :codigo ) 
              AND ( e_s.deleted_at is null ) 
              AND ( f_c.deleted_at is null )";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $reg_aire_industrial = $seleccionado->fetch();

            $output['titulo'] = "<h1 class='titulo_bold_ms rojoweb text-uppercase'>Filtro de Sellado  ". $codigo . "</h1>";
            
            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro" ">' . $codigo . '</th>';
            $output['especificaciones'] .="</tr> </thead>";
            $output['especificaciones'] .= "<tbody><tr>";
            $output['especificaciones'] .= "<td>Tipo:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['tipo'] . "</td>";
            $output['especificaciones'] .= "</tr>";
            if ($reg_aire_industrial['filtracion'] != null && $reg_aire_industrial['filtracion']!= "N/D") {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Filtración:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['filtracion'] . " </td>";
                $output['especificaciones'] .= "</tr>";
            }
            if ($reg_aire_industrial['und_empaque'] != null && $reg_aire_industrial['und_empaque'] != "N/D" && $reg_aire_industrial['und_empaque'] != 0) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Unidades de Empaque:</td>";
                $output['especificaciones'] .= "<td>" .  $reg_aire_industrial['und_empaque'] . " unidades</td>";
                $output['especificaciones'] .= "</tr>";
            }
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>ø ext1:</td>";
            $output['especificaciones'] .= "<td>" . number_format($reg_aire_industrial['diametroext'],2,",",".") . " mm</td>";
            $output['especificaciones'] .= "</tr>";
            $output['especificaciones'] .= "<tr>";
            
            if (!empty($reg_aire_industrial['nombre_rosca'])) {
                $etiqueta_dinamica = "Rosca:";
                $valor_dinamico = $reg_aire_industrial['nombre_rosca'];
            } else {
                $etiqueta_dinamica = "ø int1:";
                $valor_dinamico = $reg_aire_industrial['diametroint'] . " mm";
            }

            // Ahora lo metemos en tu tabla
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>" . $etiqueta_dinamica . "</td>";
            $output['especificaciones'] .= "<td>" . $valor_dinamico . "</td>";
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
            if($reg_aire_industrial['apertura'] != null ){
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Apertura</td>";
                $output['especificaciones'] .= "<td>" . $reg_aire_industrial['apertura'] . "</td>";
                $output['especificaciones'] .= "</tr>";
            }
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
             if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
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
            
            $output['titulo'] = "<h1 class='titulo_bold_ms  rojoweb text-uppercase'>Fluidos  ". $codigo . "</h1>";

            $output['especificaciones'] .= "<table class='table table-borderless table-custom table-equivalencias'>";
            $output['especificaciones'] .= '<thead class="text-uppercase"><tr><th class="header-negro" >especificaciones</th>';
            $output['especificaciones'] .= '<th class="header-negro">' . $codigo . '</th>'  ;
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

              if ($reg_aire_industrial['etilenglicol'] != null) {  
            $output['especificaciones'] .= "<tr>";
            $output['especificaciones'] .= "<td>Etilenglicol:</td>";
            $output['especificaciones'] .= "<td>" . $reg_aire_industrial['etilenglicol'] . " %</td>";
            $output['especificaciones'] .= "</tr>";
             }
             
            if ($codigo_barra !== null && $codigo_barra !== '') {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td>Código de Barras:</td>";
                $output['especificaciones'] .= "<td class='barcode-cell py-2' data-barcode-value='{$codigo_barra}'></td>";
                $output['especificaciones'] .= "</tr>";
            }
             if ($instalacion != null || $boletin != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2' class='Roboto-Light  text-uppercase'><b>Información Adicional</b></td>";
                $output['especificaciones'] .= "</tr>";
            }

            if ($boletin != null) {
              $output['especificaciones'] .= "<tr>";
              $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/boletin/".$boletin.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Boletín Informativo</a></td>";
              $output['especificaciones'] .= "</tr>";
            }

            if ($instalacion != null) {
                $output['especificaciones'] .= "<tr>";
                $output['especificaciones'] .= "<td colspan='2'><a href='./../../informacion_adicional/instalacion/".$instalacion.".pdf' target='_blank' class=''><i class='bx bx-download'></i> Instrucción de Instalación</a></td>";
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




        $base_path = $_SERVER['DOCUMENT_ROOT'] . '/images/fichas-filtros/web/';
        $rann = rand(); // Para evitar el caché del navegador

        $num_imagenes = 0;
        // Verifica la existencia de cada imagen y define sus rutas
        $comprobar_imagen = $base_path . $imagen . '.jpg';
        $mostrar_imagen = "./../../images/fichas-filtros/web/$imagen.jpg";
        $num_imagenes += file_exists($comprobar_imagen) ? 1 : 0; 

        $comprobar_imagen1 = $base_path . $imagen1 . '.jpg';
        $mostrar_imagen1 = "./../../images/fichas-filtros/web/$imagen1.jpg";
        $num_imagenes += file_exists($comprobar_imagen1) ? 1 : 0; 

        $comprobar_imagen2 = $base_path . $imagen2 . '.jpg';
        $mostrar_imagen2 = "./../../images/fichas-filtros/web/$imagen2.jpg";
        $num_imagenes += file_exists($comprobar_imagen2) ? 1 : 0; 

        $output['carrusel'] .= "<div class='container-all'>";  

        if($num_imagenes > 1){
            if( file_exists( $comprobar_imagen )){
                $output['carrusel'] .= "<input type='radio' id='1' name='image-slide_carrusel_prod' hidden/>";
            }
            if( file_exists( $comprobar_imagen1 )){
                $output['carrusel'] .= "<input type='radio' id='2' name='image-slide_carrusel_prod' hidden/>"; 
            }
            if( file_exists( $comprobar_imagen2 )){
                $output['carrusel'] .= "<input type='radio' id='3' name='image-slide_carrusel_prod' hidden/>";
            }
        }

            if($num_imagenes > 1){
                $output['carrusel'] .= "<div class='slide_carrusel_prod'>";
            }

                if( file_exists ( $comprobar_imagen )){
                   
                    $output['carrusel'] .= "<div class='item-slide_carrusel_prod'>
                                <img src='$mostrar_imagen?t=<?php echo $rann?>' alt='Filtro de ". $resultado . " " . $codigo ."' class='imagen' data='$mostrar_imagen?t=<?php echo $rann?>'   data='$mostrar_imagen?t=<?php echo $rann?>'
            data-bs-toggle='modal' 
            data-bs-target='#zoomModal'
            onclick='document.getElementById(\"modalImage\").src=this.src;' >
                            </div>";
                }
                if( file_exists ( $comprobar_imagen1 )){
                    $output['carrusel'] .= "<div class='item-slide_carrusel_prod'>
                                <img src='$mostrar_imagen1?t=<?php echo $rann?>' alt='Filtro de ". $resultado . " " . $codigo ."' class='imagen' data='$mostrar_imagen1?t=<?php echo $rann?>'  data='$mostrar_imagen1?t=<?php echo $rann?>'
            data-bs-toggle='modal' 
            data-bs-target='#zoomModal'
            onclick='document.getElementById(\"modalImage\").src=this.src;' >
                            </div>";
                }
                if( file_exists ( $comprobar_imagen2 )){
                $output['carrusel'] .= "<div class='item-slide_carrusel_prod'>
                                <img src='$mostrar_imagen2?t=<?php echo $rann?>' alt='Filtro de ". $resultado . " " . $codigo ."' class='imagen' data='$mostrar_imagen2?t=<?php echo $rann?>' data='$mostrar_imagen2?t=<?php echo $rann?>'
            data-bs-toggle='modal' 
            data-bs-target='#zoomModal'
            onclick='document.getElementById(\"modalImage\").src=this.src;' >
                            </div>";
                }
                if( $num_imagenes == 0 ){
                    $output['carrusel'] .= "<div class='item-slide_carrusel_prod'>
                                <img src='./../../images/fichas-filtros/web/no-img.jpg' alt='' class='imagen' data='./../images/fichas-filtros/web/no-img.jpg' >
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
                            <img src='$mostrar_imagen?t=<?php echo $rann?>' alt='Carrusel 1'  >
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

if ($num_imagenes > 1) {

    // Definimos los keyframes dinámicamente
    $keyframes = "@keyframes autoplay {";
    
    // Si hay 2 imágenes (0 y 1), se mueve hasta el 100% del carrusel (slide 2)
    if ($num_imagenes == 2) {
        $keyframes .= "
            50% { transform: translate3d(calc(-100% * 0), 0, 0); }
            100% { transform: translate3d(calc(-100% * 1), 0, 0); }
        ";
    } 
    // Si hay 3 imágenes (0, 1 y 2), se mueve hasta el 200% del carrusel (slide 3)
    elseif ($num_imagenes >= 3) {
        $keyframes .= "
            33% { transform: translate3d(calc(-100% * 0), 0, 0); }
            66% { transform: translate3d(calc(-100% * 1), 0, 0); }
            100% { transform: translate3d(calc(-100% * 2), 0, 0); }
        ";
    }
    
    $keyframes .= "}";

    // Añade los keyframes al HTML dentro de una etiqueta <style>
    $output['carrusel'] .= "<style>{$keyframes}</style>";
    
}


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

             }

         $sql = "SELECT a_t.aplicacion, a_m.marca, a_v.modelo, a_v.ano, a.id_tipo, a.id_marca, a.id_vehiculo, a_v.cilindrada FROM aplicacion as a
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
        $output['aplicacion'] .= "<table class='table table-sm table-borderless table-custom table-codigos-web'>";
        $output['aplicacion'] .= "<thead class='text-uppercase'>"; 
        if( $seleccionado->rowCount() > 0 ){
              $output['aplicacion'] .= "<tr>"; 
            $output['aplicacion'] .= "<th class='tilt_blanco' colspan='5'><b>Aplicaciones</b></th>";
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
             $cilindrada = $reg_aplicacion['cilindrada'];
             $ano = $reg_aplicacion['ano'];

            $output['aplicacion'] .= "<tr>"; 
            if($aplicacion != $reg_aplicacion['aplicacion']){
                $output['aplicacion'] .= "<td><b class='Roboto-Bold'>$aplicacion_colocar</b></td>";
            }
            else {
                $output['aplicacion'] .= "<td ></td>";
            }
            if($aplicacion_marca != $reg_aplicacion['marca']){
                $output['aplicacion'] .= "<td ><a href='./../busqueda_por_aplicacion/index.php?aplic=$id_tipo&marca=$id_marca' class='link'>".$aplicacion_colocar_marca."</a></td>";
            }
            else {
                $output['aplicacion'] .= "<td ></td>";
            }
            $output['aplicacion'] .= "<td ><a href='./../busqueda_por_aplicacion/index.php?aplic=$id_tipo&marca=$id_marca&vehic=$id_vehiculo' class='link'>$aplicacion_vehiculo</a></td>";
            if ($cilindrada != null && $cilindrada!= "N/D") {    
            $output['aplicacion'] .= "<td class='apli_cili'>$cilindrada</td>";
             } else {  $output['aplicacion'] .= "<td ></td>";  }
             if ($ano != null && $ano!= "N/D") {    
            $output['aplicacion'] .= "<td class='apli'>$ano</td>";
             } else {
              $output['aplicacion'] .= "<td class='apli'></td>"; 
               }
            $output['aplicacion'] .= "</tr>";  
            $aplicacion = $reg_aplicacion['aplicacion']; 
            $aplicacion_marca =  $reg_aplicacion['marca'];         
        }
        $output['aplicacion'] .= "</tbody>";
        $output['aplicacion'] .= "</table>";

        $sql = "SELECT f_e.marca, f_e.codigo_marca FROM filtro_equivalencia AS f_e
                JOIN equivalencia_marca AS e_m ON e_m.id = f_e.id_marca
                WHERE (f_e.codigo = :codigo OR f_e.id_codigo = :id_codigo)  AND (f_e.deleted_at IS NULL) 
               AND (e_m.mostrar = 1) ORDER BY f_e.marca, f_e.codigo_marca";
               
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        $seleccionado->bindParam(":id_codigo", $reg_aire_industrial['id_codigo'], PDO::PARAM_STR);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
        $seleccionado->execute();

        $equivalencia = "";
        $output['equivalencia'] = "";
        $output['equivalencia'] .= "<table class='table table-bordered custom-table'>";
        if( $seleccionado->rowCount() > 0 ){
            $output['equivalencia'] .= "<thead class='table-header-custom-eq text-uppercase'>"; 
            $output['equivalencia'] .= "<tr>"; 
            $output['equivalencia'] .= "<th  colspan='2'>Equivalencias </th>";
            $output['equivalencia'] .= "</tr>"; 
            $output['equivalencia'] .= "</thead>"; 
            $output['equivalencia'] .= "<tbody class='table-body-custom-eq'>"; 
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
        $output['equivalencia'] .= "</tbody></table>"; 
    

        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }


    function plantillaEspecificaciones($tabla){

    }
?>
