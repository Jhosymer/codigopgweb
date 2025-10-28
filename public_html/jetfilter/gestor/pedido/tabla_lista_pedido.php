

<div class='container light color_blanco py-3 mt-5 overflow-auto rounded'>
<table class="table  table-hover  table-responsive table-bordered dataTable"  id="example">
  <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">Codigo Prod.</th>
      <th scope="col">Nombre Producto</th>
   
      <th scope="col">Cantidad</th>
      <th scope="col">precio Und</th>
      <th scope="col">total</th>
      
     
      <th scope="col">Operaciones</th>
     
     
    </tr>
  </thead>
  <tbody>
  <?php
$contador=1;
  $wsqli="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.precio_u as precio, lista_pedidos.total as totallinea,  lista_pedidos.cancel  as cancel, lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo as codpro ,  filtro_codificacion.descripcion  as nombre, lista_pedidos.cantidad as cantida
         from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = '$id_pedido'";
  $result = $base_de_datos->query($wsqli);
   if ($base_de_datos->errorCode() !== '00000') {
       $errorInfo = $base_de_datos->errorInfo();
       die("Query failed: " . $errorInfo[2]);
   }

   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
     $codpro=$row['codpro'];
       $nombre=$row['nombre'];
       $cantidad=$row['cantida'];
       $id_pro=$row['id_pro'];
       $id_lista_pedido=$row['id_lista_pedido'];
       $id_pedido=$row['id_pedido'];
       $precio =$row['precio'];
       $total_linea=$row['totallinea'];
     
     
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
      <td><?php echo $total_linea;?></td>
      </td>
      <td class="text-center"> 

    <?php  if ($row['cancel'] =='0' ) {  ?>
        <a href="index.php?&id_lista_pedido=<?php echo  $id_lista_pedido ?>" class="btn btn-info " ><i class='bx bxs-edit' style='color:#ffffff'></i></a>
  
        <a href="crud.php?id_lista_pedido=<?php echo $id_lista_pedido ?>" class="btn btn-danger " onclick="return confirm('Estas seguro de cancelar el <?php echo $codpro; ?> del pedido ?.')"><i class='bx bx-x'  style='color:#ffffff'></i> </a>
    <?php  } else { ?>
      <a href="crud.php?id_lista_pedido_act=<?php echo $id_lista_pedido ?>" class="btn btn-primary " onclick="return confirm('Estas seguro de Activar el <?php echo $codpro; ?> del pedido ?.')"><i class='bx bx-check'  style='color:#ffffff'></i> </a> 
    <?php } ?>
      </td>
      </tr>
  

    <?php 
    $contador++;
    }
    ?>

    </tbody>
    </table>

    <div class="alert alert-secondary mt-2 alertotal" role="alert" >
    <h5>Total: <?php echo $total_pedido; ?></h5>
     </div>
    </div>
  