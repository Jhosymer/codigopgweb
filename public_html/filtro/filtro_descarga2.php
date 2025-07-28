<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
    date_default_timezone_set('America/Caracas');
    $rann = date('H:i:s');
    if( isset($_GET['codigo']) ){
      ob_start();
    }

    $codigo = $_GET['codigo'];

    include('./../../conexion.php');
    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }

    $sql = "SELECT * FROM filtro_codificacion WHERE codigo = :codigo and ( deleted_at is null ) ";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $filtro = $seleccionado->fetch();
    $clase = $filtro['clase'];
    $barra= $filtro['codigo_barra'];
    $idtipo= $filtro['id_tipo'];

    switch($clase){
        case "aireautomotriz":
            $sql = 'SELECT * FROM espec_aireautomotriz WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case "aireindustrial":
            $sql = 'SELECT * FROM espec_aireindustrial WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case 'combustiblelinea':
            $sql = 'SELECT * FROM espec_combustiblelinea WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case 'elemento':
            $sql = 'SELECT * FROM espec_elemento WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case 'fluidos':
            $sql = 'SELECT * FROM espec_fluidos WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case 'panel':
            $sql = 'SELECT * FROM espec_panel WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
         case 'cabina':
            $sql = 'SELECT * FROM espec_cabina WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
                       
        case 'sellado':
            $sql = 'SELECT * FROM espec_sellado WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
    }
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $especificaciones = $seleccionado->fetch();

    $sql = "SELECT a_t.aplicacion, a_m.marca, a_v.modelo, a.id_tipo, a.id_marca, a.id_vehiculo,a_v.cilindrada FROM aplicacion as a
                            JOIN aplicacion_tipo as a_t ON a_t.id = a.id_tipo
                            JOIN aplicacion_marca as a_m ON a_m.id = a.id_marca
                            JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo
                            WHERE ( codigo = :codigo ) and ( a.deleted_at is null )  and  ( a_m.deleted_at is null ) and ( a_t.deleted_at is null ) and ( a_v.deleted_at is null ) and (  ( a.id_tipo=1 ) or  ( a.id_tipo=2 ) or  ( a.id_tipo=3 ) or  ( a.id_tipo=4 ))
                      GROUP BY a.id_tipo ";
  

    $seleccionado_aplicacion = $base_de_datos->prepare($sql);
    $seleccionado_aplicacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $seleccionado_aplicacion->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado_aplicacion->execute();
    $num_aplicacion = $seleccionado_aplicacion->rowCount();

    $sql = "SELECT marca, codigo_marca FROM filtro_equivalencia as f_e
                WHERE (codigo = :codigo) and ( deleted_at is null ) and (marca='BALDWIN' or marca='WIX'  or marca='DONALDSON' or marca='FOTON')
                ORDER BY f_e.marca, f_e.codigo_marca";
    $seleccionado_equivalencias = $base_de_datos->prepare($sql);
    $seleccionado_equivalencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado_equivalencias->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado_equivalencias->execute();

    $imagen = $especificaciones['imagen'];

    $path = "./../images/fichas-filtros/web/$imagen.jpg";
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $sql = "SELECT * FROM tipos WHERE id = :idtipo";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':idtipo', $idtipo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $tipo = $seleccionado->fetch();
    $categoria_id = $tipo['categoria_id'];

    $sql = "SELECT * FROM categorias WHERE id = :categoria_id";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':categoria_id', $categoria_id, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $categoria = $seleccionado->fetch();
    $categoria_nom = $categoria['categoria'];
    $producto_id =$categoria['producto_id'];


    $sql = "SELECT * FROM productos WHERE id = :producto_id";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':producto_id', $producto_id, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $producto = $seleccionado->fetch();
    $filtrar = $producto['nombre'];

?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="https://webtest.webfiltros.com/img/icono/web.ico">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Roboto:wght@500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Permanent+Marker&family=Roboto:wght@500&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&family=Permanent+Marker&family=Roboto:wght@500&display=swap" rel="stylesheet">
        <title>Ficha Tecnica <?php echo $codigo; ?></title>
    
      
<style >

@page {
margin: 0.2cm 0.2cm;
}

/** Defina ahora los márgenes reales de cada página en el PDF **/
body {
margin-top: 2cm;
margin-left: 0.5cm;
margin-right: 0.5cm;
margin-bottom: 1cm;

}

/** Definir las reglas del encabezado **/
header {
position: fixed;
top: 0cm;
left: 0cm;
right: 0cm;
height: 1.9cm;
/** Estilos extra personales **/
background-color: #E2001A;
color: white;
text-align: center;
line-height: 1.5cm;
}


  main {
        position: relative;
        top: 0.5cm;
        left: 0cm;
        right: 0cm;
        margin-bottom: 2cm;
      } 

/** Definir las reglas del pie de página **/
footer {
position: fixed;
bottom: 0cm;
left: 0cm;
right: 0cm;
height:2.35cm;
margin-top: 10.0 cm;
//background-color: #9b8c8c;

/** Estilos extra personales **/



//line-height: 1.5cm;
}

.barra {
     font-family: 'Libre Barcode 128 Text';
     font-size: 30px;
     margin-bottom: 0.5rem;
     margin-top: -0.5em;
}
      
      }

.logo_imagen{
    width:150px;
    padding:5px;
    margin-right: 10px;
     margin-top: 0.3cm;
    
}

.imagen {
    width:200px;
   // margin-left:100px;
}

.img_qr{
    width:70px;
    margin-top: 0cm;
  
}


.left{
     margin-left: 7cm;   
}

.right_jf{
     margin-left: 2cm;     
}
.right_txt{
     text-align: left;
     font-size: 10px;
     line-height: 0.5cm; 
     margin-left: 10px;
     color: #000;
}
.txt_comprar{
     text-align: right;
     font-size: 10px;
     line-height: 0.5cm; 
   // margin-right: 0.05cm;
     color: #000;
}

.logo_imagen_pie{
    width:160px;
    margin-block: 5cm;
    margin-left: 6cm;  
   
}
.pie_pg  {
    font-size: 13px;
    line-height: 0.5cm;
    text-align: right;
    margin-top: 0.2cm
}

.table_pdf{
    width:100%;
    border-collapse: collapse;
    
    }

    .table_pdf td{
//border: 1px solid #CCC;
    border-collapse: collapse;
   
    }

    .titulo_detalle {
    font-family: 'Permanent Marker', cursive;
    font-family: 'Roboto', sans-serif;
    font-size: 25px;
   // margin: 3px 3px;
    text-transform: uppercase;
    margin-left: 0.5cm;
    
}


.titu{
   
    font-size: 25px;
    text-transform: uppercase;
    text-align: center;
}

.mayuscula{
    text-transform: uppercase;
}


</style>
    </head>
    <body>

          <table class="table_pdf">
        <thead>
            <tr>
                <td><p class="titulo_detalle">Ficha Tecnica</p></td><td><p class="titu"><b><?php echo $codigo; ?></b></p></td><td > <div style="text-align: right;"><img src='https://webtest.webfiltros.com/img/logo/web.png' alt='logo' class='logo_imagen'></div></td>
            </tr>
           
        </thead>

        <tbody>
            <tr>
            <td ><br>
  
        <div class="aplicacion_producto">
            <section class="filtro_selec"  id="detalle_producto">
                <?php 
                    switch($clase){
                        case "aireautomotriz":
                ?>
                         
                            <div class="datos3" id="filtro_carrusel">
                    
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;" >
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                             </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado_aireautomotriz'>
                                    <tbody>
                                      <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>
                                          <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>
                                        
                                        <tr>
                                            <td>ø ext1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø ext2:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext2'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int2:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint2'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php 
                        break;
                         ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        case "aireindustrial":
                ?>
                
                            
                            <div class="datos3" id="filtro_carrusel">
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                            </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado_aireautomotriz'>
                                   
                                    <tbody>
                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                          <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>
                                        <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>
                                        <tr>
                                            <td>ø ext1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø ext2:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext2'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int2:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint2'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php
                        break;
                         ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        
                        case "combustiblelinea":
                ?>
                          
                            <div class="datos3" id="filtro_carrusel">
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                               </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                   
                                    <tbody>
                                      <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>

                                           <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>  

                                        <tr>
                                            <td>ø ext1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Entrada:</td>
                                            <td><?php echo number_format( $especificaciones['entrada'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Salida:</td>
                                            <td><?php echo number_format( $especificaciones['salida'],2,",","."); ?> mm</td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php
                            break;
                                    ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        case 'elemento':
                ?>
                            
                            <div class="datos3" id="filtro_carrusel">
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen'  >
                                    </div>
                                </div>
                            </div>
                            </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                    
                                    <tbody>
                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>
                                        

                                          <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>  
                                        
                                        <tr>
                                            <td>ø ext1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint1'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>ø int2:</td>
                                            <td><?php echo number_format( $especificaciones['diametroint2'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?></td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php
                            break;
                                          ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        case 'fluidos':
                ?>
                
                            <div class="datos3" id="filtro_carrusel">
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                             </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                   
                                    <tbody>
                                    <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>

                                          <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php 
                        break;

                        ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        case 'panel':
                ?>
                          
                            <div class="datos3" id="filtro_carrusel">
                            
                                <div class='container-all'>
                        
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?> alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                            </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                    
                                    <tbody>

                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>

                                        
                                          <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>  
                                        
                                        <tr>
                                            <td>Largo:</td>
                                            <td><?php echo number_format( $especificaciones['largo'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Ancho:</td>
                                            <td><?php echo number_format( $especificaciones['ancho'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php 
                        break; ?>
                        </td>
        </tr>

           </tbody>


    </table>

    </br> <?php 
                        case 'cabina':
                ?>
                          
                            <div class="datos3" id="filtro_carrusel">
                            
                                <div class='container-all'>
                        
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?> alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                            </td>
            <td colspan="2"><br>
                            <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                    
                                    <tbody>

                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>

                                        
                                          <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?>  
                                        
                                        <tr>
                                            <td>Largo:</td>
                                            <td><?php echo number_format( $especificaciones['largo'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Ancho:</td>
                                            <td><?php echo number_format( $especificaciones['ancho'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>

                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php 
                        break; ?>
                        </td>
        </tr>

           </tbody>


    </table>
     </br> <?php 
                        case 'sellado':
                ?>
            <div class="datos3" id="filtro_carrusel">
                          
                                <div class='container-all'>
                                    <div class='item-slide' style="text-align: center;" style="text-align: center;">
                                       
                                          <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' data='<?php echo $base64; ?>' >
                                    </div>
                                </div>
                            </div></td>
            <td colspan="2"><br> <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                    <tbody>
                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td><?php echo $filtrar ?></td>
                                        </tr>

                                            <?php
                                            }
                                            if($categoria_nom != null or $categoria_nom != "" ){ 
                                        ?>

                                        <tr>
                                            <td>Categoria:</td>
                                            <td><?php echo $categoria_nom ?></td>
                                        </tr>
                                            <?php
                                            }
                                        ?>
                                        <tr>
                                            <td>Tipo:</td>
                                            <td><?php echo $especificaciones['tipo']; ?></td>
                                        </tr>
                                        <?php 
                                            
                                               if ($filtro['filtracion'] != null && $filtro['filtracion']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Filtración:</td>
                                                    <td><?php echo $filtro['filtracion'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                            
                                               if ($filtro['und_empaque'] != null && $filtro['und_empaque']!= "N/D" && $filtro['und_empaque'] != 0) {
                                        ?>
                                                <tr>
                                                    <td>Unidades de Empaque:</td>
                                                    <td><?php echo $filtro['und_empaque'] ?></td>
                                                </tr>
                                        <?php
                                               }
                                        ?> 
                                        <tr>
                                            <td>ø ext1:</td>
                                            <td><?php echo number_format( $especificaciones['diametroext'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Rosca:</td>
                                            <td><?php echo $especificaciones['diametroint']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Altura:</td>
                                            <td><?php echo number_format( $especificaciones['altura'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Empacadura:</td>
                                            <td>ø ext: <?php echo number_format( $especificaciones['diametroempext'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>ø int: <?php echo number_format( $especificaciones['diametroempint'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Espesor: <?php echo number_format( $especificaciones['espesoremp'],2,",","."); ?> mm</td>
                                        </tr>
                                        <tr>
                                            <td>Valvula de Alivio</td>
                                            <?php 
                                                if($especificaciones['valvulaal'] == 1){
                                            ?>
                                                    <td>SI</td>  
                                            <?php
                                                }
                                                if($especificaciones['valvulaal'] == 0){
                                            ?>
                                                    <td>NO</td>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                                   <?php
                                                         if($especificaciones['apertura'] != null ){ 
                                                             ?>
                                                             <tr>
                                                                <td>Apertura:</td>
                                                            <td><?php echo  $especificaciones['apertura'];   ?></td>
                                                            </tr>
                                                      <?php
                                                            }
                                                        ?>      
                                            
                                        <tr>
                                            <td>Valvula Anti-Drain</td>
                                            <?php
                                                if($especificaciones['valvulaad'] == 1){
                                            ?>
                                                    <td>SI</td>
                                            <?php
                                                }
                                            ?>
                                            <?php
                                                if($especificaciones['valvulaad'] == 0){
                                            ?>
                                                    <td>NO</td>
                                            <?php
                                                }
                                            ?>
                                        </tr>
                                        <?php
                                            if ( $especificaciones['detalle1'] != null && $especificaciones['detalle1']!= "N/D" ) {
                                        ?>
                                                <tr>
                                                    <td>Detalle 1:</td>
                                                    <td><?php echo $especificaciones['detalle1']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                            if ($especificaciones['detalle2'] != null && $especificaciones['detalle2']!= "N/D") {
                                        ?>
                                                <tr>
                                                    <td>Detalle 2:</td>
                                                    <td><?php echo $especificaciones['detalle2'] ?></td>
                                                </tr>
                                        <?php
                                            }
                                        ?>
                                        
                                                <?php
                                                       if($barra != null or $barra != "" ){ 
                                                        ?>

                                     <tr>
                                                    <td>Codigo  de barra:</td>
                                                    <td > <p class="barra"><?php echo $barra ?></p></td>
                                                </tr>

                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                <?php
                            break;
                    }
                ?></td>
        </tr>

           </tbody>


    </table>
    
           <!-- /  aplicacion -->
  
         <div class="datos"  id="filtro_aplicacion">
                    <?php
                        $aplicacion = "";
                        $aplicacion_marca = "";
                    ?>
                        <table class='apli'>
                            <thead class='apli'>
                                <?php 
                                    if( $num_aplicacion > 0 ){
                                ?>
                                        <tr>
                                            <td class='tilt_blanco' colspan="4"><b>Aplicaciones</b></td>
                                            
                                        </tr>
                                <?php
                                    }
                                    ?>
                            </thead>
                            <tbody>
                                <?php 
                                    while( $reg_aplicacion = $seleccionado_aplicacion->fetch() ){
                                        $aplicacion_colocar = substr($reg_aplicacion['aplicacion'], 1);
                                        $aplicacion_colocar_marca = $reg_aplicacion['marca'];
                                        $aplicacion_vehiculo = $reg_aplicacion['modelo'];

                                        $id_tipo = $reg_aplicacion['id_tipo'];
                                        $id_marca = $reg_aplicacion['id_marca'];
                                        $id_vehiculo = $reg_aplicacion['id_vehiculo'];
                                         $cilindrada = $reg_aplicacion['cilindrada'];
                                ?>

                                        <tr>
                                        <?php 
                                            if($aplicacion != $reg_aplicacion['aplicacion']){
                                        ?>
                                                <td class='apli'><b><?php echo $aplicacion_colocar; ?></b></td>
                                        <?php
                                            }
                                            else {
                                        ?>
                                                <td class='apli'></td>
                                        <?php
                                            }
                                            if($aplicacion_marca != $reg_aplicacion['marca']){
                                        ?>
                                                <td class='apli'>
                                                    <a href='#' class='link'><?php echo $aplicacion_colocar_marca ?></a>
                                                </td>
                                        <?php
                                            }
                                            else {
                                        ?>
                                                <td class='apli'></td>
                                        <?php
                                            }
                                        ?>
                                        <td class='apli'>
                                            <a href='#' class='link'><?php echo $aplicacion_vehiculo ?></a>
                                        </td>
                                         <td class='apli'>
                                            <a href='#' class='link'><?php echo $cilindrada ?></a>
                                        </td>
                                    </tr>
                                <?php
                                        $aplicacion = $reg_aplicacion['aplicacion']; 
                                        $aplicacion_marca =  $reg_aplicacion['marca'];         
                                    }
                                ?>
                        </tbody>
                    </table>
                </div> 

  <!-- /  equivalencia -->
 <div class="datos" id="filtro_equivalencia">
                    <table class='eq'>
                        <?php
                            $equivalencia = "";
                            if( $seleccionado_equivalencias->rowCount() > 0 ){
                        ?>
                                <thead class='eq'> 
                                    <tr>
                                        <td class='tilt_blanco' colspan="2"><b>Equivalencias</b> </td>
                                       
                                    </tr> 
                                </thead>
                                <tbody class='eq'>
                        <?php
                            }
                              $count = 0;
                            while($reg_equivalencia = $seleccionado_equivalencias->fetch()){
                                $equivalencia_colocar = $reg_equivalencia['marca'];
                                $equivalencia_codigo_marca = $reg_equivalencia['codigo_marca'];
                               
                                if($count % 4 == 0){
                                echo "<tr>";
                                 }
                        ?>
                                
                       
                                <th class='eq'><?php echo $equivalencia_colocar; ?> </th>
                      
                            <td class='eq'><?php echo $equivalencia_codigo_marca; ?></td>
                             <td class='eq'></td>
                            <?php
                        if($count % 4 == 3){
                            echo "</tr>";
                        }
                        $count++;
                            $equivalencia = $reg_equivalencia['marca'];              
                            }
                            if($count % 4 != 0){
                        echo "</tr>";
                    }
                        ?>
                        </tbody>
                        </table>
                </div> 


    </main>


</body>
</html>
<?php
$codigo_url = str_replace(" ","%20",$codigo);
// Llamando a la libreria PHPQRCODE
include('libreria/phpqrcode/qrlib.php'); 

// Ingresamos el contenido de nuestro Código QR
$contenido = "https://webfiltros.com/filtro/filtro.php?codigo=$codigo_url&clase=$clase&cod=1";

// Exportamos una imagen llamado resultado.png que contendra el valor de la avriable $content
QRcode::png($contenido,"libreria/resultado.png",QR_ECLEVEL_L,10,2);

// Impresión de la imagen en el navegador listo para usarla

?> 
 


    <footer>
<table class="table_pdf">
   <tr>
            <div  style="text-align: left;" ><img src='https://webfiltros.com/filtro/libreria/resultado.png?t=<?php echo $rann?>' class="img_qr"/></div> </td>
            <td><div style="text-align: right;"><img src='https://webfiltros.com/img/logo/logojf_d.png' alt='logo' class='logo_imagen_pie'></div></td>
    </tr>
</table>
    </footer>
       
               </body>
</html> 






<?php
    $html = ob_get_clean();
    require_once('./../librerias/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $domPDF = new Dompdf($options);
    
    $domPDF->loadHTML($html);
    $domPDF->setPaper('letter');
    $domPDF->Output('','S');
    $domPDF->render();
    $domPDF->stream("Ficha_Tecnica_'$codigo'.pdf", array("Attachment" => false));
   // header("location: ./../especificaciones.php?pdf_generado=true");
?>