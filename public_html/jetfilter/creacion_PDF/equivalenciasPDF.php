<?php
if( isset($_GET['generar-catalogo']) ){
 ob_start();
}
else{
    //header("location: ./../especificaciones.php");
}

include('./../conexion/conexion.php');
$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Marca
$seleccionado = $base_de_datos->prepare("SELECT id, marca FROM equivalencia_marca 
                                                 WHERE deleted_at IS NULL 
                                                 ORDER BY marca ASC");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $marca []= $fila;
}

//Equivalencias
$seleccionado = $base_de_datos->prepare("SELECT codigo, id_marca, codigo_marca 
                                    FROM filtro_equivalencia 
                                    WHERE deleted_at is null
                                    ORDER BY marca, codigo_marca");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $equivalencias []= $fila;
}

$path = './../img/logo/LOGOWEB.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
$fechaActual = date('d/m/Y');




?>
<html>
<head>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
      <!-- / <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&family=Permanent+Marker&family=Roboto:wght@400&display=swap" rel="stylesheet"> barra sin numero-->
       <link href='https://fonts.googleapis.com/css?family=Libre Barcode 128 Text' rel='stylesheet'>
    <title>Ficha Tecnica</title>
<style>
/**
Establezca los márgenes de la página en 0, por lo que el pie de página y el encabezado
puede ser de altura y anchura completas.
**/

@page {
margin: 0.2cm 0.2cm;
}
@media print {
  table {
    page-break-inside: avoid;
   
  }
 
}


/** Defina ahora los márgenes reales de cada página en el PDF **/
body {
margin-top: 3cm;
margin-left: 0.5cm;
margin-right: 0.5cm;
margin-bottom: 1cm;


}

/** Definir las reglas del encabezado **/
header {
position: fixed;
top: 0.5cm;
left: 0.5cm;
right: 0.5cm;
height: 2.5cm;
/** Estilos extra personales **/
text-align: center;
}


  main {
        position: relative;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        margin-bottom: 0cm;
      } 

/** Definir las reglas del pie de página **/
footer {
position: fixed;
bottom: 0cm;
left: 0cm;
right: 0cm;
height:0.5cm;
margin-top: 0.2cm;
/** Estilos extra personales **/

}

h3{
        color: #E2001A;
    }
      
      
img{
        width:50%;
    }
    .marca{
        background-color: #DDD;
        text-align: center;
        font-size: 0.78em;
   
    }


.table_pdf{
    width:100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin: 0 auto;
    
    }

   .table_pdf td{
   
    border-collapse: collapse;
    width:25%;
    page-break-inside: avoid;
    border: 1px solid #ccc;
    padding: 0px;
    vertical-align: top;
   
    }

    .tables_f td, th {
   /* border: 1px solid #ccc;*/
    width: 25%;
    padding: 0px;
}
    
 td {
       /* border: 1px solid #000;*/
        border-collapse: collapse;
        font-size: 0.75em;
    }
    th {
        text-align: center;
        font-size: 0.75em;
    }

    .tables td, th{
        border: 1px solid #ccc;
        width:25%;
        padding: 1px;
       

    }
    .tables {
        width:100%;

    }
    .titulo
    {
        border-style: none;
      
        padding: 2px;
        font-size: 1em;
        text-align: left;
          
    }
    .fecha
    {
        border-style: none;
        padding: 1px;
        font-size: 0.7em;
        text-align: right;
          
    }
    h3{
        color: #E2001A;
    }

    table {
        width:100%;
         border-collapse: collapse;
    }
    
 

</style>
</head>
<body>
<!-- Defina bloques de encabezado y pie de página antes de su contenido -->
<header>
 <table class="table_pdf">
       
 
                              <tr>
                                <th class="titulo" colspan="3"><h3>Equivalencias</h3></th>
                                <th class="fecha" width="20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
                            </tr>
                            <tr>
                                <th class="fecha" colspan="5"><?php echo "$fechaActual";?></th> </tr>
                            <tr>
       
           
        </table>
</header>

<footer>

</footer>

<!-- Envuelva el contenido de su PDF dentro de una etiqueta principal -->
<main>

<table class='table_pdf'>
           <tbody>


<tr><td> 

<?php 

$j = 1;
$o=1;
$ototal=1;
$counter = 1;
$countertotal=1;
foreach($marca as $mar){
     //Evaluo si la cantidad de celdas es diferente a 1 para sumarle las 2 columnas de thead de la tabla de marcas.
    if ($counter != 1 and  $j!= 1 ) {
        $counter += 2;
        $countertotal += 2;
        $j+= 2;
    } 
     // si el número de columnas es mayor a 48, cierra el td y abre otro de la tabla principal
    if ($counter >= 48 and $j >= 48) {
       
       $counter= 1;
       $j= 1;
       $o += 1;
       $ototal += 1;
       $countertotal += 2;
       if ($o % 6 == 0)  { 
        echo '</td></tr><td> ';
        $o += 1;
       } else {
        echo'</td><td> ';
      
       }


    }
?>      <table class = 'tables'>
                <thead>
                
                        <tr>
                            <th class="marca"  colspan="2"><?php echo $mar['marca'] ;  ?></th>
                    
                        </tr>
                        <tr>
                            <th>Codigo Marca</th>
                            <th >Codigo WEB</th>
                        
                        </tr>
                </thead>
<tbody>

        <?php 
       
      
            foreach($equivalencias as $equivalencia){ 
             
                if($equivalencia['id_marca'] == $mar['id']){
                    
            ?>
            
                <tr>
                    <td>
                        <?php echo $equivalencia['codigo_marca']; ?>
                    </td>
                    
                    <td>
                        <?php echo $equivalencia['codigo']; ?>
                    </td>
                    
             
                    
                        <?php
                          // contador de las columnas hasta 50
                         $counter++;
                          //contador de las celdas generadas para poder evaluar cuantas columnas faltan
                         $countertotal++;
                         // si las columnas 50 vuelve el contador de columnas  a 1 
                         if ($counter > 50) {
                            $counter = 1;
                         }

                        if ($j % 50 == 0) { 
                        $j++; 
                        $o++;
                        $ototal++;
                       
                        ?>
                          
                        </tr>
                        </tbody>
                        </table>
                       
                        </td>
                        <?php
                        //para que solo cree 5 filas
                        if ($o % 6 == 0)  { 
                         echo '</tr> <tr>';
                         $o++;
                       //  $ototal++;
                        }
                        ?>
                    
                        <td> 
                       
                            
                        <table class = 'tables' >
<thead>
  

    <tr>
        <th class="marca"  colspan="2"><?php echo $mar['marca'];  ?></th>
      
    </tr>
    <tr>
        <th>Codigo Marca</th>
        <th >Codigo WEB</th>
       
    </tr>
</thead>
<tbody>
                             
                        <?php

                        

                        }else 
                        {
                           
                            ?>
                            </tr>
                           
                       <?php
                       
                        $j++; 
                       
                        }
                      
                  
                }
               

               
            } 
            ?>
            </tbody>
        </table>
        <?php
           
                  
  
}
//revisa cuantas columnas faltaron
$sobran = $countertotal % 50; 
//echo "Sobran " . $sobran . " registros.";
 if ($sobran  == 0){
    ?>
</tbody>
<?php 
 }
 else {
    echo '<table class = "tables"><tbody>';
    while ($sobran <= 50) {
        // Código a ejecutar mientras $i sea menor a 40
        echo'<tr> <td>&nbsp;</td><td>&nbsp;</td><tr>';
        $sobran++; // Incrementar el valor de $i en cada iteración
    }

    echo '</tbody></table>';
 }  
 
// revisa cuantas filas faltaron
 $sobranfilas = $ototal % 5; 
 //echo "Sobran " .$ototal. '--'. $sobranfilas . " registros.";
 if ($sobranfilas  == 0){
    ?>
    </table></td> <?php
} else {
    while ($sobranfilas < 5) {
      ?>  <td>
         <table class = 'tables'>
        <thead>
        
                <tr>
                    <th class= 'marca' colspan="2" > &nbsp;</th>
            
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <th >&nbsp;</th>
                
                </tr>
        </thead>
<tbody><?php
       
       $sobranfilas++;
        for ($colum = 1; $colum <= 50; $colum++) {
            echo'<tr> <td>&nbsp;</td><td>&nbsp;</td><tr>';
        }
       echo '</tbody></table></td>' ;
      

    }

}
?>

                           
                            </tr>
                          

        </tbody>
</table>
</main>


</body>
</html>
<?php 
   $html = ob_get_clean();
    require_once('./../../librerias/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set("isRemoteEnabled",TRUE);
    $domPDF = new Dompdf;

   
    $domPDF->loadHTML($html);
 


    $domPDF->render();
    $pdf = $domPDF->output();
    file_put_contents("./../PDF/equivalencias.pdf",$pdf);
   // $domPDF->stream("dompdf_out.pdf", array("Attachment" => false));
   header("location: ./../especificaciones.php?pdf_generado=true");

  
?>
