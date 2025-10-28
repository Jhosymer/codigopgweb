<?php 

$nameD=$row_offcanvas['name'];
$fechaD=$row_offcanvas['fecha'];
$statD=$row_offcanvas['stat'];

echo "<div class='alert alert-info' role='alert'>
                        <h6 class='offcanvas-title subtito_ms' id='offcanvasRightLabel'> {$nameD}</h6>
                        <h6 class='offcanvas-title' id='offcanvasRightLabel'><strong>Fecha:</strong> {$fechaD}</h6>
                        <h6 class='offcanvas-title' id='offcanvasRightLabel'><strong>Status :</strong> {$statD}</h6>
            </div>";

           
?>

<table class="table  table-hover  table-responsive table-bordered table-sm tablaver"  cellspacing="0" width="100%"  >



<thead>
    <tr>
    <th scope="col">#</th>
               <th scope="col">Código Prod.</th>
              <th scope="col">Nombre Producto</th>
                <th scope="col">Cantidad</th>
              <th scope="col">Precio Und</th>
            <th scope="col">Total</th>
     
     
    </tr>
  </thead>
           
        </tbody>
            <?php
            $contador_tabla2 = 1;
            $idoffcanvas=$row_offcanvas['id_pedido'];
            $wsqli_tabla2 = "SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.precio_u as precio, lista_pedidos.total as totallinea, lista_pedidos.id_producto as id_pro, lista_pedidos.cancel as cancel, filtro_codificacion.codigo as codpro, filtro_codificacion.descripcion as nombre, lista_pedidos.cantidad as cantida
            from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = $idoffcanvas";
            $result_tabla2 = $base_de_datos->query($wsqli_tabla2);
            if ($base_de_datos->errorCode() !== '00000') {
                $errorInfo = $base_de_datos->errorInfo();
                die("Query failed: " . $errorInfo[2]);
            }

            while ($row_tabla2 = $result_tabla2->fetch(PDO::FETCH_ASSOC)) {
                $codpro_tabla2 = $row_tabla2['codpro'];
                $nombre_tabla2 = $row_tabla2['nombre'];
                $cantidad_tabla2 = $row_tabla2['cantida'];
                $precio_tabla2 = $row_tabla2['precio'];
                $total_linea_tabla2 = $row_tabla2['totallinea'];

                if ($row_tabla2['cancel'] == '1') {
                    echo "<tr class='gris'>";
                } else {
                    echo "<tr>";
                }
                ?>
                <th scope='row'><?php echo $contador_tabla2; ?></th>
                <th><?php echo $codpro_tabla2; ?></th>
                <th><?php echo $nombre_tabla2; ?></th>
                <th><?php echo $cantidad_tabla2; ?></th>
                <th><?php echo $precio_tabla2; ?></th>
                <th><?php echo $total_linea_tabla2; ?></th>
            </tr>
                <?php
                $contador_tabla2++;
            }
            ?>
             <tr>
            <td colspan="5" style="text-align: right;"><strong>Total</strong></td>
            <td><strong><?php echo $total; ?></strong></td>
        </tr>
   </tbody>
   
</table>



