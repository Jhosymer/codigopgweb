<?php 
  // 1. Consulta de cabecera: Obtenemos los datos del pedido SAP
  $wsqli = "SELECT p.*, u.name, u.rif 
            FROM pedidos p 
            INNER JOIN users u ON p.id_users = u.id 
            WHERE p.id = '$id_pedido'";
  $result = $linki->query($wsqli);
  if($linki->errno) die($linki->error);

  $nsap = ""; 
  $total_pedido = "0,00$";

  if($row = $result->fetch_array()){
    $id_pedido_db = $row['id']; 
    $nsap = $row['na_pedido']; 
    $total = $row['total_pedido'];
    $fecha = $row['fecha'];
    $fecha_c = $row['fecha_sap'];
    $rif_cliente = $row['rif'];
    $nombre_cliente = $row['name'];
    $total_pedido = number_format($total, 2, ',', '.') . '$';
    $numero_oc = $row['numero_oc'];
    $origen = $row['origen'];
?>
<style>
   @media print {
    body * { visibility: hidden; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .printable-area { visibility: visible !important; position: absolute; left: 0; top: 0; width: 100% !important; margin: 0 !important; padding: 0 !important; overflow: visible !important; }
    .printable-area .alert, .printable-area table, .printable-area table *, .printable-area h4, .printable-area h5, .printable-area p, .printable-area strong, .printable-area b { visibility: visible !important; }
    .dataTables_wrapper .row, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate { display: none !important; }
    .alert-info.mb-4 { margin-bottom: 5px !important; padding: 10px !important; }
    table#example { width: 100% !important; border-collapse: collapse !important; margin-top: 0 !important; margin-bottom: 5px !important; }
    .alert-warning.mt-2, .alert-secondary.mt-2 { margin-top: 5px !important; padding: 5px 10px !important; }
    .printable-area .row { margin-top: 0 !important; margin-bottom: 0 !important; }
    table#example th, table#example td { padding: 4px 6px !important; border: 1px solid #dee2e6 !important; }
    .d-print-none, .btn, #btnImprimirPedido, footer, .navbar { display: none !important; }
    .badge.bg-info { background-color: #0dcaf0 !important; color: #fff !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; border: none !important; padding: 4px 8px !important; text-decoration: none !important; display: inline-block !important; border-radius: 4px !important; }
   }
</style>

<div class="mb-4 d-print-none">
    <?php $url_referencia = $_SERVER['HTTP_REFERER'] ?? 'index.php?pag=pedido'; ?>
    <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo $url_referencia; ?>'">
        <i class='bx bx-arrow-back'></i> Volver
    </button>
    <button type="button" id="btnImprimirPedido" class="btn btn-danger ms-2">
        <i class='bx bx-printer'></i> Imprimir
    </button>
</div>

<div class="bg-white p-3 overflow-auto rounded printable-area">
  <div class='alert alert-info mb-4' role='alert'>
        <div class="row">
            <div class="col-8">
                <p class="mb-1"><strong>RIF:</strong> <?php echo $rif_cliente; ?></p>
                <p class="mb-0"><strong>Cliente:</strong> <?php echo $nombre_cliente; ?></p>
            </div>
            <div class="col-4 text-end">
                <h5 class="fw-bold mb-1">Nro. Pedido: <?php echo $nsap; ?></h5>
                <p class="mb-0 small">Fecha de Creación: <?php echo $fecha; ?></p>
                <p class="mb-0 small">Fecha de Contabilización : <?php echo $fecha_c; ?></p>
                <p class="mb-0 text-info small">Status: <b>Procesado</b></p>
                <?php if (!empty($numero_oc)): ?>
                    <p class="mb-0 small">Orden de Compra: <?php echo $numero_oc; ?></p>
                <?php endif; ?>
                
               <?php if ($origen == 'sap'): ?>
    <p class="text-muted mb-0 small text-danger" style="font-style: italic;">
        <span class="text-danger">● Pedido importado desde sistema administrativo </span>
    </p>
<?php elseif ($origen == 'app'): ?>
    <p class="text-muted mb-0 small text-primary" style="font-style: italic;">
        <span class="text-primary">● Pedido Creado desde nuestra aplicación </span>
    </p>
<?php endif; ?>
            </div>
        </div>
    </div>
<?php } ?>

<table class="table table-sm color_blanco table-responsive table-bordered dataTable" id="example">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Codigo Prod.</th>
      <th scope="col">Descripción</th>
      <th scope="col" class="text-center">Pedido</th>
      <th scope="col" class="text-center text-success">Despachado</th>
      <th scope="col" class="text-center text-danger">Pendiente</th>
      <th scope="col" class="text-center text-primary">Adicional</th>
      <th scope="col">Precio</th>
      <th scope="col">Total</th>
    </tr>
  </thead>
<tbody>
<?php
$contador = 1;
// 2. Consulta de ítems con subconsulta para el Backorder
$wsqli_items = "SELECT lp.*, 
                fc.codigo as codpro, fc.descripcion as nombre, fc.disponible_inmediata,
                fc.stock AS stock1, fc.act_sap AS act1,
                (SELECT SUM(lf.cantidad) 
                 FROM lista_factura lf 
                 INNER JOIN factura f_sub ON lf.id_factura = f_sub.id 
                 WHERE lf.na_pedido = '$nsap' 
                 AND lf.id_producto = lp.id_producto
                 AND f_sub.id_users = '$id_users'
                 AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) as cant_facturada,
                SUM(COALESCE(t2.stock, 0)) AS stock2,
                MAX(COALESCE(t2.act_sap, 'N')) AS act2
                FROM lista_pedidos lp
                INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id 
                LEFT JOIN filtro_alternativo_sap t2 ON fc.id = t2.id_codigo
                WHERE lp.id_pedido = '$id_pedido'
                GROUP BY lp.id ORDER BY lp.id ASC";

$result_items = $linki->query($wsqli_items);

while($row_i = $result_items->fetch_array()){
    // Cálculos de Despacho
    $cant_pedida = (int)$row_i['cantidad'];
    $cant_despachada = (int)($row_i['cant_facturada'] ?? 0);
    
    $pendiente = ($cant_despachada >= $cant_pedida) ? 0 : ($cant_pedida - $cant_despachada);
    $adicional = ($cant_despachada > $cant_pedida) ? ($cant_despachada - $cant_pedida) : 0;

    $id_pro = trim($row_i['id_producto']);
    
    // Verificación de facturas vinculadas
    $sql_vinc = "SELECT f.id, f.num_fact 
                 FROM lista_factura lf 
                 INNER JOIN factura f ON lf.id_factura = f.id 
                 WHERE lf.id_producto = '$id_pro' 
                   AND lf.na_pedido = '$nsap' AND f.id_users = '$id_users'
                   AND (f.nota_credito IS NULL OR f.nota_credito = '')";
    
    $res_vinc = $linki->query($sql_vinc);
    $facturas_vinculadas = []; 
    $mostrar_semaforo = true;
    $clase_gris = "";

    if ($res_vinc && $res_vinc->num_rows > 0) {
        while($data_v = $res_vinc->fetch_assoc()){
            $facturas_vinculadas[] = $data_v;
        }
        $mostrar_semaforo = false;
        if($pendiente <= 0) { 
            $clase_gris = "table-secondary text-muted opacity-75"; 
        }
    }

    if ($res_vinc && $res_vinc->num_rows > 0) {
        $data_v = $res_vinc->fetch_assoc();
        $id_f = $data_v['id_factura'];
        $sql_n = "SELECT num_fact FROM factura WHERE id = '$id_f' LIMIT 1";
        $res_n = $linki->query($sql_n);
        if($row_n = $res_n->fetch_assoc()){
            $num_factura = $row_n['num_fact'];
            // Si ya se despachó todo, ponemos un tono gris
            if($pendiente <= 0) { $clase_gris = "table-secondary text-muted opacity-75"; }
            $mostrar_semaforo = false;
        }
    }

    // Lógica de disponibilidad (Punto de Stock)
    $clasePunto = ""; $mensaje = ""; $claseTooltip = "";
   if ($mostrar_semaforo) {
        $stockReal = 0;
        if ($row_i['act1'] == 'Y' && $row_i['act2'] == 'Y') { 
            $stockReal = (int)$row_i["stock1"] + (int)$row_i["stock2"]; 
        } else if ($row_i['act1'] == 'Y' && $row_i['act2'] == 'N') { 
            $stockReal = (int)$row_i["stock1"]; 
        } else if ($row_i['act2'] == 'Y' && $row_i['act1'] == 'N') { 
            $stockReal = (int)$row_i["stock2"]; 
        }

        //  (Semaforizacion)
        $meta = (int)$row_i['disponible_inmediata'];
        if ($meta <= 0) {
            $clasePunto = "dot-info"; 
            $mensaje = "stock no configurado"; 
            $claseTooltip = "tooltip-info";
        } else {
            $d10 = $meta * 0.10;
            $d30 = $meta * 0.30;
            if ($stockReal <= $d10) {
                $clasePunto = "dot-danger"; 
                $mensaje = "Consulta con Ventas"; 
                $claseTooltip = "tooltip-danger";
            } elseif ($stockReal <= $d30) {
                $clasePunto = "dot-warning"; 
                $mensaje = "Poca Disponibilidad"; 
                $claseTooltip = "tooltip-warning";
            } else {
                $clasePunto = "dot-success"; 
                $mensaje = "Disponibilidad Inmediata"; 
                $claseTooltip = "tooltip-success";
            }
        }
    }
?>
    <tr class="<?php echo $clase_gris; ?>" style="<?php if($clase_gris != "") echo 'background-color: #ececec !important;'; ?>">
        <th scope="row"><?php echo $contador; ?></th>
           <td>
    <div class="d-flex flex-column"> 
        
        <div class="d-flex align-items-center">
            <div style="min-width: 25px;" class="d-flex justify-content-center">
                <?php if ($mostrar_semaforo): ?>
                    <span class="dot-status <?php echo $clasePunto; ?> me-2" 
                          data-bs-toggle="tooltip" 
                          data-bs-custom-class="<?php echo $claseTooltip; ?>" 
                          data-bs-title="<?php echo $mensaje; ?>"> 
                    </span>
                <?php endif; ?>
            </div>
            <span class="fw-bold"><?php echo $row_i['codpro']; ?></span>
        </div>
        
       <?php if (!empty($facturas_vinculadas)): ?>
    <div class="mt-1 d-flex flex-wrap gap-1" style="margin-left: 25px;"> 
        <?php foreach ($facturas_vinculadas as $f): ?>
            <a href="index.php?pag=factura&id_ver=<?php echo $f['id']; ?>" 
               class="badge bg-info text-decoration-none d-inline-flex align-items-center" 
               style="font-size: 0.7rem; white-space: nowrap;">
                <i class='bx bxs-file-pdf me-1'></i> Fact: <?php echo $f['num_fact']; ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

    </div>
</td>
        <td><?php echo $row_i['nombre']; ?></td>
        
        <td class="text-center fw-bold"><?php echo number_format($cant_pedida, 0); ?></td>
        <td class="text-center text-success fw-bold"><?php echo number_format($cant_despachada, 0); ?></td>
        
        <!-- Pendiente (Corregido) -->
        <td class="text-center text-danger fw-bold"><?php echo number_format($pendiente, 0); ?></td>
        
        <!-- Adicional (Nueva Celda) -->
        <td class="text-center text-primary fw-bold">
            <?php echo ($adicional > 0) ? '+'.number_format($adicional, 0) : '0'; ?>
        </td>
        
        <td class="text-end"><?php echo number_format($row_i['precio_u'], 2, ',', '.'); ?>$</td>
        <td class="text-end"><?php echo number_format($row_i['total'], 2, ',', '.'); ?>$</td>
    </tr>
<?php $contador++; } ?>
</tbody>
</table>

<div class="row">
    <div class="col-12">
        <div class="alert alert-warning small mt-2 alernota text-right" role="alert">
            <strong>⚠️ Nota: El total mostrado NO incluye descuentos ni impuestos.</strong>
        </div>
    </div>
</div> 

<div class="alert alert-secondary mt-2 alertotal" role="alert">
    <h4>Total: <?php echo $total_pedido; ?></h4>
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

