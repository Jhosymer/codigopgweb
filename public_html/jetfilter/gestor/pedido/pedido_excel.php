<?php
    header("Content-Type: application/xls");    
	
    header("Content-Disposition: attachment; filename=pedido" . date('Y:m:d:m:s').".xls");
    header("Pragma: no-cache"); 
    header("Expires: 0");

    include_once('./../../../config/conexion.php');
   
        $id = $_GET['id'];

        $wsqli = " SELECT pedidos.id as id_pedido, users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido,
        pedidos.fecha as fecha, pedidos.stat as stat FROM pedidos INNER JOIN users ON users.id = pedidos.id_users where  pedidos.id = '$id'";
       $result = $base_de_datos->query($wsqli);
       if ($base_de_datos->errorCode() !== '00000') {
           $errorInfo = $base_de_datos->errorInfo();
           die("Query failed: " . $errorInfo[2]);
       }
    
       while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
           $id_pedido = $row['id_pedido'];
           $cliente = $row['name'];
           $fecha= $row['fecha'];
           $stat=$row['stat'];
       ?>
           <table>
           <tr> <td style="border: 1px solid #000; ">Pedido Num: <?php echo  $id_pedido?></td> </tr>
           <tr> <td style="border: 1px solid #000; ">Fecha: <?php echo $fecha?></td> </tr>
           <tr> <td style="border: 1px solid #000; ">Cliente : <?php echo $cliente?></td> </tr>
           <tr> <td style="border: 1px solid #000; ">Status : <?php echo $stat?></td> </tr>
       
           </table>
         <?php
         
           
       }
       
?>

<table>

  <thead>
    <tr>
    <th  style="border: 1px solid #000; ">#</th>
    <th style="border: 1px solid #000; ">Codigo de producto</th>
      <th style="border: 1px solid #000; ">Cantidad</th>
  
    </tr>
  </thead>
  <tbody>
  <?php
$contador = 1;
  $wsqli="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.id_producto as id_pro,lista_pedidos.cancel  as cancel, filtro_codificacion.codigo as codpro ,  filtro_codificacion.descripcion  as nombre, lista_pedidos.cantidad as cantida
  from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id where id_pedido = '$id' and lista_pedidos.cancel <> 1  ";
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

       if ($row2['cancel'] =='1' ) { ?>
        <tr class='gris'><?php
       } else {
       ?>
       <tr>
        <?php } ?>
      <th style="border: 1px solid #000; "> <?php echo $contador;?></th>
      <td style="border: 1px solid #000; "><?php echo $codpro;   ?></td>
 
      <td style="border: 1px solid #000; "><?php echo $cantidad;?></td>

      
      </tr>
      


    <?php
    $contador++; 
    }
   

     

?>

    </tbody>
    </table>

