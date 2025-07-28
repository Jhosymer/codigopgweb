<?php 
if( isset($_GET['generar-catalogo']) ){
    ob_start();
}
else{
    header("location: ./../especificaciones.php");
}

date_default_timezone_set('America/Caracas');

include('./../conexion/conexion.php');
$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//Aire Automotriz
$seleccionado = $base_de_datos->query("SELECT * FROM espec_aireautomotriz where deleted_at is null ORDER BY codigo ASC  ");
$aire_automotriz = $seleccionado->fetchAll();
//Aire Industrial
$seleccionado = $base_de_datos->query("SELECT * FROM espec_aireindustrial where deleted_at is null ORDER BY codigo ASC");
$aire_industrial = $seleccionado->fetchAll();
//Combustible en Linea
$seleccionado = $base_de_datos->query("SELECT * FROM espec_combustiblelinea where deleted_at is null ORDER BY codigo ASC");
$combustible_linea = $seleccionado->fetchAll();
//Elemento
$seleccionado = $base_de_datos->query("SELECT * FROM espec_elemento where deleted_at is null ORDER BY codigo ASC");
$elemento = $seleccionado->fetchAll();
//Panel
$seleccionado = $base_de_datos->query("SELECT * FROM espec_panel where deleted_at is null ORDER BY codigo ASC");
$panel = $seleccionado->fetchAll();
//Sellado
$seleccionado = $base_de_datos->query("SELECT * FROM espec_sellado where deleted_at is null ORDER BY codigo ASC");
$sellado = $seleccionado->fetchAll();

$path = './../img/logo/LOGOWEB.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
$fechaActual = date('d/m/Y');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Especificaciones</title>
</head>

<style>
    * {
        margin: 0.7em; 
        box-sizing: border-box;
    }

    
    table {
        width:100%;
        border-collapse: collapse;
        text-align: left;
      
    }

    th, td {
        border: 1px solid #000;
        border-collapse: collapse;
        padding: 1px;
        font-size: 0.7em;
    }

    thead  {
        background-color: #C2C2C2;
    }

    h3{
        color: #E2001A;
    }

    .marca{
        background-color: #DDD;
        text-align: left
    }

    img{
        width:60%;
    }
    .titulo
    {
        border-style: none;
        background-color:#FFFFFF;
        padding: 2px;
        font-size: 1em;
        text-align: left;
      
    }
    .fecha
    {
        border-style: none;
        background-color:#FFFFFF;
        padding: 1px;
        font-size: 1em;
        text-align: right;
       
    }
    .tipo {
        text-align: left;
    }
</style>

<body>

    <table>
        <thead>
            <tr>
                <th class="titulo"  colspan="7"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="5" class="fecha tipo">Filtros Sellados</th>
                <th class="fecha" colspan="4"><?php echo "$fechaActual";?></th> 
            </tr>

            <th class="columna">Codigo</th>
            <th class="columna">Diametro Externo(mm)</th>
            <th class="columna">Rosca</th>
            <th class="columna">Altura (mm)</th>
            <th class="columna">Diametro Ext. (mm)</th>
            <th class="columna">Empacadura Dimetro Int. (mm)</th>
            <th class="columna">Espesor (mm)</th>
            <th class="columna">Válvula de Alivio</th>
            <th class="columna">Válvula de Anti Drenaje</th>
        </thead>
        <tbody>
            <?php 
                foreach($sellado as $sell){
            ?>
                <tr>
                    <th class="columna"><?php echo $sell['codigo']; ?></th>
                    <th class="columna"><?php echo $sell['diametroext']; ?></th>
                    <th class="columna"><?php echo $sell['diametroint']; ?></th>
                    <th class="columna"><?php echo $sell['altura']; ?></th>
                    <th class="columna"><?php echo $sell['diametroempext']; ?></th>
                    <th class="columna"><?php echo $sell['diametroempint']; ?></th>
                    <th class="columna"><?php echo $sell['espesoremp']; ?></th>
                    <th class="columna"><?php if( $sell['valvulaal'] == 1) { echo "Sí"; } else{ echo "No"; }?></th>
                    <th class="columna"><?php if( $sell['valvulaad'] == 1) { echo "Sí"; } else{ echo "No"; } ?></th>
                </tr>

            <?php 
                }
            ?>
        </tbody>
    </table>
    <br>
    <br>
    <table>
        <thead>
               <tr>
                <th class="titulo"  colspan="3"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="4" class="fecha tipo">Filtros de Elemento Uso Automotriz y Comercial</th>
                <th class="fecha" colspan="1"><?php echo "$fechaActual";?></th> 
            </tr>

  <tr>
            <th class="columna">Codigo</th>
            <th class="columna">Diametro Externo (mm)</th>
            <th class="columna">Diametro Interno 1 (mm)</th>
            <th class="columna">Diametro Interno 2 (mm)</th>
            <th class="columna">Altura (mm)</th>
            </tr>

        </thead>

        <?php 
            foreach($elemento as $elem){
        ?>
            <tr>
                <th><?php echo $elem['codigo']; ?></th>
                <th><?php echo $elem['diametroext1']; ?></th>
                <th><?php echo $elem['diametroint1']; ?></th>
                <th><?php echo $elem['diametroint2']; ?></th>
                <th><?php echo $elem['altura']; ?></th>
            </tr>

        <?php 
            }
        ?>
    </table>
    <br>
    <br>

    <table>
        <thead> 

        <tr>
                <th class="titulo"  colspan="4"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="4" class="fecha tipo">Filtros de Aire Automotriz</th>
                <th class="fecha" colspan="2"><?php echo "$fechaActual";?></th> 
            </tr>
           
  <tr>
            <th class="columna">Codigo</th>
            <th class="columna">Diametro Externo 1(mm)</th>
            <th class="columna">Diametro Externo 2(mm)</th>
            <th class="columna">Diametro Interno 1(mm)</th>
            <th class="columna">Diametro Interno 2(mm)</th>
            <th>Altura (mm)</th>
            </tr>

        </thead>

        <?php 
            foreach($aire_automotriz as $automotriz){
        ?>
            <tr>
                <td><?php echo $automotriz['codigo']; ?></td>
                <td><?php echo $automotriz['diametroext1']; ?></td>
                <td><?php echo $automotriz['diametroext2']; ?></td>
                <td><?php echo $automotriz['diametroint1']; ?></td>
                <td><?php echo $automotriz['diametroint2']; ?></td>
                <td><?php echo $automotriz['altura']; ?></td>
            </tr>

        <?php 
            }
        ?>
    </table>
    <br>
    <br>
   
    <table>
        <thead>

         <tr>
                <th class="titulo"  colspan="4"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="4" class="fecha tipo">Filtros de Aire Industrial</th>
                <th class="fecha" colspan="2"><?php echo "$fechaActual";?></th> 
            </tr>
             <tr>
            <th class="columna">Codigo</th>
            <th class="columna">Diametro Externo 1(mm)</th>
            <th class="columna">Diametro Externo 2(mm)</th>
            <th class="columna">Diametro Interno 1(mm)</th>
            <th class="columna">Diametro Interno 2 (mm)</th>
            <th class="columna">Altura (mm)</th>
            </tr>
        </thead>

        <?php 
            foreach($aire_industrial as $industrial){
        ?>
            <tr>
                <td><?php echo $industrial['codigo']; ?></td>
                <td><?php echo $industrial['diametroext1']; ?></td>
                <td><?php echo $industrial['diametroext2']; ?></td>
                <td><?php echo $industrial['diametroint1']; ?></td>
                <td><?php echo $industrial['diametroint2']; ?></td>
                <td><?php echo $industrial['altura']; ?></td>
            </tr>

        <?php 
            }
        ?>
    </table>
    <br>
    <br>
  
    <table>
        <thead>
            <tr>
                <th class="titulo"  colspan="2"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="2" class="fecha tipo">Filtros de Aire Panel</th>
                <th class="fecha" colspan="2"><?php echo "$fechaActual";?></th> 
            </tr>
             <tr>

            <th class="columna">Codigo</th>
            <th class="columna">Largo(mm)</th>
            <th class="columna">Ancho(mm)</th>
            <th class="columna">Altura(mm)</th>
            </tr>

        </thead>

        <?php 
            foreach($panel as $panel){
        ?>
            <tr>
                <td><?php echo $panel['codigo']; ?></td>
                <td><?php echo $panel['largo']; ?></td>
                <td><?php echo $panel['ancho']; ?></td>
                <td><?php echo $panel['altura']; ?></td>
            </tr>
 <?php 
            }
        ?>
    </table>
   <br>
    
    <table>
        <thead>
             <tr>
                <th class="titulo"  colspan="3"><h3>Especificaciones</h3></th>
                <th class="fecha" width= "20Px" colspan="2"><img src="<?php echo $base64; ?>"></th>
            </tr>

            <tr>
                <th colspan="3" class="fecha tipo">Filtros de Combustible</th>
                <th class="fecha" colspan="2"><?php echo "$fechaActual";?></th> 
            </tr>
             <tr>

            <th class="columna">Codigo</th>
            <th class="columna">Diametro Externo(mm)</th>
            <th class="columna">Altura(mm)</th>
            <th class="columna">Entrada(mm)</th>
            <th class="columna">Salida(mm)</th>
            </tr>

        </thead>

        <?php 
            foreach($combustible_linea as $combustible){
        ?>
            <tr>
                <td><?php echo $combustible['codigo']; ?></td>
                <td><?php echo $combustible['diametroext']; ?></td>
                <td><?php echo $combustible['altura']; ?></td>
                <td><?php echo $combustible['entrada']; ?></td>
                <td><?php echo $combustible['salida']; ?></td>
            </tr>

        <?php 
            }
        ?>
    </table>
    <br>
    <br>
</body>
</html>

<?php 
    $html = ob_get_clean();
    require_once('./../../librerias/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;
    $domPDF = new Dompdf;
    //$options = $dompdf->getOptions();
    $domPDF->loadHTML($html);
    $domPDF->render();
    $pdf = $domPDF->output();
    file_put_contents("./../PDF/especificaciones.pdf",$pdf);

    header("location: ./../especificaciones.php?pdf_generado=true");
?>

