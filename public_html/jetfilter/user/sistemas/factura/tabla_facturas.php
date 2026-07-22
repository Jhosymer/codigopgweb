<?php
// 1. Contamos cuántas facturas "N" (Nuevas) tiene este usuario
$check_nuevas = $linki->query("SELECT COUNT(*) as total FROM factura WHERE visto = 'N' AND nota_credito IS NULL AND id_users = '$id_users'");
$res_check = $check_nuevas->fetch_assoc();
$total_nuevas = $res_check['total'];
$hay_nuevas = $total_nuevas > 0;

//Capturamos el filtro desde el GET
$filtro = $_GET['filtro'] ?? '';
?>
<div class='bg-white py-3 overflow-auto rounded'>
    <?php 
// Si hay nuevas, o si el usuario ya hizo clic en algún filtro, mostramos los botones.
// Si prefieres que desaparezcan SIEMPRE que total_nuevas sea 0, usa solo: if ($hay_nuevas)
if ($hay_nuevas || !empty($filtro)): 
?>
    <div class="container"> 
        <form action="index.php" method="get">
            <input type="hidden" name="pag" value="factura">
            
            <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
                
                <?php if ($hay_nuevas): ?>
                <button type="submit" name="filtro" value="nueva" class="btn btn-primary m-2 w-100">
                    <i class='bx bxs-bell-ring'></i> Nuevas (<?php echo $total_nuevas; ?>)
                </button>
                <?php endif; ?>

                <button type="submit" name="filtro" value="revisadas" class="btn btn-info m-2 w-100 text-white">
                    <i class='bx bx-check-double'></i> Revisadas
                </button>

                <button type="submit" name="filtro" value="todos" class="btn btn-secondary m-2 w-100">
                    <i class='bx bx-list-ul'></i> Todas
                </button>

            </div> 
        </form>
    </div>
<?php endif; ?>


<div class="col-12 content">


<table class="table table-hover color_blanco table-responsive table-bordered dataTable"  id="example">
  <thead>
    <tr>
    <th scope="col">#</th>
 
    <th scope="col">Fecha contabilización</th>
    <th scope="col">Nro. de Factura</th>
    <th scope="col">Total</th>
    <th scope="col">Operaciones</th>
     
     
    </tr>
  </thead>
  <tbody>

    <?php

$contador = 1;

$filtro = $_GET['filtro'] ?? '';

if ($filtro == 'nueva') {
    $wsqli = "SELECT * FROM factura WHERE id_users = '$id_users' AND visto = 'N' AND nota_credito IS NULL ORDER BY id DESC";
} else if ($filtro == 'revisadas') {
    $wsqli = "SELECT * FROM factura WHERE id_users = '$id_users' AND visto = 'Y' AND nota_credito IS NULL ORDER BY id DESC";
} else if ($filtro == 'todos') {
    $wsqli = "SELECT * FROM factura WHERE id_users = '$id_users' AND nota_credito IS NULL ORDER BY id DESC";
} else {
    // Comportamiento inicial por defecto
    if ($hay_nuevas) {
        $wsqli = "SELECT * FROM factura WHERE id_users = '$id_users' AND visto = 'N' AND nota_credito IS NULL ORDER BY id DESC";
    } else {
        $wsqli = "SELECT * FROM factura WHERE id_users = '$id_users' AND nota_credito IS NULL ORDER BY id DESC";
    }
}
  

            
            $result=$linki->query($wsqli);
            if($linki->errno) die($linki->error);
            while($row=$result->fetch_array()){
               $id=$row['id']; 
               $preciototal=$row['total_fact'];   
               $total_factura = number_format($preciototal, 2, ',', '.') . '$';
                 
    ?>


  
    <tr>
      <th scope="row"> <?php echo $contador;   ?></th>
    
      <td><?php echo $row['fecha_contab'];   ?></td>
         <td><?php echo $row['num_fact'];   ?></td>
     
     

      <td> <?php echo $total_factura ; ?> </td>
  
      <td class="text-center"> 
         
          <a href="index.php?pag=factura&id_ver=<?php echo $id ?>" class="btn btn-primary" ><i class="align-middle" data-feather="search"></i></a>
      
 
          
       
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
