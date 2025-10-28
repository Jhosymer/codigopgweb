<div class='bg-white p-3 overflow-auto rounded'>
    <div class="row py-5">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            
            <?php
            // Lógica para obtener el encabezado del pedido
            if(isset( $_SESSION['idGenerado']) ) {
                $id_pedido = $_SESSION['idGenerado'];
            }
            $consultapedido = "SELECT * FROM pedidos WHERE id ='$id_pedido'";
            $result = $linki->query($consultapedido);
            if ($linki->errno) die($linki->error);
            while ($row = $result->fetch_array()) {
                $id_pedido = $row['id'];
                $fecha = $row['fecha'];
                $total= $row['total_pedido'];
                $total_pedido = number_format($total, 2, ',', '.') . '$';
            ?>
                <div class='alert alert-info' role='alert'>
                    <div>
                        <p style="display: none;">Pedido Num: <?php echo $id_pedido?></p>
                        <p>Fecha Pedido: <?php echo $fecha ?></p>
                    </div>
                </div>
            <?php
            }
            ?>

            <form id="pedidoForm" action="sistemas/pedidos/crud.php" method="post">
                <table class="table table-bordered table-hover" id="invoiceItem">
                    <thead>
                        <tr>
                            <th style="display: none;"></th>
                            <th width="10%">Codigo Prod.</th>
                            <th width="30%">Nombre Producto</th>
                            <th width="10%">Ud.Emp</th>
                            <th style="display: none;">Stock</th>
                            <th width="10%">Cantidad</th>
                            <th width="15%">precio Und</th>
                            <th width="15%">total</th>
                            <th width="10%">Operacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 1;
                        $wsqli = "SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, lista_pedidos.precio_u as precio, lista_pedidos.total as totallinea, lista_pedidos.cancel as cancel, lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo as codpro, filtro_codificacion.descripcion as nombre, lista_pedidos.cantidad as cantida, filtro_codificacion.stock as stock, filtro_codificacion.und_empaque as undemp
                                from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto = filtro_codificacion.id where id_pedido = '$id_pedido'";
                        $result = $linki->query($wsqli);
                        if ($linki->errno) die($linki->error);
                        while ($row = $result->fetch_array()) {
                            $codpro_disp = $row['codpro'];
                            $nombre_disp = $row['nombre'];
                            $cantidad_disp = $row['cantida'];
                            $id_pro_disp = $row['id_pro'];
                            $id_lista_pedido_disp = $row['id_lista_pedido'];
                            $id_pedido_disp = $row['id_pedido'];
                            $precio_uni_disp = $row['precio'];
                            $total_linea_des_disp = $row['totallinea'];
                            $undemp_disp = $row['undemp'];
                            $stock_disp = $row['stock'];
                            $precio = number_format($precio_uni_disp, 2, ',', '.') . '$';
                            $total_linea = number_format($total_linea_des_disp, 2, ',', '.') . '$';

                            if ($row['cancel'] == '1') { ?>
                                <tr class='gris'>
                            <?php } else { ?>
                                <tr class="item-row" data-id-lista-pedido="<?php echo $id_lista_pedido_disp; ?>" data-id-pro="<?php echo $id_pro_disp; ?>" data-undemp="<?php echo $undemp_disp; ?>" data-stock="<?php echo $stock_disp; ?>" data-precio-u="<?php echo $precio_uni_disp; ?>">
                            <?php } ?>
                                <th scope="row" style="display: none;"><?php echo $contador;?></th>
                                <td class="item-code"><?php echo $codpro_disp; ?></td>
                                <td class="item-name"><?php echo $nombre_disp;?></td>
                                <td class="item-undemp"><?php echo $undemp_disp;?></td>
                                <td style="display: none;"><?php echo $stock_disp;?></td>
                                <td class="item-quantity"><?php echo $cantidad_disp;?></td>
                                <td class="item-precio"><?php echo $precio;?></td>
                                <td class="item-total"><?php echo $total_linea;?></td>
                                <td class="text-center">
                                    <?php if ($row['cancel'] == '0') { ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#" class="btn btn-info editar-item">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger borrar-item-pedido" data-id="<?php echo $id_lista_pedido_disp ?>" data-codpro="<?php echo $codpro_disp ?>">
                                                <i class="align-middle" data-feather="trash"></i>
                                            </a>
                                        </div>
                                    <?php } else { ?>
                                        No Disponible
                                    <?php } ?>
                                </td>
                                </tr>
                            <?php $contador++;
                        } ?>
                        <tr id="agregar-item-row">
                            <td style="display: none;">
                                <input type="hidden" name="id_pro" class="id_pro">
                                <input type="hidden" name="id_lista_pedido" class="id_lista_pedido">
                                <input type="hidden" name="modo" value="<?php echo $modo ?>">
                                <input type="hidden" name="id_pedido" id="id_pedido" value="<?php echo $id_pedido ?>">
                            </td>
                            <td>
                                <input type="text" name="codigo_p" class="form-control item_code" autocomplete="off">
                                <ul class="list-group li_busqueda item_code_list"></ul>
                            </td>
                            <td>
                                <input type="text" name="nombre_p" class="form-control item_name" autocomplete="off">
                                <ul class="list-group li_busqueda item_name_list"></ul>
                            </td>
                            <td><input type="text" name="undemp" class="und_empaque inavi" autocomplete="off"></td>
                            <td style="display: none;">
                                <input type="text" name="stock" class="stock inavi" autocomplete="off">
                            </td>
                            <td><input type="text" name="cantidad" class="form-control quantity" autocomplete="off"></td>
                            <td><input type="text" name="precio_u" class="precio_u inavi" autocomplete="off"></td>
                            <td><input type="text" name="total_pp" class="total_pp inavi" autocomplete="off"></td>
                            <td>
                                <button type="submit" class="<?php echo $colorbn ?> add-item" id="miBotonDeEnvio"><?php echo $nb ?> </button>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <p class="small mt-2">
                Nota importante: La cantidad solicitada debe ser un múltiplo de la unidad de empaque. </br>
                Ejemplo: si un producto viene en cajas de 12 unidades, la cantidad solicitada puede ser 12, 24, 36, etc...
            </p>
            
            <div id="alert-container"></div>
            <div class="py-3">
                <?php
                if (isset($_SESSION['mensajeLista'])) {
                    echo "<div class='text-center alert " . $_SESSION['tm'] . "' role='alert'>";
                    echo $_SESSION['mensajeLista'];
                    echo "</div>";
                    unset($_SESSION['mensajeLista']);
                } ?>
            </div>

        <?php

if(isset($_POST['nuevo'])  ){
include('./sistemas/combos/subir_pedidoExcel.php');  
}


?>
            
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-secondary mt-2 alertotal" role="alert">
                        <?php
                        $total_pedido_display = (isset($total) && $total != 0) ? number_format($total, 2, ',', '.') . '$' : '0,00$';
                        ?>
                        <h4>Total: <?php echo $total_pedido_display; ?></h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-right">
                    <div class="alert alert-light" role="alert">
                        <form action="sistemas/pedidos/crud.php" method="post" id="pedido_form">
                            <input type="hidden" name="id" value="<?php echo $id_pedido ?>">
                            <button type="button" class='btn btn-primary' id="btn_guardar">
                                Guardar
                            </button>
                            <button type="button" class='btn btn-success ml-2' id="btn_enviar">
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>