
<div class=' bg-white py-3  overflow-auto rounded'>

<div class="container"> 
  <form action="index.php?pag=pedido" method="post" >
            <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
                <button type="submit" class="btn btn-info m-2 w-100" name="Por_Procesar" id="">Por Procesar</button>
                <button type="submit" class="btn btn-info m-2 w-100" name="Procesado" id="">Procesado</button>
                <button type="submit" class="btn btn-info m-2 w-100" name="Todos" id="">Todos</button>
            
                
              </div> 
              </form>

</div>
<div class="col-12 content">


<table class="table table-striped table-hover color_blanco table-responsive table-bordered dataTable"  id="example">
  <thead>
    <tr>
    <th scope="col">#</th>
 
    <th scope="col">Fecha</th>
    <th scope="col">Nro. de Pedido</th>
    <th scope="col">Status</th>
    <th scope="col">Total</th>
    <th scope="col">Operaciones</th>
     
     
    </tr>
  </thead>
  <tbody>

    <?php

$contador = 1;

if(isset($_POST['Por_Procesar']) ){

      $wsqli = "SELECT * FROM pedidos where id_users= '$id_users' and (na_pedido = '' OR na_pedido IS NULL) ORDER BY `pedidos`.`id` DESC";
  } else if(isset($_POST['Procesado']) ){
      $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' and na_pedido IS NOT NULL AND na_pedido != '' ORDER BY `pedidos`.`id` DESC ";
  } else if(isset($_POST['Todos']) ){
      $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' ";
  } else {
    
      $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' and (na_pedido = '' OR na_pedido IS NULL) ORDER BY `pedidos`.`id` DESC";
  }

            
            $result=$linki->query($wsqli);
            if($linki->errno) die($linki->error);
            while($row=$result->fetch_array()){
               $id=$row['id']; 
               $preciototal=$row['total_pedido'];   
               $total_pedido_p = number_format($preciototal, 2, ',', '.') . '$';
                 
    ?>


  
    <tr>
      <th scope="row"> <?php echo $contador;   ?></th>
    
      <td><?php echo $row['fecha'];   ?></td>
     <?php if ($row['na_pedido'] =='' or  $row['na_pedido'] == NUll) {
       echo "<td> Por Asignar </td>";
        echo "<td> Por Procesar  </td>";
      } else { ?>
     <td> <?php echo $row['na_pedido']; ?></td>
     <?php 
       echo "<td> Procesado </td>";
      }
       ?>
    
     

      <td> <?php echo $total_pedido_p; ?> </td>
  
      <td class="text-center"> 
         <?php if ($row['stat'] =='G' or $row['stat'] =='' ) {?>
        <a href="index.php?pag=pedido&id=<?php echo $id ?>" class="btn btn-info" ><i class="align-middle" data-feather="edit"></i></a>
        
     <a href="#" class="btn btn-danger borrar-pedido" data-id="<?php echo $id ?>" >
    <i class="align-middle" data-feather="trash"></i>
     </a>
        <?php }
        else  {  ?>
          <a href="index.php?pag=pedido&id_ver=<?php echo $id ?>" class="btn btn-primary" ><i class="align-middle" data-feather="search"></i></a>
          <?php  } ?>
 
          
      


       
      </td>
     
    </tr>
    


<?php
$contador++; 
}


?>

</tbody>
</table>
</div>
</div>
