<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>

<h1 class="titulo text-center ">Procesar Pedidos</h1>

<?php include('combos/mensaje.php'); ?>
<div class="d-flex justify-content-between mt-5 mb-5">
    <div class="d-flex">
    <label for="idcliente" class="me-2 subtito_ms">Cliente:</label>
    <select name="idcliente" id="idcliente" class="form-select me-3" style="width: 250px;" onchange="cargarDatos()">
        <?php include_once("combos/Cliente_pedidos_por_procesar.php") ?>
    </select>
    </div>
    <form action="index.php" method="post" >
    <button name="En Proceso" class="btn-icon me-4">Volver</button>
    </form>
</div>

<div style="display: none;" id="miDiv">
    <form action="crup_predespacho.php" method="post">
        <table class="table table-striped table-hover color_blanco  table-responsive table-bordered"  cellspacing="0" width="100%"  >
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col"></th>
            <th scope="col">Nombre de Cliente</th>
            <th scope="col">Nro. de Pedido</th>
            <th scope="col">fecha</th>
            <th scope="col">Nro de Pedido en  SAP</th>
            <th scope="col">status</th>
            <th scope="col">Total</th>
            <th scope="col">Operaciones</th>
            </tr>
        </thead>

        <tbody id="tablaResultados">
        </tbody>
        
        <tfoot>
            <tr>
                <th scope="col" colspan="8" ></th>
                <th class='text-center'>
                        <button type="submit" class="btn-icon">Crear</button>
                </th>
            </tr>
        </tfoot>
        </table>
    </form>
</div>





<script src="<?php echo $loc; ?>js/js_vende/cargardatos_procesar_pedido.js"></script>



<?php
 $wsqli_offcanvas = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido', users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha, pedidos.stat as stat FROM pedidos INNER JOIN users ON users.id = pedidos.id_users WHERE stat = 'En Proceso'";

    $wsqli_offcanvas = $base_de_datos->query($wsqli_offcanvas);
    if ($base_de_datos->errorCode() !== '00000') {
        $errorInfo = $base_de_datos->errorInfo();
        die("Query failed: " . $errorInfo[1]);
    }


    while ($row_offcanvas = $wsqli_offcanvas->fetch(PDO::FETCH_ASSOC)) {
        $total = number_format($row_offcanvas['totalpedido'], 2, ',', '.') . '$';
        echo "<div class='offcanvas offcanvas-end '  tabindex='-1' id='offcanvasRight-{$row_offcanvas['id_pedido']}' aria-labelledby='offcanvasRightLabel'>
                <div class='offcanvas-header'>
                    <h5 class='subtito_ms' id='offcanvasRightLabel'> Pedido Nro. {$row_offcanvas['id_pedido']}</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='offcanvas' aria-label='Close'></button>
                </div>
                <div class='offcanvas-body'>";
        
                  
                    include("offcanvas_content.php");
        
         echo "</div>
              </div>";

    }


    ?>

   