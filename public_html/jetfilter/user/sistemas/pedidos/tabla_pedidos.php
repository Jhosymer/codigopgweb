
<div class=' bg-white py-3  overflow-auto rounded'>

<div class="container"> 
  <div class="container"> 
  <form action="index.php" method="get">
    <input type="hidden" name="pag" value="pedido"> <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
        <button type="submit" class="btn btn-info m-2 w-100" name="status" value="Por_Procesar">Por Procesar</button>
        <button type="submit" class="btn btn-info m-2 w-100" name="status" value="Procesado">Procesado</button>
        <button type="submit" class="btn btn-info m-2 w-100" name="status" value="Todos">Todos</button>
    </div> 
  </form>
</div>

</div>
<div class="col-12 content">


<table class="table  table-hover  table-responsive table-bordered dataTable"  id="example">
  <thead >
    <tr>
    <th scope="col">#</th>
 
    <th scope="col">Fecha</th>
    <th scope="col">Nro. de Pedido</th>
    <th scope="col">Status</th>
    <th scope="col" >Nro. OC</th>
    <th scope="col">Total</th>
    <th scope="col">Operaciones</th>
     
     
    </tr>
  </thead>
  <tbody class="table-group-divider">

    <?php

$contador = 1;

$filtro = $_GET['status'] ?? 'Por_Procesar'; 

if($filtro == 'Por_Procesar'){
    $wsqli = "SELECT * FROM pedidos where id_users= '$id_users' and (na_pedido = '' OR na_pedido IS NULL) ORDER BY id DESC";
} else if($filtro == 'Procesado'){
   $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' and na_pedido IS NOT NULL AND na_pedido != '' AND stat != 'FB' ORDER BY id DESC";
} else if($filtro == 'Todos'){
    $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' AND stat != 'FB' ORDER BY id DESC";
} else {
    $wsqli = "SELECT * FROM pedidos where id_users = '$id_users' and (na_pedido = '' OR na_pedido IS NULL) ORDER BY id DESC";
}

            
            $result=$linki->query($wsqli);
            if($linki->errno) die($linki->error);
            while($row=$result->fetch_array()){
               $id=$row['id']; 
               $preciototal=$row['total_pedido'];   
               $total_pedido_p = number_format($preciototal, 2, ',', '.') . '$';
                $stat = $row['stat'];
                 
    ?>


  
    <tr>
      <th scope="row"> <?php echo $contador;   ?></th>
    
      <td><?php echo $row['fecha'];   ?></td>
     <?php if ($row['na_pedido'] =='' or  $row['na_pedido'] == NUll) {
       echo "<td> Por Asignar </td>";
         echo "<td> Por Procesar </br>";
          if ($stat == 'C') {
            echo '<span class="badge rounded-pill text-bg-success text-white">Enviado</span>';
        } else {
            echo '<span class="badge rounded-pill text-bg-danger ">Sin Enviar</span>';
        };
            echo "</td>";
      } else { ?>
     <td> <?php echo $row['na_pedido']; ?></td>
     <?php 
       echo "<td> Procesado </td>";
      }
      if (empty($row['numero_oc'])) {
          echo "<td> </td>"; 
      } else {
        
          echo "<td>" . $row['numero_oc'] . "</td>";
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
