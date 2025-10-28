<?php 
if( isset($_GET['generar-catalogo']) ){
    ob_start();
}
else{
    header("location: ./../especificaciones.php");
}

include('../../../config/conexion.php');
try {
    $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
} catch (PDOException $pe) {
    die("Could not connect to the database $dbname :" . $pe->getMessage());
}

$seleccionado = $base_de_datos->prepare("SELECT a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_v.motor, a_v.ano, a_m.marca, count(*) 
                                        FROM aplicacion as a 
                                        JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
                                        JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
                                        WHERE( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null )
                                        GROUP BY a.id_tipo, a.id_marca, a.id_vehiculo, a_v.modelo, a_m.marca 
                                        ORDER BY a_m.marca, a_v.modelo
                                        LIMIT 0, 650");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $aplicacion []= $fila;
}

$seleccionado_espera = $base_de_datos->prepare("SELECT a.codigo, a.aplicacion FROM aplicacion as a 
                                                                                JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
                                                                                JOIN aplicacion_vehiculo as a_v ON a.id_vehiculo = a_v.id 
                                                                                WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null ) and ( a_v.deleted_at is null ) and a.id_vehiculo = ? and a.id_marca = ?
                                                                                GROUP BY a.aplicacion, a.codigo");

$seleccionado = $base_de_datos->prepare("SELECT a.aplicacion FROM aplicacion as a 
                                            WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null )
                                            GROUP BY a.aplicacion
                                            ORDER BY a.aplicacion");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $nombres_aplicaciones []= $fila;
}

$seleccionado = $base_de_datos->prepare("SELECT count(DISTINCT a.aplicacion) FROM aplicacion as a 
                                        WHERE ( a.id_tipo = 3 ) and ( a.deleted_at is null )");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $numero_aplicaciones []= $fila;
}

$path = '../../../img/logo/LOGOWEB.png';
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
    <title>Vehiculos Fuera de Carretera1</title>
</head>
   <style>
* {
  margin: 0.5em; 
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
.marca{
    background-color: #DDD;
    text-align: left
}

h3{
   color: #E2001A;
}

img{
width:90%;
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
  font-size: 0.7em;
  text-align: right;
  
}
    </style>
<body>
   
    <table>
        <thead>
            <tr> 
                <th class="titulo" colspan="12">
                    <h3>Vehiculos Fuera de Carretera</h3>
                </th>
                <th class="fecha" width= "50Px" colspan="3">
                    <img src="<?php echo $base64; ?>">
                </th>
            </tr>
            <tr>
                <th class="fecha" colspan="15"><?php echo "$fechaActual";?></th> 
            </tr>
            <tr>
                <th><h6>Modelo</h6></th>
                <th><h6>Motor</h6></th>
                <th><h6>Año</h6></th>
                <?php 
                    for($i = 0; $i < $numero_aplicaciones[0]['count(DISTINCT a.aplicacion)']; $i++){
                        ?>
                            <th><h6><?php echo $nombres_aplicaciones[$i]['aplicacion']; ?></h6></th>
                        <?php
                    }
                ?>
            </tr>
    
        </thead>
        <tbody>
        <?php 
                    $marca = "";
                    $j = 0;
                    foreach($aplicacion as $apl){
                        if($marca != $apl['marca']){
                            ?>
                                <tr><td colspan="<?php echo ($numero_aplicaciones[0]['count(DISTINCT a.aplicacion)'] + 3); ?>" class="marca"><h6><?php echo $apl['marca']; ?></h6></td></tr>
                            <?php
                            $marca = $apl['marca'];
                        }
                        ?>
                        <tr>
                            <td>
                                <h6><?php echo $apl['modelo'] ?></h6>
                            </td>
                            <td>
                                <h6><?php echo $apl['motor'] ?></h6>
                            </td>
                            <td>
                                <h6><?php echo $apl['ano'] ?></h6>
                            </td>
                                <?php 
                                    unset($aplicacion_filtro);
                                    unset($aplicacion_filtro_2);
                                    
                                    $id_vehiculo = $apl['id_vehiculo'];
                                        $seleccionado_espera->execute([ $apl['id_vehiculo'], $apl['id_marca'] ]);
                                        while ($fila = $seleccionado_espera->fetch(PDO::FETCH_ASSOC)) {
                                            $aplicacion_filtro []= $fila;
                                        }
 
                                        $j = 0;
                                        $k = 0;
                                        foreach($aplicacion_filtro as $a_f){
                                            $aplicacion_filtro_2[$j] = $a_f['aplicacion'];
                                            $codigo_filtro_2[$j] = $a_f['codigo'];
                                            $j++;
                                        }
        
                                     foreach ($nombres_aplicaciones as $nombre) {
                                        $matching_codigos = []; // Array para almacenar códigos coincidentes
                                    
                                        // Recorre aplcicacionc_filtro_2 y busca coincidencias
                                        foreach ($aplicacion_filtro_2 as $index => $aplcicacion) {
                                            if (isset($aplcicacion) && strcasecmp($nombre['aplicacion'], $aplcicacion) == 0) {
                                                $matching_codigos[] = $codigo_filtro_2[$index]; // Add matching codigo to array
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
    
</body>
</html>

<?php
    unset($nombres_aplicaciones);
    unset($aplicacion);
    unset($numero_aplicaciones);

    $html = ob_get_clean();
    require_once('../../../librerias/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set("isRemoteEnabled",TRUE);
    $domPDF = new Dompdf;
    $domPDF->loadHTML($html);

    $domPDF->render();
    $pdf = $domPDF->output();
    file_put_contents("./../PDF/fuera_de_carretera1.pdf",$pdf);
    //$domPDF->stream("dompdf_out.pdf", array("Attachment" => false));

    ?>

<?php 
    header("location: ./../especificaciones.php?pdf_generado=true");
?>
