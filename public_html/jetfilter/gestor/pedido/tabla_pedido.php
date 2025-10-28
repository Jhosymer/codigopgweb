

<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>

 <!--<div class="row">
        <div class ='col-lg-5'>
           <form action="combos/actualizar_stock.php" method="POST" enctype="multipart/form-data">
              <label for="excelFile" class="btn btn-success m-2 w-80 text-white">Actualizar stock</label>
              <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" style="display: none;">
              <button id="bt_actualizar_stock" class="btn btn-primary m-2" style="display: none;">Actualizar datos</button>
          </form>

        </div>
     </div>-->
     <?php //include('combos/selccion_excel.php'); ?>

<h1 class="titulo text-center ">Pedidos</h1>

<?php include('combos/mensaje.php'); ?>

<div class="container"> 
  <form action="index.php" method="post" >
            <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
                <button type="submit" class="btn btn-info m-2 w-100 text-white" name="Por Procesar" id="">Por Procesar</button>
                <button type="submit" class="btn btn-info m-2 w-100 text-white" name="Procesado" id="">Procesado</button>
                <button type="submit" class="btn btn-info m-2 w-100 text-white" name="Todos" id="">Todos</button>
               
              </div> 
              </form>

</div>


<table class="table table-striped table-hover color_blanco  table-responsive table-bordered"  id="example" cellspacing="0" width="100%">
  <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">Nombre de Cliente</th>
    <th scope="col">Nro. de Pedido</th>
    <th scope="col">fecha</th>
    
    <th scope="col">Nro de Pedido en  SAP</th>
     <th scope="col">status</th>
     <th scope="col">Total</th>
      
      
      <th scope="col">Operaciones</th>
     
     
    </tr>
  </thead>

  <tbody>



    <?php 
 $contador=1;
    
   
  // $wsqli = "SELECT * FROM pedidos";
  if(isset($_POST['Por_Procesar']) ){
    $wsqli = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido' , users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha FROM pedidos INNER JOIN users ON users.id = pedidos.id_users where  na_pedido = '' OR na_pedido IS NULL";
  } else if(isset($_POST['Procesado']) ){
    $wsqli = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido' , users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha FROM pedidos INNER JOIN users ON users.id = pedidos.id_users where   na_pedido IS NOT NULL AND na_pedido != ''";
  } else if(isset($_POST['Todos']) ){
    $wsqli = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido' , users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha FROM pedidos INNER JOIN users ON users.id = pedidos.id_users";
  } else {
     $wsqli = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido' , users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha FROM pedidos INNER JOIN users ON users.id = pedidos.id_users where  na_pedido = '' OR na_pedido IS NULL ";

  }
  

  
   $result = $base_de_datos->query($wsqli);
   if ($base_de_datos->errorCode() !== '00000') {
       $errorInfo = $base_de_datos->errorInfo();
       die("Query failed: " . $errorInfo[1]);
   }

   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
       $id = $row['id_pedido'];
       $cliente = $row['name'];
      $total= $row['totalpedido'];
      $total_pedido_p = number_format($total, 2, ',', '.') . '$';
       // Process the retrieved data here
       
    ?>


  
    <tr>
    <th scope="row"> <?php echo $contador;   ?></th>
    <td><?php echo $cliente; ?> </td>
      <th> <?php echo $row['id_pedido'];   ?></th>
      </td>
      <td><?php echo $row['fecha'];   ?></td>
    
      <?php if ($row['na_pedido'] =='' or  $row['na_pedido'] == NUll) {
        echo "<td> Por Asignar </td>";
        echo "<td> Por Procesar  </td>";
      

      } else { 
        echo "<td>".$row['na_pedido']."</td>";  
        echo "<td> Procesado </td>";
       
      }
      
       ?> 
     
   <td> <?php echo $total_pedido_p; ?> </td>
        <td class="text-center"> 

         <a href="index.php?&id_ver=<?php echo $id ?>" class="btn btn-primary" ><i class='bx bxs-search' style='color:#ffffff'></i></a>
          </td>
      
     
    </tr>
    
   

<?php 
$contador++; 
}


?>

</tbody>
<tfoot>
    <tr>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
  </tfoot>
</table>
</div>