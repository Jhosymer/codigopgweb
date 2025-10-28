<div class='container light color_blanco py-3 mt-5 overflow-auto rounded'>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
<?php
        $wsqli = " SELECT pedidos.id as id_pedido, pedidos.fecha_sap as fecha_sap, users.rif as 'rif', users.name as 'name',pedidos.total_pedido as total_pedido, pedidos.id_users as id_users, pedidos.na_pedido as na_pedido,
         pedidos.fecha as fecha FROM pedidos INNER JOIN users ON users.id = pedidos.id_users where  pedidos.id = '$id'";
        $result = $base_de_datos->query($wsqli);
        if ($base_de_datos->errorCode() !== '00000') {
            $errorInfo = $base_de_datos->errorInfo();
            die("Query failed: " . $errorInfo[2]);
        }
     
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id_pedido = $row['id_pedido'];
            $cliente = $row['name'];
            $fecha= $row['fecha'];
            
            $rif=$row['rif'];
            $nsap=$row['na_pedido'];
            $total= $row['total_pedido'];
            $fechasap= $row['fecha_sap'];

            $total_pedido= number_format($total, 2, ',', '.') . '$';
        ?>
            <div class='alert alert-info' role='alert'>
    <div class="d-flex justify-content-between">
        <div>
            <p><b>RIF :</b> <?php echo $rif ?></p>
            <p><b>Cliente :</b> <?php echo $cliente ?></p>
        </div>
        <div>
            <p>Nro. Pedido : <?php echo $id_pedido ?></p>
            <p>Fecha: <?php echo $fecha ?></p>
        </div>
    </div>
    
    <?php if ($nsap !== null) { ?>
        <hr> <h4>Datos Sap</h4>
        <p>Nro. Pedido en SAP: <?php echo $nsap ?></p>
        <p>fecha en SAP: <?php echo $fechasap ?></p>
        <p> Status : <?php echo "Procesado"?></p>
    <?php } else {?>
    
    <p> Status : <?php echo "Por Procesar"?></p>

          <?php
            } 

        ?>
        </div>
        <?php
        }

    ?>


