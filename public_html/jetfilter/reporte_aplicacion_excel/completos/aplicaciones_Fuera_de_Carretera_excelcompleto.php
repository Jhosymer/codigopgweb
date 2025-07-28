<?php
   
   // mb_internal_encoding('UTF-8');
   
  

        if ( ($tipo == 'todo' ) or ($tipo == 'Carretera' )){
            if ($marcaset == '' ){
		
				$seleccionadoc = $base_de_datos->prepare("SELECT a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_v.motor, a_v.ano, a_m.marca, count(*) 
																							FROM aplicacion as a 
																							JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
																							JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
																							WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null )  
																							GROUP BY a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_m.marca 
																							ORDER BY a_m.marca, a_v.modelo");
				$seleccionadoc->execute();
				while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
					$aplcicacionc []= $filac;
				}
				
				$seleccionadoc_esperac = $base_de_datos->prepare("SELECT a.codigo, a.aplicacion FROM aplicacion as a 
																								JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
																								JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
																								WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null )  and a.id_vehiculo = ? and a.id_marca = ?
																								GROUP BY a.aplicacion, a.codigo");
				
				$seleccionadoc = $base_de_datos->prepare("SELECT a.aplicacion FROM aplicacion as a 
															WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null )  
															GROUP BY a.aplicacion
															ORDER BY a.aplicacion");


               $seleccionadoc->execute();
				while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
					if( $filac['aplicacion'] == "Hidraulico primario" ){
						$filac['aplicacion'] = "Hidráulico primario";
					}
					else if($filac['aplicacion'] == "Hidraulico"){
						$filac['aplicacion'] = "Hidráulico";
					}
					else if($filac['aplicacion'] == "Hidraulico secundario"){
						$filac['aplicacion'] = "Hidráulico secundario";
					}
					$nombres_aplicacionesc []= $filac;
				}
				
				$seleccionadoc = $base_de_datos->prepare("SELECT count(DISTINCT a.aplicacion) FROM aplicacion as a 
														WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) ");
				$seleccionadoc->execute();
				while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
					$numero_aplicacionesc []= $filac;
				}
?>
                <head>
    <meta charset="UTF-8">
</head>


<table>
    <thead>
        <tr><th><h1 style=' color: #E2001A;'>Aplicacion Fuera de carretera
 </h1></th></tr>
    </thead>
</table>
    
    
    <table style=" width:100%; border-collapse: collapse; text-align: left;">
              <thead style="background-color: #C2C2C2">
                <tr>
                    <th style="border: 1px solid #000;" ><h6>Modelo</h6></th>
                    <th style="border: 1px solid #000;" ><h6>Motor</h6></th>
                    <th style="border: 1px solid #000;" ><h6>Año</h6></th>
                    <?php 
                        for($i = 0; $i < $numero_aplicacionesc[0]['count(DISTINCT a.aplicacion)']; $i++){
                            ?>
                                <th style="border: 1px solid #000;" ><h6><?php echo $nombres_aplicacionesc[$i]['aplicacion']; ?></h6></th>
                            <?php
                        }
                    ?>
                </tr>
            </thead>
    
            <tbody>
            <?php 
                        $marca = "";
                        $j = 0;
                        foreach($aplcicacionc as $aplc){
                            if($marca != $aplc['marca']){
                                ?>
                                    <tr><td colspan="<?php echo ($numero_aplicacionesc[0]['count(DISTINCT a.aplicacion)'] + 3); ?>"  style="border: 1px solid #000; background-color: #DDD;"><h6><?php echo $aplc['marca']; ?></h6></td></tr>
                                <?php
                                $marca = $aplc['marca'];
                            }
                            ?>
                            <tr>
                                <td style="border: 1px solid #000;" >
                                    <h6><?php echo $aplc['modelo'] ?></h6>
                                </td>
                                <td style="border: 1px solid #000;" >
                                    <h6><?php echo $aplc['motor'] ?></h6>
                                </td>
                                <td style="border: 1px solid #000;" >
                                    <h6><?php echo $aplc['ano'] ?></h6>
                                </td>
                                    <?php 
                                        unset($aplcicacionc_filtro);
                                        unset($aplcicacionc_filtro_2);
                                        
                                       // $id_vehiculoc = $aplc['id_vehiculo'];
                                       // $id_marcamarca = $aplc['id_marca'];
                                            $seleccionadoc_esperac->execute([ $aplc['id_vehiculo'], $aplc['id_marca'] ]);
                                            while ($filac = $seleccionadoc_esperac->fetch(PDO::FETCH_ASSOC)) {
                                                $aplcicacionc_filtro []= $filac;
                                            }
     
                                            $x = 0;
                                            $k = 0;
                                            foreach($aplcicacionc_filtro as $a_f){
                                                $aplcicacionc_filtro_2[$x] = $a_f['aplicacion'];
                                                $codigo_filtro_2c[$x] = $a_f['codigo'];
                                                $x++;
                                            }
            
                            foreach ($nombres_aplicacionesc as $nombre) {
                                        $matching_codigos = []; // Array para almacenar códigos coincidentes
                                    
                                        // Recorre aplcicacionc_filtro_2 y busca coincidencias
                                        foreach ($aplcicacionc_filtro_2 as $index => $aplcicacion) {
                                            if (isset($aplcicacion) && strcasecmp($nombre['aplicacion'], $aplcicacion) == 0) {
                                                $matching_codigos[] = $codigo_filtro_2c[$index]; // Add matching codigo to array
                                            }
                                        }
                                    
                                        ?>
                                        <td style="border: 1px solid #000;">
                                            <?php
                                            // Display matching codigos in the same <td>
                                            foreach ($matching_codigos as $codigo) {
                                                ?>
                                                <h6><?php echo $codigo; ?></h6>
                                                <?php
                        }
                        ?>
                    </td>
                    <?php
                }
                                    ?>
                            </tr>
                    <?php
                            }
                    ?>
            </tbody>
        </table>
				
                
<?php
            }else {
    
		
				
				$seleccionadoc = $base_de_datos->prepare("SELECT a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_v.motor, a_v.ano, a_m.marca, count(*) 
																							FROM aplicacion as a 
																							JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
																							JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
																							WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null )  and  a.id_marca = '$marcaset'
																							GROUP BY a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_m.marca 
																							ORDER BY a_m.marca, a_v.modelo");
				$seleccionadoc->execute();
				while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
					$aplcicacionc []= $filac;
				}

               
				
				$seleccionadoc_esperac = $base_de_datos->prepare("SELECT a.codigo, a.aplicacion FROM aplicacion as a 
																								JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
																								JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
																								WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null )   and a.id_vehiculo = ? and a.id_marca = ?
																								GROUP BY a.aplicacion, a.codigo");
				
				$seleccionadoc = $base_de_datos->prepare("SELECT a.aplicacion FROM aplicacion as a 
															WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null )   and  a.id_marca = '$marcaset'
															GROUP BY a.aplicacion
															ORDER BY a.aplicacion");

					$seleccionadoc->execute();
					while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
						/*if( $filac['aplicacion'] == "Hidraulico primario" ){
							$filac['aplicacion'] = "Hidráulico primario";
						}
						else if($filac['aplicacion'] == "Hidraulico"){
							$filac['aplicacion'] = "Hidráulico";
						}
						else if($filac['aplicacion'] == "Hidraulico secundario"){
							$filac['aplicacion'] = "Hidráulico secundario";
						}*/
						$nombres_aplicacionesc []= $filac;
					}

					$seleccionadoc = $base_de_datos->prepare("SELECT count(DISTINCT a.aplicacion) FROM aplicacion as a 
															WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and a.id_marca = '$marcaset' ");
					$seleccionadoc->execute();
					while ($filac = $seleccionadoc->fetch(PDO::FETCH_ASSOC)) {
						$numero_aplicacionesc []= $filac;
					}
                    
                    $consulta = $base_de_datos->prepare("SELECT * FROM `aplicacion` WHERE id_tipo = 3 and id_marca = :marcaset and updated_at is null");
                    $consulta->bindParam(':marcaset', $marcaset, PDO::PARAM_STR);
                    $consulta->execute();
                
                    // Obtener el resultado
                    $resultado = $consulta->fetchColumn();
    
                    if ($resultado > 0) {
                 
                
                    ?>
                    <head>
        <meta charset="UTF-8">
    </head>

    <table>
    <thead>
        <tr><th><h1 style=' color: #E2001A;'>Aplicacion Fuera de carretera
</h1></th></tr>
    </thead>
</table>
    
    
<table style=" width:100%; border-collapse: collapse; text-align: left;">
    <thead style="background-color: #C2C2C2">
        <tr>
            <th style="border: 1px solid #000;"><h6>Modelo</h6></th>
            <th style="border: 1px solid #000;"><h6>Motor</h6></th>
            <th style="border: 1px solid #000;"><h6>Año</h6></th>
            <?php 
            for($i = 0; $i < $numero_aplicacionesc[0]['count(DISTINCT a.aplicacion)']; $i++){
                ?>
                <th style="border: 1px solid #000;"><h6><?php echo $nombres_aplicacionesc[$i]['aplicacion']; ?></h6></th>
                <?php
            }
            ?>
        </tr>
    </thead>

    <tbody>
        <?php 
        $marca = "";
        $j = 0;
        foreach($aplcicacionc as $aplc){
            if($marca != $aplc['marca']){
                ?>
                <tr><td colspan="<?php echo ($numero_aplicacionesc[0]['count(DISTINCT a.aplicacion)'] + 3); ?>" style="border: 1px solid #000; background-color: #DDD;"><h6><?php echo $aplc['marca']; ?></h6></td></tr>
                <?php
                $marca = $aplc['marca'];
            }
            ?>
            <tr>
                <td style="border: 1px solid #000;">
                    <h6><?php echo $aplc['modelo'] ?></h6>
                </td>
                <td style="border: 1px solid #000;">
                    <h6><?php echo $aplc['motor'] ?></h6>
                </td>
                <td style="border: 1px solid #000;">
                    <h6><?php echo $aplc['ano'] ?></h6>
                </td>
                <?php 
                unset($aplcicacionc_filtro);
                unset($aplcicacionc_filtro_2);

                $seleccionadoc_esperac->execute([$aplc['id_vehiculo'], $aplc['id_marca']]);
                while ($filac = $seleccionadoc_esperac->fetch(PDO::FETCH_ASSOC)) {
                    $aplcicacionc_filtro []= $filac;
                }

                $j = 0;
                $k = 0;
                foreach($aplcicacionc_filtro as $a_f){
                    $aplcicacionc_filtro_2[$j] = $a_f['aplicacion'];
                    $codigo_filtro_2c[$j] = $a_f['codigo'];
                    $j++;
                }

                  foreach ($nombres_aplicacionesc as $nombre) {
                    $matching_codigos = []; // Array para almacenar códigos coincidentes
                
                    // Recorre aplcicacionc_filtro_2 y busca coincidencias
                    foreach ($aplcicacionc_filtro_2 as $index => $aplcicacion) {
                        if (isset($aplcicacion) && strcasecmp($nombre['aplicacion'], $aplcicacion) == 0) {
                            $matching_codigos[] = $codigo_filtro_2c[$index]; // Add matching codigo to array
                        }
                    }
                
                    ?>
                    <td style="border: 1px solid #000;">
                        <?php
                        // Display matching codigos in the same <td>
                        foreach ($matching_codigos as $codigo) {
                            ?>
                            <h6><?php echo $codigo; ?></h6>
                            <?php
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
                    
                    
        <?php

} else { ?> 
    <table>
    <thead>
        <tr><th><h3 >No Hay Registro En Aplicacion Fuera de carretera
 Para la Marca Seleccionada </h3></th></tr>
    </thead>
</table> <?php
}

}
 }
    
?>

 

