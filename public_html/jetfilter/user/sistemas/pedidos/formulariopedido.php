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
                $total = $row['total_pedido'];
                $total_pedido = number_format($total, 2, ',', '.') . '$';
            ?>
                <div class='alert alert-info py-3' role='alert'>
                    <div>
                        <p style="display: none;">Pedido Num: <?php echo $id_pedido?></p>
                        <p class="mb-2">Fecha Pedido: <?php echo $fecha ?></p>
                        
                        <div class="d-flex align-items-center">
                            <span class="me-1">Nro. Orden de compra: </span>
                            
                            <div id="oc_view" class="d-inline-flex align-items-center" data-id="<?php echo $id_pedido; ?>">
                                <span id="display_oc" class="fw-bold me-2">
                                    <?php echo !empty($row['numero_oc']) ? $row['numero_oc'] : '____'; ?>
                                </span>
                                <a href="javascript:void(0)" onclick="toggleOC(true)" class="text-primary">
                                    <i class='bx bx-edit' style="font-size: 1.2rem; vertical-align: middle;"></i>
                                </a>
                            </div>

                            <div id="oc_edit" class="d-none">
                                <div class="input-group input-group-sm" style="max-width: 200px;">
                                    <input type="text" id="input_oc" class="form-control" value="<?php echo $row['numero_oc']; ?>">
                                    <button class="btn btn-success btn-sm" onclick="saveOC()"> <i class='bx bx-check'></i></button>
                                    <button class="btn btn-danger btn-sm border" onclick="toggleOC(false)"><i class='bx bx-x'></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <form id="pedidoForm" action="sistemas/pedidos/crud.php" method="post">
             <input type="hidden" id="usuario_logueado" value="<?php echo $id_users; ?>">
                <table class="table table-bordered table-hover" id="invoiceItem">
                    <thead>
                        <tr>
                           <th style="display: none;"></th>
                            <th  class="col-codigo" width="10%">Codigo Prod.</th>
                            <th width="30%" class="col-nombre">Nombre Producto</th>
                            <th width="10%">Ud.Emp</th>
                            <th style="display: none;">Stock</th>
                            <th width="10%">Cantidad</th>
                            <th width="15%" class="col-precio">precio Und</th>
                            <th width="15%" class="col-total" >total</th>
                            <th width="10%">Operacion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 1;
                        // Query actualizado con LEFT JOIN para suma de stock SAP
                        $wsqli = "SELECT 
                                    lp.id_pedido, lp.id AS id_lista_pedido, lp.precio_u AS precio, 
                                    lp.total AS totallinea, lp.cancel, lp.id_producto AS id_pro, 
                                    lp.cantidad AS cantida,
                                    f.codigo AS codpro, f.descripcion AS nombre, f.und_empaque AS undemp,
                                    f.stock AS stock1, f.disponible_inmediata, f.act_sap AS act1,
                                    SUM(COALESCE(t2.stock, 0)) AS stock2,
                                    MAX(COALESCE(t2.act_sap, 'N')) AS act2
                                  FROM lista_pedidos lp 
                                  INNER JOIN filtro_codificacion f ON lp.id_producto = f.id 
                                  LEFT JOIN filtro_alternativo_sap t2 ON f.id = t2.id_codigo
                                  WHERE lp.id_pedido = '$id_pedido'
                                  GROUP BY lp.id";

                        $result = $linki->query($wsqli);
                        if ($linki->errno) die($linki->error);
                        
                        while ($row = $result->fetch_array()) {
                            // Lógica de suma de Stock SAP
                           $stockReal = 0;
                            if ($row['act1'] == 'Y' && $row['act2'] == 'Y') { 
                                $stockReal = (int)$row["stock1"] + (int)$row["stock2"]; 
                            } else if ($row['act1'] == 'Y' && $row['act2'] == 'N') { 
                                $stockReal = (int)$row["stock1"]; 
                            } else if ($row['act2'] == 'Y' && $row['act1'] == 'N') { 
                                $stockReal = (int)$row["stock2"]; 
                            }

                            // Lógica de Disponibilidad (Puntos)
                            $meta = (int)$row['disponible_inmediata'];
                            if ($meta <= 0) {
                                $clasePunto = "dot-info"; $mensaje = "stock no configurado"; $claseTooltip = "tooltip-info";
                            } else {
                                $d10 = $meta * 0.10;
                                $d30 = $meta * 0.30;
                                if ($stockReal <= $d10) {
                                    $clasePunto = "dot-danger"; $mensaje = "Consulta con Ventas"; $claseTooltip = "tooltip-danger";
                                } elseif ($stockReal <= $d30) {
                                    $clasePunto = "dot-warning"; $mensaje = "Poca Disponibilidad"; $claseTooltip = "tooltip-warning";
                                } else {
                                    $clasePunto = "dot-success"; $mensaje = "Disponibilidad Inmediata"; $claseTooltip = "tooltip-success";
                                }
                            }

                            $precio = number_format($row['precio'], 2, ',', '.') . '$';
                            $total_linea = number_format($row['totallinea'], 2, ',', '.') . '$';

                            $filaClase = ($row['cancel'] == '1') ? 'gris' : 'item-row';
                            ?>
                            <tr class="<?php echo $filaClase; ?>" 
                                data-id-lista-pedido="<?php echo $row['id_lista_pedido']; ?>" 
                                data-id-pro="<?php echo $row['id_pro']; ?>" 
                                data-undemp="<?php echo $row['undemp']; ?>" 
                                data-stock="<?php echo $stockReal; ?>" 
                                data-precio-u="<?php echo $row['precio']; ?>">
                                
                                <th scope="row" style="display: none;"><?php echo $contador;?></th>
                                <td class="item-code">
                                    <span class="dot-status <?php echo $clasePunto; ?> me-2" 
                                          style="cursor:pointer;"
                                          data-bs-toggle="tooltip" 
                                          data-bs-custom-class="<?php echo $claseTooltip; ?>" 
                                          title="<?php echo $mensaje; ?>"></span>
                                    <?php echo $row['codpro']; ?>
                                </td>
                                <td class="item-name"><?php echo $row['nombre'];?></td>
                                <td class="item-undemp"><?php echo $row['undemp'];?></td>
                                <td style="display: none;"><?php echo $stockReal;?></td>
                                <td class="item-quantity"><?php echo $row['cantida'];?></td>
                                <td class="item-precio"><?php echo $precio;?></td>
                                <td class="item-total"><?php echo $total_linea;?></td>
                                <td class="text-center">
                                    <?php if ($row['cancel'] == '0') { ?>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#" class="btn btn-info editar-item">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger borrar-item-pedido" data-id="<?php echo $row['id_lista_pedido'] ?>" data-codpro="<?php echo $row['codpro'] ?>">
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
    <div class="d-flex align-items-center">
        <div style="min-width: 20px;">
            <span id="stock_visual_input" class="dot-status" 
                  data-bs-toggle="tooltip" 
                  title=""></span>
        </div>
        <input type="text" name="codigo_p" class="form-control item_code ms-1" autocomplete="off" inputmode="text">
    </div>
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
                            <td><input type="text" name="cantidad" class="form-control quantity" autocomplete="off" inputmode="decimal"></td>
                            <td><input type="text" name="precio_u" class="precio_u inavi" autocomplete="off"></td>
                            <td><input type="text" name="total_pp" class="total_pp inavi" autocomplete="off"></td>
                            <td>
                               <button type="submit" class="<?php echo $colorbn ?> add-item" id="miBotonDeEnvio" disabled><?php echo $nb ?> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>

            <p class="small mt-2">
                Nota importante: La cantidad solicitada debe ser un múltiplo de la unidad de empaque. <br>
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
            if(isset($_POST['nuevo'])){
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

            <?php if (isset($id_pedido) && $id_pedido > 0) { ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning small mt-2 alernota text-right" role="alert">
                        <strong>⚠️ Nota: El total mostrado NO incluye descuentos ni impuestos.</strong>
                    </div>
                </div>
            </div> 
            <?php } ?>

            <?php $deshabilitado = (!isset($id_pedido) || $id_pedido <= 0) ? 'disabled' : ''; ?>

            <div class="row">
                <div class="col-12 text-right">
                    <div class="alert alert-light" role="alert">
                        <form action="sistemas/pedidos/crud.php" method="post" id="pedido_form">
                            <input type="hidden" name="id" value="<?php echo $id_pedido ?>">
                            <button type="button" class='btn btn-primary' id="btn_guardar" <?php echo $deshabilitado; ?>>
                                Guardar
                            </button>
                            <button type="button" class='btn btn-success ml-2' id="btn_enviar" <?php echo $deshabilitado; ?>>
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-10">
        <div class="p-3 border rounded shadow-sm bg-white">
            <div class="d-flex align-items-center mb-2">
                <strong class="text-danger uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    NOTA IMPORTANTE 
                </strong>
            </div>
            
           <p class="text-secondary mb-0" style="font-size: 0.8rem;">
                Puedes procesar tu solicitud aunque los artículos estén en 
                <span class="dot-status dot-danger me-2 ms-2" style="width: 10px; height: 10px;"></span>  o 
                <span class="dot-status dot-warning me-2 ms-2" style="width: 10px; height: 10px;"></span> 
                Esto puede indicar que el artículo se encuentra actualmente en proceso de producción o en fase de planificación.
            </p>
            
            <div class="pt-2 border-top">
                <div class="align-items-center">
                    <p class="text-secondary mb-0" style="font-size: 0.8rem;">
                        Que el indicador no esté en <span class="dot-status dot-success me-2 ms-2" style="width: 8px; height: 8px;"></span> verde no significa falta de existencias futuras; 
                        al realizar tu pedido, aseguras la asignación y prioridad en nuestra próxima disponibilidad programada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Inicializar tooltips de Bootstrap 5.3 para los puntos de stock
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>