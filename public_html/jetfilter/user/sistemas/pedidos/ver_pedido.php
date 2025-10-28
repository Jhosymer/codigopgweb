<?php 
  $wsqli="SELECT * from pedidos where id = '$id_pedido'";
  $result=$linki->query($wsqli);
  if($linki->errno) die($linki->error);
  while($row=$result->fetch_array()){
    $id_pedido=$row['id']; 
    $nsap=$row['na_pedido'];
    $total= $row['total_pedido'];
    $fechasap =$row['fecha_sap'];

    $total_pedido= number_format($total, 2, ',', '.') . '$';



?>

<div class="bg-white p-3  overflow-auto rounded">
  <div class='alert alert-info' role='alert'>
     <p >Nro. Pedido: <?php echo $nsap ?></p>

    <div class="d-flex justify-content-between">
        <div>
            <p style="display: none;">Nro. Pedido : <?php echo $id_pedido ?></p>
            <p>Fecha: <?php echo $fecha ?></p>
        </div>
    </div>

    <p> Status : <?php echo "Procesado"?></p>
    
    <?php  /*if ($nsap !== null) { ?>
        <h4 style="display: none;">Datos Sap</h4>
        <p style="display: none;">Nro. Pedido en SAP: <?php echo $nsap ?></p>
        <p style="display: none;">fecha en SAP: <?php echo $fechasap ?></p>
        <p style="display: none;"> Status : <?php echo "Procesado"?></p>
    <?php } else {?>
    
    <p style="display: none;"> Status : <?php echo "Por Procesar"?></p>

          <?php
            } */

        ?>
        </div>
        <?php
        }
  
  ?>
  <div class="mb-4">
   <form action="index.php?pag=pedido" method="post" >
            <button type="submit" class ='btn btn-info' name='cerrar'><?php echo $nbcerrar ?></button>
    </form>

  

    </div>

    <table class="table table-striped table-hover color_blanco table-responsive table-bordered dataTable"  id="example">
     
  <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">Codigo de producto</th>
      <th scope="col">Descripcion</th>
   
      <th scope="col">Cantidad</th>
      <th scope="col">Precio</th>
      <th scope="col">Total</th>
   
     
   
     
     
    </tr>
  </thead>
  <tbody>
  <?php
$contador=1;
  $wsqli="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.precio_u as precio, lista_pedidos.total as total, lista_pedidos.cancel  as cancel, lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo as codpro ,  filtro_codificacion.descripcion  as nombre, lista_pedidos.cantidad as cantida
         from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = '$id_pedido'";
   $result=$linki->query($wsqli);
   if($linki->errno) die($linki->error);
  // $row=$result->fetch_array();
  while($row=$result->fetch_array()){

       $codpro=$row['codpro'];
       $nombre=$row['nombre'];
       $cantidad=$row['cantida'];
       $preciodes=$row['precio'];
       $total_linia=$row['total'];
       $id_pro=$row['id_pro'];
       $id_lista_pedido=$row['id_lista_pedido'];
       $id_pedido=$row['id_pedido'];
       $total= number_format($total_linia, 2, ',', '.') . '$';
       $precio= number_format($preciodes, 2, ',', '.') . '$';
       if ($row['cancel'] =='1' ) { ?>
        <tr class='gris'><?php
       } else {
       ?>
       <tr>
        <?php } ?>
      <th scope="row"> <?php echo $contador;?></th>
      <td><?php echo $codpro;   ?></td>
      <td><?php echo$nombre;?></td>
      <td><?php echo $cantidad;?></td>
      <td><?php echo $precio;?></td>
      <td><?php echo $total;?></td>

    
      </tr>
    


    <?php 
    $contador++;
    }
    ?>

    </tbody>
    </table>
    <div class="alert alert-secondary mt-2 alertotal" role="alert" >
    <h4>Total: <?php echo $total_pedido; ?></h4>
     </div>
    </div>
      </div>
          </div>