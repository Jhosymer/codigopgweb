<?php
    header("Content-Type: application/xls");
    header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=Requerimiento_" . date('Y:m:d:m:s').".xls");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    


    include_once('./../conexion/conexion.php');
   
   // mb_internal_encoding('UTF-8');
   
    
             $fechaI =  $_GET['fechaI'];
             $fechaF =  $_GET['fechaF'];

        
            if ($fechaI == '' or  $fechaF == '' ){
                $sql = "SELECT codigo_fabricante, fabricante, marca, modelo, ano, motor, tlf, email, comentario, fecha FROM requerimiento_filtro";
                

            }else {
    
            $sql = "SELECT codigo_fabricante, fabricante, marca, modelo, ano, motor, tlf, email, comentario, fecha FROM requerimiento_filtro where fecha BETWEEN '$fechaI' AND '$fechaF'";
        }

    $busqueda_seleccionada = $base_de_datos->prepare($sql);
    $busqueda_seleccionada->setFetchMode(PDO::FETCH_ASSOC);
    $busqueda_seleccionada->execute();
    while ( $fila = $busqueda_seleccionada->fetch() ) {
        $busqueda []= $fila;
    } 
    
?>


<head>
    <meta charset="UTF-8">
</head>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; ">
                <h3>Codigo de fabricante</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>fabricante</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Marca</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Modelo</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Año</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Motor</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>tlf</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Email</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Comentario</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Fecha</h3>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
       
       foreach( $busqueda as $busqueda_filtros ){
                
        ?>
            <tr>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['codigo_fabricante']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['fabricante']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['marca']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['modelo']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['ano']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['motor']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['tlf']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['email']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['comentario']; ?></td>
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['fecha']; ?></td>
              
            
                
            </tr>
        <?php 
            }
        ?>
    </tbody>
</table>