
<div class='bg-white p-3 mt-5 overflow-auto rounded'>
<table class="table table-hover table-responsive table-bordered dataTable"  id="example">
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
$contador =1;
  $wsqli="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.precio_u as precio, lista_pedidos.total as totallinea,  lista_pedidos.cancel  as cancel, lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo as codpro ,  filtro_codificacion.descripcion  as nombre, lista_pedidos.cantidad as cantida
         from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = '$id_pedido'";
   $result=$linki->query($wsqli);
   if($linki->errno) die($linki->error);
  // $row=$result->fetch_array();
  while($row=$result->fetch_array()){

       $codpro=$row['codpro'];
       $nombre=$row['nombre'];
       $cantidad=$row['cantida'];
       $id_pro=$row['id_pro'];
       $id_lista_pedido=$row['id_lista_pedido'];
       $id_pedido=$row['id_pedido'];
       $precio_uni =$row['precio'];
       $total_linea_des=$row['totallinea'];

       $precio= number_format($precio_uni, 2, ',', '.') . '$';
       $total_linea= number_format($total_linea_des, 2, ',', '.') . '$';

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
        <?php
      if ($row['cancel'] =='0' ) { ?>
       <a href="index.php?pag=pedido&id_lista_pedido=<?php echo  $id_lista_pedido ?>" class="btn btn-info " ><i class="align-middle" data-feather="edit"></i></a>
  <a href="#" class="btn btn-danger borrar-item-pedido" data-id="<?php echo $id_lista_pedido ?>" data-codpro="<?php echo $codpro ?>">
    <i class="align-middle" data-feather="trash"></i>
</a><?php
       } else {
       ?>
        No Disponible
        <?php } ?>

        
    
    </td>
      </tr>
    


    <?php 
    $contador++;
    }
    ?>

    </tbody>
    </table>

  <div class="row">
    <div class="col-12">
        <div class="alert alert-secondary mt-2 alertotal" role="alert">
            <h4>Total: <?php echo $total_pedido; ?></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-right">
        <div class="alert alert-light" role="alert">
            <form action="sistemas/pedidos/crud.php" method="post" id="pedido_form">
                <input type="hidden" name="id" value="<?php echo $id_pedido ?>">

                <button type="button" class='btn btn-primary' id="btn_guardar">
                    Guardar
                </button>

                <button type="button" class='btn btn-success ml-2' id="btn_enviar">
                    Enviar
                </button>
            </form>
        </div>
    </div>
</div>


</div>





 