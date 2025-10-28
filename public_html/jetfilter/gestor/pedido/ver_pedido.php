<?php 

$id = $id_pedido;


  include('form_edi_arriba.php');

 
  
  ?>
   <input type="hidden" id="id" value=<?php echo $id; ?> >
<div class="mt-2">
    <div class="row">
        <div class="col-lg-1 col-md-2 col-sm-2 mt-2 ">
            <form action="index.php" method="post" id='cerrar'>
                <button type="submit" class='btn btn-secondary' name='cerrar' id='cerrar'>Atras</button>
            </form>
        </div> 
        <div class="col-lg-1 col-md-2 col-sm-2 mt-2">
            <a  id="descargar_excel" class="btn btn-success"><i class='bx bx-download' style='color:#ffffff'></i></a>
        </div>

    
       
    </div>

    <?php include('combos/mensaje.php'); ?>
</div>

<table class="table  table-hover  table-responsive table-bordered dataTable"  id="example">

  <thead>
    <tr>
    
    <th scope="col">#</th>
    <th scope="col">Codigo Prod.</th>
    <th scope="col">Nombre Producto</th>
    <th scope="col">Cantidad</th>
    <th scope="col">precio Und</th>
    <th scope="col">total</th>
    </tr>
  </thead>
  <tbody>
  <?php
$contador = 1;
  $wsqli="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido,lista_pedidos.precio_u as precio, lista_pedidos.total as totallinea, lista_pedidos.id_producto as id_pro,lista_pedidos.cancel  as cancel, filtro_codificacion.codigo as codpro ,  filtro_codificacion.descripcion  as nombre, lista_pedidos.cantidad as cantida
  from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = '$id_pedido'";
   $result2 = $base_de_datos->query($wsqli);
   if ($base_de_datos->errorCode() !== '00000') {
       $errorInfo = $base_de_datos->errorInfo();
       die("Query failed: " . $errorInfo[2]);
   }

   while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
       
       $codpro=$row2['codpro'];
       $nombre=$row2['nombre'];
       $cantidad=$row2['cantida'];
       $id_pro=$row2['id_pro'];
       $id_lista_pedido=$row2['id_lista_pedido'];
       $id_pedido=$row2['id_pedido'];
       $precio =$row2['precio'];
       $total_linea=$row2['totallinea'];

       if ($row2['cancel'] =='1' ) { ?>
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

      
      </tr>
      


    <?php
    $contador++; 
    }
   

     

?>

    </tbody>
    </table>

    </div>

    <div class="alert alert-secondary mt-2 alertotal" role="alert" >
    <h5>Total: <?php echo $total_pedido; ?></h5>
     </div>
    </div>



  
<script>
  descargar_excel = document.getElementById('descargar_excel');
    descargar_excel.addEventListener('click', () => {
        id = document.getElementById('id').value;
        window.location.href = `./pedido_excel.php?id=${id}`;
    })
</script>
   
