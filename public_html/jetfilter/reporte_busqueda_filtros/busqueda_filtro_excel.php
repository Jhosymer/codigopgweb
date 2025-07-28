<?php
    header("Content-Type: application/xls");    
    header("Content-Disposition: attachment; filename=busqueda" . date('Y:m:d:m:s').".xls");
    header("Pragma: no-cache"); 
    header("Expires: 0");

    include_once('./../conexion/conexion.php');
   
    if( isset( $_GET['tipo'] ) ){
        $tipo = $_GET['tipo'];
        $fechaI =  $_GET['fechaI'];
        $fechaF =  $_GET['fechaF'];

        if ($tipo == 'Si' ){
            if ($fechaI == '' or  $fechaF == '' ){
                $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro where respuesta = 1 ";
                

            }else {
    
            $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro where respuesta = 1  AND  fecha BETWEEN '$fechaI' AND '$fechaF'";
        }

    }
    
        else if ($tipo == 'No' ){ 
            if ($fechaI == '' or  $fechaF == '' ){
            $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro where respuesta = 0 ";

        }else {

        $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro where respuesta = 0  AND  fecha BETWEEN '$fechaI' AND '$fechaF'";
    }
        } else { 
            if ($fechaI == '' or  $fechaF == '' ){
                $sql = $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro ";

            }else {
    
            $sql = "SELECT codigo_busq, respuesta, ciudad, estado, fecha FROM busqueda_filtro where  fecha BETWEEN '$fechaI' AND '$fechaF'";
        }
          
        
        }
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
                <h3>Codigo de busqueda</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Respuesta</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Ciudad</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Estado</h3>
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
                <td style="border: 1px solid #000;  mso-number-format:'@';"><?php echo $busqueda_filtros['codigo_busq']; ?></td>
              
                <?php
                if( $busqueda_filtros['respuesta'] == 1 ){
                    ?><td style="border: 1px solid #000; ">SI</td> <?php
            }
            else if( $busqueda_filtros['respuesta'] == 0 ){
                ?><td style="border: 1px solid #000;  ">NO</td> <?php
            }
            ?>
                <?php
                if( $busqueda_filtros['ciudad'] == "" ){
                    ?><td style="border: 1px solid #000; ">NO</td> <?php
            }
            else {
                ?><td style="border: 1px solid #000;  "><?php echo $busqueda_filtros['ciudad']; ?></td> <?php
            }

             if( $busqueda_filtros['estado'] == "" ){
                    ?><td style="border: 1px solid #000; ">NO</td> <?php
            }
            else {
                
                ?><td style="border: 1px solid #000;  "><?php echo $busqueda_filtros['estado']; ?></td> <?php
            }
            ?>
        
                <td style="border: 1px solid #000; "><?php echo $busqueda_filtros['fecha']; ?></td>
                
            </tr>
        <?php 
            }
        ?>
    </tbody>
</table>