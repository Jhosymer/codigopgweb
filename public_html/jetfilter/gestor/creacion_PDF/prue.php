
include('../../../config/conexion.php');
$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Marca
$seleccionado = $base_de_datos->prepare("SELECT * FROM equivalencia_marca WHERE deleted_at IS NULL ORDER BY marca ASC ");
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $marca []= $fila;
}

//Numero Marcas

//Equivalencias
$seleccionado = $base_de_datos->query("SELECT * FROM filtro_equivalencia ORDER BY marca, codigo_marca");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF - Equivalencias</title>
</head>

<style>
    * {
        margin: 0.7em; 
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

<body>
        
    <div style="page-break-after:always;">
        <table>
         
       
            <thead>
                <tr>
                    <th class="titulo" colspan="6"><h3>Equivalencias</h3></th>
                    <th class="fecha" width="20Px" colspan="4"><img src="<?php echo $base64; ?>"></th>
                </tr>
                <tr>
                    <th class="fecha" colspan="10"><?php echo "$fechaActual";?></th> </tr>
                <tr>
                <?php 
        foreach($marca as $mar){
    ?>
                 
              <tr>
                    <th class="marca" colspan="2"><?php echo $mar['marca']; ?></th>
           
                    <th class="marca" colspan="2"><?php echo $mar['marca']; ?></th>
                   
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
             
                <tr>
                    <?php
                        $j = 0; 
                        foreach($equivalencias as $equivalencia){
                            if($j == 5){ $j = 0;?> 
                            </tr><tr>
                            <?php }
                            if($equivalencia['id_marca'] == $mar['id']){
                    ?>

                                    <td><?php echo $equivalencia['codigo_marca']; ?></td>
                                    <td><?php echo $equivalencia['codigo']; ?></td>
                                
                    <?php 
                                $j++;
                            }
                        }
                    ?>

                </tr>
                
            </tbody>
            
      <?php }?>
        </table>
        
        </div>
  
</body>
</html>
