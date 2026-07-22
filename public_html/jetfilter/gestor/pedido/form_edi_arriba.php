<div class='container light color_blanco py-3 mt-5 overflow-auto rounded'>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      
<?php
        $wsqli = " SELECT pedidos.id as id_pedido, pedidos.origen as origen, pedidos.fecha_sap as fecha_sap, pedidos.numero_oc, users.rif as 'rif', users.name as 'name',pedidos.total_pedido as total_pedido, pedidos.id_users as id_users, pedidos.na_pedido as na_pedido,
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
            $or_compra =$row['numero_oc'];
            $origen = $row['origen'];
           

            $total_pedido= number_format($total, 2, ',', '.') . '$';
        ?>
            <div class='alert alert-info' role='alert'>
    <div class="d-flex justify-content-between">
        <div>
            <p><b>RIF :</b> <?php echo $rif ?></p>
            <p><b>Cliente :</b> <?php echo $cliente ?></p>
        </div>
        <div>
            <p><b>Nro. Pedido :</b> <?php echo $id_pedido ?></p>
            <p><b>Fecha de Creación:</b> <?php echo $fecha ?></p>
        </div>
    </div>
    
    <?php if ($nsap !== null) { ?>
        <hr> <h4><b>Datos Sap</b></h4>
        <p> <b>Nro. Pedido en SAP: <?php echo $nsap ?> </b></p>
        <p><b>Fecha de Contabilización: <?php echo $fechasap ?></b> </p>
        <p><b> Status : <?php echo "Procesado"?></b></p>
        <?php if ($origen == 'sap') { ?>
           
           <p class="text-muted mb-0 small text-danger" style="font-style: italic;">
        <span class="text-danger">● Pedido importado desde sistema administrativo </span>
        </p>

    
    <?php  }  if ($origen == 'app') { ?>
           
           <p class="text-muted mb-0 small text-primary" style="font-style: italic;">
        <span class="text-primary">● Pedido Creado desde nuestra aplicación </span>
        </p>

    
    <?php  } } else {?>
    
    <p> Status : <?php echo "Por Procesar"?></p>

          <?php
            } 

        if (!empty($or_compra)){?>
    
    <p class="mb-0 me-2">Nro. Orden de compra: <?php echo $or_compra; ?></p>
<?php } ?>
        </div>
        <?php
        }

    

    ?>


