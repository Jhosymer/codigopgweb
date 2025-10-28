<html>
    <head>
   
   
   
 
    <link rel="stylesheet" href="./../css/css_vende/bootstrap.min.css">
<link rel="stylesheet" href="./../css/css_vende/dataTables.bootstrap5.css">
<link rel="stylesheet" href="./../vendor/boxicons/css/boxicons.min.css">
    <script src="./../js/js_vende/jquery-3.7.1.js"></script>
    <script src="./../js/js_vende/dataTables.js"></script>
     <script src="./../js/js_vende/dataTables.bootstrap5.js"></script>
    </head>
    <body>
    <table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>
    <th scope="col">#</th>
    <th scope="col">Nombre de Cliente</th>
    <th scope="col">Numero de Pedido</th>
      <th scope="col">fecha</th>
     
      <th scope="col">status</th>
      <th scope="col">Operaciones</th>
     
    <?php  include_once('./../conexion/conexion.php');?>
    </tr>
  </thead>
  <tfoot>
        <tr>
        <th scope="col">#</th>
    <th scope="col">Nombre de Cliente</th>
    <th scope="col">Numero de Pedido</th>
      <th scope="col">fecha</th>
     
      <th scope="col">status</th>
      <th scope="col">Operaciones</th>
        </tr>
      </tfoot>
  <tbody>



    <?php 

    
   
  // $wsqli = "SELECT * FROM pedidos";

  

  $wsqli = " SELECT pedidos.id as id_pedido, users.name as name, pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha, pedidos.stat as stat FROM pedidos INNER JOIN users ON users.id = pedidos.id_users";
   $result = $base_de_datos->query($wsqli);
   if ($base_de_datos->errorCode() !== '00000') {
       $errorInfo = $base_de_datos->errorInfo();
       die("Query failed: " . $errorInfo[2]);
   }

   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
       $id = $row['id_pedido'];
       // Process the retrieved data here
    ?>


  
    <tr>
      <th scope="row"> <?php echo $row['id_pedido'];   ?></th>
      <td><?php echo $row['name']; ?> </td>
      <td><?php if ($row['na_pedido'] =='' or  $row['na_pedido'] == NUll) {
        echo "Por Asignar ";
      } else { echo $row['na_pedido']; }
      
       ?></td>
      <td><?php echo $row['fecha'];   ?></td>
     
  
      <td> <?php echo $row['stat']; ?> </td>
  
      <td class="text-center"> 
      <?php if ($row['stat'] =='Por Procesar' ) {?>
        <a href="index.php?pag=pedido&id=<?php echo $id ?>" class="btn btn-info" ><i class='bx bxs-edit' style='color:#ffffff'></i></a>
        <?php }
        else  {  ?>
          <a href="index.php?pag=pedido&id_ver=<?php echo $id ?>" class="btn btn-primary" ><i class='bx bxs-search' style='color:#ffffff'></i></a>
          <?php  }
      
      ?>

        <a href="sistemas/pedidos/crud.php?id=<?php echo $id ?>" class="btn btn-danger " onclick="return confirm('Estas seguro de eliminar este registro de pedidos?.')"> <i class='bx bxs-trash-alt '  style='color:#ffffff'></i> </a>
      </td>
     
    </tr>
    


<?php 
}


?>

</tbody>
</table>
    </body>
    </html>
    <script>
    $(document).ready(function() {
        $('#example').DataTable( {
            initComplete: function () {
                this.api().columns([1]).every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
     
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
     
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }
        } );
    } );
    </script>