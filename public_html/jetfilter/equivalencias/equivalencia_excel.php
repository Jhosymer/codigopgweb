<?php
    header("Content-Type: application/xls");    
	
    header("Content-Disposition: attachment; filename=Equivalencias" . date('Y:m:d:m:s').".xls");
    header("Pragma: no-cache"); 
    header("Expires: 0");

    include_once('./../conexion/conexion.php');
    if( isset( $_GET['pagina'] ) && $_GET['registros'] ){
        $limit = $_GET['registros'];
        $page = $_GET['pagina'];
        $inicio = $limit * ($page - 1);

        $sql = "SELECT codigo, marca, codigo_marca FROM filtro_equivalencia
                        WHERE ( deleted_at is null )
                        LIMIT $inicio, $limit";
    }
    else {
        $sql = "SELECT codigo, marca, codigo_marca FROM filtro_equivalencia
                        WHERE ( deleted_at is null )";
    }
    $equivalencias_seleccionada = $base_de_datos->prepare($sql);
    $equivalencias_seleccionada->setFetchMode(PDO::FETCH_ASSOC);
    $equivalencias_seleccionada->execute();
    while ( $fila = $equivalencias_seleccionada->fetch() ) {
        $equivalencias []= $fila;
    } 
?>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; ">
                <h3>Codigo</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Marca</h3>
            </th>
            <th style="border: 1px solid #000; ">
                <h3>Codigo Marca</h3>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
            foreach( $equivalencias as $equivalencia ){
        ?>
            <tr>
                <td style="border: 1px solid #000; "><?php echo $equivalencia['codigo']; ?></td>
                <td style="border: 1px solid #000; "><?php echo $equivalencia['marca']; ?></td>
                <td style="border: 1px solid #000; "><?php echo $equivalencia['codigo_marca']; ?></td>
            </tr>
        <?php 
            }
        ?>
    </tbody>
</table>