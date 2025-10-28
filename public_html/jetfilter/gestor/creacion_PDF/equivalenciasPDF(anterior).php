<?php
if( isset($_GET['generar-catalogo']) ){
   ob_start();
}
else{
    //header("location: ./../especificaciones.php");
}

include('../../../config/conexion.php');
$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Marca
$seleccionado = $base_de_datos->prepare("SELECT id, marca FROM equivalencia_marca 
                                                 WHERE deleted_at IS NULL 
                                                 ORDER BY marca ASC
                                                 LIMIT 0, 55");
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
    <title>PDF - Equivalencias</title>

    <style>
    * {
        margin: 0.5em; 
        box-sizing: border-box;
    }
    .titulo
    {
        border-style: none;
        background-color:#FFFFFF;
        padding: 2px;
        font-size: 1em;
        text-align: left;
          
    }

    img{
        width:60%;
    }
    .marca{
        background-color: #DDD;
        text-align: left
    }
    .fecha
    {
        border-style: none;
        background-color:#FFFFFF;
        padding: 1px;
        font-size: 0.7em;
        text-align: right;
          
    }

    h3{
        color: #E2001A;
    }
    table {
        width:100%;
        text-align: left;
         border-collapse: collapse;
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
</style>
</head>

<body>
     

        <table>
            <thead>
                <tr>
                    <th class="titulo" colspan="6"><h3>Equivalencias</h3></th>
                    <th class="fecha" width="20Px" colspan="4"><img src="<?php echo $base64; ?>"></th>
                </tr>
                <tr>
                    <th class="fecha" colspan="10"><?php echo "$fechaActual";?></th> </tr>
                <tr>
                </thead>
                <?php 
        foreach($marca as $mar){
    ?>
                <tr>
                    <th class="marca"  colspan="2"><?php echo $mar['marca']; ?></th>
                  
                    <th class="marca" colspan="2" ><?php echo $mar['marca']; ?></th>
                 
                    <th class="marca" colspan="2"><?php echo $mar['marca']; ?></th>
                 
                    <th class="marca" colspan="2"><?php echo $mar['marca']; ?></th>
                    
                    <th class="marca" colspan="2"><?php echo $mar['marca']; ?></th>

               
                </tr>
                <tr>
                    <th><h6>Codigo Marca</h6></th>
                    <th><h6>Codigo WEB</h6></th>
                    <th><h6>Codigo Marca</h6></th>
                    <th><h6>Codigo WEB</h6></th>
                    <th><h6>Codigo Marca</h6></th>
                    <th><h6>Codigo WEB</h6></th>
                    <th><h6>Codigo Marca</h6></th>
                    <th><h6>Codigo WEB</h6></th>
                    <th><h6>Codigo Marca</h6></th>
                    <th><h6>Codigo WEB</h6></th>
                   
                </tr>
            </thead>
            <tbody>
                <?php $j = 0; ?>
                <tr>
                    <?php 
                        foreach($equivalencias as $equivalencia){ 
                            if($j == 5){ 
                                $j = 0;
                                ?>
                                    </tr><tr>
                                <?php 
                            }
                            if($equivalencia['id_marca'] == $mar['id']){
                        ?>
                                <td>
                                    <?php echo $equivalencia['codigo_marca']; ?>
                                </td>
                                
                                <td>
                                    <?php echo $equivalencia['codigo']; ?>
                                </td>

                            <?php 
                                $j++; 
                            }
                        } 
                        if( $j != 0){
                            for($i = 0; $i < 5 - $j; $i++){
                                ?>
                                <td></td>
                                <td></td>
                                <?php
                            }
                        }
                    ?>
                </tr>
            </tbody>
            <?php 
        }
            ?>
        </table>
</body>
</html>

<?php 
    $html = ob_get_clean();
    require_once('../../../librerias/dompdf/autoload.inc.php');
    use Dompdf\Dompdf;
    use Dompdf\Options;

    $options = new Options();
    $options->set("isRemoteEnabled",TRUE);
    $domPDF = new Dompdf;
    //$options = $dompdf->getOptions();
    $domPDF->loadHTML($html);

    $domPDF->render();
    $pdf = $domPDF->output();
    file_put_contents("./../PDF/equivalencias1.pdf",$pdf);
   // $domPDF->stream("dompdf_out.pdf", array("Attachment" => false));
   // header("location: ./../especificaciones.php?pdf_generado=true");
?>