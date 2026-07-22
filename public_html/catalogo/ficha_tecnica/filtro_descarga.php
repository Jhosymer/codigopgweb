<?php
  ob_start(); 

// Mueve la configuración de memoria y encabezados.
ini_set('memory_limit', '1024M'); 
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");

// Coloca las configuraciones de error.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Caracas');
$rann = date('H:i:s');

// Si el código NO existe, detenemos la ejecución de forma segura.
if( !isset($_GET['codigo']) ){
    // Si no hay código, liberamos el buffer (opcional, pero limpio) y morimos.
    ob_end_clean(); 
    die("Error: El parámetro 'codigo' es requerido.");
}

    $codigo = $_GET['codigo'];

    include('./../../config/conexion.php');

    require_once('./../../vendor/autoload.php'); 
     use Picqer\Barcode\BarcodeGeneratorPNG; 

    function calcular_checksum_ean13($codigo) {
    $codigo = preg_replace('/\D/', '', $codigo);
    $codigo = str_pad(substr($codigo, 0, 12), 12, '0', STR_PAD_LEFT);
    $suma_impar = 0;
    $suma_par = 0;
    for ($i = 0; $i < 12; $i++) {
        $digito = intval($codigo[$i]);
        if ($i % 2 == 0) {
            $suma_impar += $digito;
        } else {
            $suma_par += $digito * 3;
        }
    }
    $total = $suma_impar + $suma_par;
    $checksum = ($total % 10 == 0) ? 0 : (10 - $total % 10);
    return $codigo . $checksum;
}
function generate_barcode_base64($data) {
    $generator = new BarcodeGeneratorPNG();
    
    $barWidth = 1; 
    
    // Usaremos una altura de 80px. El texto numérico se ubicará en la parte inferior de estos 80px.
    $height = 80; 
    
    // **Corregido:** Revertimos a TYPE_EAN_13 que es la constante correcta.
    $barcode_image = $generator->getBarcode($data, $generator::TYPE_EAN_13, $barWidth, $height); 
    
    $base64 = base64_encode($barcode_image);
    return 'data:image/png;base64,' . $base64;
}
    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }

    $sql = "SELECT * FROM filtro_codificacion WHERE codigo = :codigo and ( deleted_at is null )";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $filtro = $seleccionado->fetch();
    $clase = $filtro['clase'];
    $barra= $filtro['codigo_barra'];
    $idtipo= $filtro['id_tipo'];

     $barra_ean13 = ($barra != null && $barra != "") ? calcular_checksum_ean13($barra) : null;
$barra_base64 = ($barra_ean13 != null) ? generate_barcode_base64($barra_ean13) : null;

$barra_formateada = null;
if ($barra_ean13 !== null && strlen($barra_ean13) == 13) {
    // Formato: [0] [706384] [21380] [6]
    $barra_formateada = 
        substr($barra_ean13, 0, 1) . ' ' . // Primer dígito
        substr($barra_ean13, 1, 6) . ' ' . // Dígitos del 2 al 7 (izquierda)
        substr($barra_ean13, 7, 5) . ' ' . // Dígitos del 8 al 12 (derecha)
        substr($barra_ean13, 12, 1);     // Último dígito (checksum)
}


      switch($clase){
        case "aireautomotriz":
            $sql = 'SELECT * FROM espec_aireautomotriz WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case "aireindustrial":
            $sql = 'SELECT * FROM espec_aireindustrial WHERE ( codigo = :codigo ) and ( deleted_at is null )';
            break;
        case 'combustiblelinea':
            $sql = 'SELECT e.*, r_e.codigo as nombre_rosca_entrada, r_s.codigo as nombre_rosca_salida 
            FROM espec_combustiblelinea as e
            LEFT JOIN roscas as r_e ON e.id_rosca_entrada = r_e.id
            LEFT JOIN roscas as r_s ON e.id_rosca_salida = r_s.id
            WHERE ( e.codigo = :codigo ) and ( e.deleted_at is null )';
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
            $sql = 'SELECT e_s.*, r.codigo as nombre_rosca 
            FROM espec_sellado as e_s
            LEFT JOIN roscas as r ON e_s.id_rosca = r.id
            WHERE ( e_s.codigo = :codigo ) and ( e_s.deleted_at is null )';
            break;
    }
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $especificaciones = $seleccionado->fetch();

     $sql = "SELECT a_t.aplicacion, a_m.marca, a_v.ano, a_v.modelo,  a.id_tipo, a.id_marca, a.id_vehiculo, a_v.cilindrada  FROM aplicacion as a
                                                            JOIN aplicacion_tipo as a_t ON a.id_tipo = a_t.id
                                                            JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id
                                                            JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id
                                                            WHERE (codigo = :codigo ) and ( a_m.deleted_at is null ) and ( a.deleted_at is null ) and (a_v.deleted_at is null)
                                                            ORDER BY a.id_tipo, a_m.marca, a_v.modelo ";
  

    $seleccionado_aplicacion = $base_de_datos->prepare($sql);
    $seleccionado_aplicacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
    $seleccionado_aplicacion->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado_aplicacion->execute();
    $num_aplicacion = $seleccionado_aplicacion->rowCount();

    $sql = "SELECT f_e.marca AS marca, f_e.codigo_marca AS codigo_marca 
        FROM filtro_equivalencia AS f_e
        INNER JOIN equivalencia_marca AS e_m ON f_e.id_marca = e_m.id
        WHERE f_e.codigo = :codigo 
          AND f_e.deleted_at IS NULL 
          AND e_m.mostrar = 1
        ORDER BY f_e.marca, f_e.codigo_marca";
                
    $seleccionado_equivalencias = $base_de_datos->prepare($sql);
    $seleccionado_equivalencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado_equivalencias->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado_equivalencias->execute();

    $imagen = $especificaciones['imagen'];


   $path_producto = "./../../images/fichas-filtros/web/$imagen.jpg";
   $path_no_imagen = "./../../images/fichas-filtros/web/noimagen.jpg";

    if (file_exists($path_producto)) {
        $path_final = $path_producto;
    } else {
        $path_final = $path_no_imagen;
    }

    $path = $path_final; 
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $path_logo_web2 = './../../img/logo/web2.png';
    $type_logo_web2 = pathinfo($path_logo_web2, PATHINFO_EXTENSION);
    $data_logo_web2 = file_get_contents($path_logo_web2);
    $base64_logo_web2 = 'data:image/' . $type_logo_web2 . ';base64,' . base64_encode($data_logo_web2);

    $path_qr = './../../img/qr/DondeCompra.png';
    $type_qr = pathinfo($path_qr, PATHINFO_EXTENSION);
    $data_qr = file_get_contents($path_qr);
    $base64_qr = 'data:image/' . $type_qr . ';base64,' . base64_encode($data_qr);

    $path_qr_tc = './../../librerias/resultado.png';
    $type_qr_tc = pathinfo($path_qr_tc, PATHINFO_EXTENSION);
    $data_qr_tc = file_get_contents($path_qr_tc);
    $base64_qr_tc = 'data:image/' . $type_qr_tc . ';base64,' . base64_encode($data_qr_tc);

     $path_jet= './../../jetfilter/img/logoj.png';
    $type_jet = pathinfo($path_jet, PATHINFO_EXTENSION);
    $data_jet = file_get_contents($path_jet);
    $base64_jet= 'data:image/' . $type_jet. ';base64,' . base64_encode($data_jet);


    $sql = "SELECT * FROM tipos WHERE id = :idtipo";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':idtipo', $idtipo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $tipo = $seleccionado->fetch();
    $categoria_id = $tipo['categoria_id'] ?? 'NULL';

    $sql = "SELECT * FROM categorias WHERE id = :categoria_id";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':categoria_id', $categoria_id, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $categoria = $seleccionado->fetch();
    $categoria_nom = $categoria['categoria'] ?? 'N/D';
    $producto_id =$categoria['producto_id'] ?? 'N/D';


    $sql = "SELECT * FROM productos WHERE id = :producto_id";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':producto_id', $producto_id, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $producto = $seleccionado->fetch();
    $filtrar = $producto['nombre'] ?? 'N/D';


   
   ?>

<!DOCTYPE html>
<html>
<head>
   
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;500;700&display=swap" rel="stylesheet">
        <title>Ficha Tecnica <?php echo $codigo; ?></title>
        <link rel="icon" href="./../../../img/logo/web.ico">
<style>


@page {
margin: 0.2cm 0.2cm;
}

/** Defina ahora los márgenes reales de cada página en el PDF **/
body {
margin-top: 2cm;
margin-left: 0.5cm;
margin-right: 0.5cm;
margin-bottom: 1cm;
font-family: 'Roboto', sans-serif;
font-weight: 500;

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
bottom: 0.2cm;
left: 0cm;
right: 0cm;
height:2.35cm;
margin-top: 20.0 cm;
/*background-color: #9b8c8c;*/

/** Estilos extra personales **/



/*line-height: 1.5cm;*/
}

      
      

.logo_imagen{
    width:150px;
    padding:5px;
    margin-right: 10px;
     margin-top: -0.1cm;
    
}

.imagen {
    width:200px;
   /* margin-left:100px;*/
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
   /*margin-right: 0.05cm*/
     color: #000;
}

.logo_imagen_pie{
    width:160px;
    margin-block: 5cm;
    margin-left: 6cm;  
   
}
.pie_pg  {
    font-size: 13px;
    line-height: 0.4cm;
    text-align: right;
    margin-top: 0.1cm
}

.table_pdf{
    width:100%;
    border-collapse: collapse;
    
    }

    .table_pdf td{
/*border: 1px solid #CCC;*/
    border-collapse: collapse;
   
    }

    .titulo_detalle {
    font-family: 'Permanent Marker', cursive;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;   
    font-size: 26px;
    text-transform: uppercase;
    margin-left: 0.5cm;
     margin-top: -0.4cm;
    
}


.titu{
   font-family: 'Permanent Marker', cursive;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 26px;
    text-transform: uppercase;
    text-align: center;
     margin-top: -0.4cm;
}

.mayuscula{
    text-transform: uppercase;
}


/*tabla especidicacion solo para aire automotriz*/
.vehiculo_detalles_seleccionado_aireautomotriz {
    width: 90%;
    margin-bottom: 1em;
    border-collapse: collapse;
    font-size: 12px;
    margin-left: 60px;
  
}

.vehiculo_detalles_seleccionado_aireautomotriz tr{
    border-bottom: 1px solid #DCD9D8;
    height: 1em;

 }

/*tabla especidicacion*/
.vehiculo_detalles_seleccionado {
    width: 100%;
    margin-bottom: 1em;
    border-collapse: collapse;
    font-size: 12px;
  
}



.vehiculo_detalles_seleccionado tr {
    border-bottom: 1px solid #DCD9D8;
    height: 1em;

}
thead .equivalencias {
    background-color: #000000;
    color: white;
}

th.equivalencias, td.equivalencias, td.apli {
    border-top: 0.5pt solid #CCCCCC;
    padding: 2px;
}

/* tabla aplicacion*/

table.apli {
    background-color: #FFF;
    text-align: left;
    border-collapse: collapse;
    width: 50%;
    font-size: 12px;
    margin-top: 0.5cm;
}

thead.apli, td.otra_esp, .thead_titulo {
    background-color: #E2001A;
    color: white;
    
}
.tilt_blanco {
    text-transform: uppercase;
    font-family: 'Permanent Marker', cursive;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 16px;
    line-height: 1em;
    padding:10px;
    letter-spacing: 0.07vw;
}


a {
    text-decoration: none;
    color: #E2001A;
}



.datos {
  margin-bottom: 20px;
}

/* equivalencias */
table.eq {
    
    text-align: left;
    border-collapse: collapse;
    width: 100%; /* Asegura que ocupe todo el ancho */
    font-size: 12px;
    page-break-inside: avoid;
}
thead.eq {
    background-color: #808080; /* Gris */
    color: white;
    padding: 2px;
}

td.eq, th.eq {
   padding-left: 10px;
    border-bottom: 1px solid #75727194;
    width: 12.5%; /* Cada celda ocupa 1/8 del ancho (100%/8) */
}

tbody.eq{
    background-color: #E0E0E0; /* Gris claro para el cuerpo de la tabla */
}

.tilt_blanco_transparente {
    background-color: transparent !important; /* Quita el color de fondo */
    color: #000; /* Asegura que el texto (si hubiera) sea negro */
    border: none !important; /* Quita los bordes de la celda */
    padding: 10px;
    
    /* Hereda las propiedades de texto si usas tilt_blanco */
    text-transform: uppercase;
    font-family: 'Roboto', sans-serif;
    font-weight: 700;
    font-size: 14px;
    line-height: 1em;
    letter-spacing: 0.07vw;
}

</style>
</head>
<body>
<!-- Defina bloques de encabezado y pie de página antes de su contenido -->
<header>
 <table class="table_pdf">
       
            <tr>
                <th><p class="titulo_detalle">Ficha Tecnica</p></th><td><p class="titu"><b><?php echo $codigo ; ?></b></p></td><td > <div style="text-align: right;"><img src='<?php echo $base64_logo_web2?>' alt='logo' class='logo_imagen'></div></td>
            </tr>
           
        </table>
</header>

<?php
$codigo_url = str_replace(" ","%20",$codigo);
// Llamando a la libreria PHPQRCODE
include('./../../librerias/phpqrcode/qrlib.php'); 

// Ingresamos el contenido de nuestro Código QR
$contenido = "https://webfiltros.com/catalogo/ficha_tecnica/index.php?codigo=$codigo_url&clase=$clase&cod=1";

// Exportamos la imagen
$ruta_qr = "./../../librerias/resultado.png";
QRcode::png($contenido, $ruta_qr, QR_ECLEVEL_L, 10, 2);

// Convertir la imagen QR a Base64
// Lee el contenido del archivo .png
$datos_qr = file_get_contents($ruta_qr);
// Codifica el contenido a Base64 y añade el prefijo Data URI
$base64_qr_tc_final = 'data:image/png;base64,' . base64_encode($datos_qr);

// A partir de este punto, $base64_qr_final contiene la imagen lista para Dompdf.


// Si el QR que quieres refrescar es el que estás generando aquí y se llama $base64_qr_tc:
$base64_qr_tc = $base64_qr_tc_final; 


?> 
<footer>
<table class="table_pdf" id="detalle">
    <tr>
        <td>
            <img src='<?php echo $base64_qr_tc;?>' class="img_qr"/>
            <p class="right_txt"> <b>Ir a la Web</b></p>
        </td>
        <td>
            <img src='<?php echo $base64_qr;?>' alt='logo' class='img_qr left'>
            <p class="txt_comprar"><b>¿Donde Comprar?</b></p>
        </td>

         <td><img src='<?php echo $base64_jet;?>' alt='logo' class='logo_imagen_pie'>
            <p class="pie_pg  right_jf"> Jetfilter, C.A,  Tinaquillo EDO. Cojedes, www.webfiltros.com,   RIF J- 00059322-1 </p>
            </td>
    </tr>
</table>
</footer>

<!-- Envuelva el contenido de su PDF dentro de una etiqueta principal -->
<main>
<table class="table_pdf">
  <tbody>
            <tr>
            <td >
  
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                        if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                        if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                                <td>
                                                    <?php 
                                                    if (!empty($especificaciones['nombre_rosca_entrada'])) {
                                                        echo $especificaciones['nombre_rosca_entrada'];
                                                    } else {
                                                        echo number_format($especificaciones['entrada'], 2, ",", ".") . " mm";
                                                    }
                                                    ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Salida:</td>
                                                <td>
                                                    <?php 
                                                    if (!empty($especificaciones['nombre_rosca_salida'])) {
                                                        echo $especificaciones['nombre_rosca_salida'];
                                                    } else {
                                                        echo number_format($especificaciones['salida'], 2, ",", ".") . " mm";
                                                    }
                                                    ?>
                                                </td>
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
                                       if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                         if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                         if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                         if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                         if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">
                                                        
                                                        <img 
                                                            src="<?php echo $barra_base64; ?>" 
                                                            alt="Barcode" 
                                                            
                                                            style="width: 150px; height: 30px; display: block; margin: 0 auto 0 auto;"
                                                        >
                                                        
                                                        <p style="font-size: 14px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em;">
                                                            <?php echo $barra; ?>
                                                        </p>
                                                    </td>
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
                          
                                <div class="datos3" id="filtro_carrusel">
                            
                                <div class='container-all'>
                        
                                    <div class='item-slide' style="text-align: center;">
                                        <img src='<?php echo $base64; ?>' alt='Imagen de Filtro 1' class='imagen' >
                                    </div>
                                </div>
                            </div>
                            </td>
                            </div></td>
            <td colspan="2"><br> <div class="datos2" id="filtro_especificaciones">
                                <table class='vehiculo_detalles_seleccionado'>
                                    <tbody>
                                     <?php
                                              if($filtrar != null or $filtrar != "" ){ 
                                              ?>
                                     <tr>
                                            <td>A Filtrar:</td>
                                            <td class="mayuscula"><?php echo $filtrar ?></td>
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
                                            <?php 
                                            
                                            if (!empty($especificaciones['nombre_rosca'])) {
                                                $etiqueta = "Rosca:";
                                                $valor_mostrar = $especificaciones['nombre_rosca'];
                                            } else {
                                                $etiqueta = "ø int1:";
                                                $valor_mostrar = $especificaciones['diametroint'] . " mm";
                                            }
                                        ?>
                                        <tr>
                                            <td><?php echo $etiqueta; ?></td>
                                            <td><?php echo $valor_mostrar; ?></td>
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
                                         

                                  
                                            if($barra_base64 != null){ 
                                            ?>
                                                <tr>
                                                    <td style="width: 40%;">
                                                        <b>Código de Barras:</b>
                                                    </td>
                                                    
                                                    <td style="width: 60%; text-align: center; padding: 5px 5px 2px 5px; ">

    <img
        src="<?php echo $barra_base64; ?>"
        alt="Barcode"
        style="width: 200px; height: 50px; display: block; margin: 0 auto 0 auto;"
    >
                                         
    <p style="font-size: 12px; margin-top: 0px; margin-bottom: 0px; text-align: center; line-height: 1.1em; letter-spacing: 0.5px;">
        <?php echo $barra_formateada; ?>
    </p>

</td>
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
                                            <td class='tilt_blanco' colspan="5"><b>Aplicaciones</b></td>
                                            
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
                                         $ano = $reg_aplicacion['ano'];
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
                                            <?php echo $cilindrada ?>
                                        </td>
                                        <td class='apli'>
                                            <?php echo $ano ?>
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
                            $num_equivalencias = $seleccionado_equivalencias->rowCount(); // Volvemos a contar las filas
                            
                            if( $num_equivalencias > 0 ){
                        ?>
                                <thead class='eq'> 
                                    <tr>
                                        <td class='tilt_blanco' colspan="4"><b>Equivalencias</b> </td>
                                        <td class='tilt_blanco_transparente' colspan="4"></td> 
                                       
                                    </tr> 
                                </thead>
                                <tbody class='eq'>
                        <?php
                            }
                              $count = 0;
                            while($reg_equivalencia = $seleccionado_equivalencias->fetch()){
                                $equivalencia_colocar = $reg_equivalencia['marca'];
                                $equivalencia_codigo_marca = $reg_equivalencia['codigo_marca'];
                               
                                // Abre una nueva fila cada 4 pares (o al inicio)
                                if($count % 4 == 0){
                                echo "<tr>";
                                 }
                        ?>
                                
                       
                                <th class='eq'><?php echo $equivalencia_colocar; ?> </th>
                      
                                <td class='eq'><?php echo $equivalencia_codigo_marca; ?></td>
                                <?php
                                // Cierra la fila después del cuarto par (índice 3)
                                if($count % 4 == 3){
                                    echo "</tr>";
                                }
                                $count++;
                                $equivalencia = $reg_equivalencia['marca'];              
                            }
                            
                            // Lógica para rellenar la última fila si no está completa
                            $elementos_en_ultima_fila = $count % 4;
                            if($count > 0 && $elementos_en_ultima_fila != 0){
                                // Calcula cuántos pares de Marca/Código faltan
                                $pares_faltantes = 4 - $elementos_en_ultima_fila; 
                                
                                // Rellena con 2 celdas vacías (Marca + Código) por cada par faltante
                                for($i = 0; $i < $pares_faltantes; $i++){
                        ?>
                                    <th class='eq'></th>
                                    <td class='eq'></td>
                        <?php
                                }
                                // Cierra la última fila que ahora está completa
                                echo "</tr>";
                            } else if ($count == 0 && $num_equivalencias > 0) {
                                // Caso especial si la consulta regresa filas pero alguna lógica previa falló (aunque la tuya se ve bien, esto es por seguridad)
                                echo "<tr><td colspan='8'>No se encontraron datos de equivalencia o error de formato.</td></tr>";
                            }
                        ?>
                        </tbody>
                        </table>
                </div>



    

</main>


</body>
</html>

<?php
   $html = ob_get_clean();

// 3. LA LIBRERÍA DOMPDF ESTÁ LISTA PARA ENVIAR SUS PROPIOS HEADERS
require_once('./../../librerias/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$domPDF = new Dompdf($options);

$domPDF->loadHTML($html);
$domPDF->setPaper('letter', 'portrait');
$pdf = $domPDF->output(); // Esto no es necesario si usas render() y stream()
$domPDF->render();
$domPDF->stream("Ficha_Tecnica_'$codigo'.pdf", array("Attachment" => false));
exit(); // Es crucial añadir exit() o die() para detener la ejecución
?>
