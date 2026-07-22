<?php 
$id_factura = $id; 

// Marcar como visto (Lógica de control)
$check_vista = $linki->query("SELECT visto FROM factura WHERE id = '$id_factura'");
if($row_v = $check_vista->fetch_array()){
    if($row_v['visto'] == 'N'){
        $linki->query("UPDATE factura SET visto = 'Y' WHERE id = '$id_factura'");
    }
}

// Consulta de Cabecera y Datos del Cliente
$wsqli = "SELECT f.*, u.name, u.rif 
          FROM factura f 
          INNER JOIN users u ON f.id_users = u.id 
          WHERE f.id = '$id_factura'";

$result = $linki->query($wsqli);
if($row = $result->fetch_array()){
    $num_fact    = $row['num_fact'];
    $num_control = $row['num_control'];
    $fecha_emi   = date("d/m/Y", strtotime($row['fecha_contab']));
    $fecha_venc  = date("d/m/Y", strtotime($row['fecha_venc']));
    $moneda      = $row['Moneda']; 
    
    // Valores en Divisas (USD u otra)
    $sub_total_val = $row['sub_total'];
    $desc_porce    = $row['porcentaje_descuento']; 
    $desc_monto    = $row['descuento'];
    $iva_val       = $row['iva'];
    $iva_porce     = number_format($row['porcentaje_iva'], 0);
    $total_val     = $row['total_fact'];

    // Lógica para Bolívares usando valor_Moneda
    $valor_Moneda  = $row['valor_Moneda']; 
    $monto_antes_desc_bs = $sub_total_val * $valor_Moneda;
    $descuento_bs  = $desc_monto * $valor_Moneda;
    $base_imp_bs   = ($sub_total_val - $desc_monto) * $valor_Moneda;
    $iva_bs        = $iva_val * $valor_Moneda;
    $total_bs      = $total_val * $valor_Moneda;
}
?>

<style>
/* Estilo base para pantalla y marca de agua */
.watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 5rem;
    color: rgba(220, 53, 69, 0.05); 
    font-weight: bold;
    text-transform: uppercase;
    z-index: 0;
    pointer-events: none;
    white-space: nowrap;
}

/* Evitar scroll horizontal en el contenedor */
.table-responsive {
    overflow-x: visible !important;
}

@media print {
    @page { size: letter; margin: 0.5cm; }
    body { background-color: #fff !important; -webkit-print-color-adjust: exact; font-family: sans-serif; }
    .no-print, .d-print-none { display: none !important; }
    
    body * { visibility: hidden; }
    #area-impresion, #area-impresion * { visibility: visible; }

    #area-impresion {
        position: absolute;
        left: 0; top: 0;
        width: 100% !important;
        border: none !important;
    }

    /* Tabla de productos principal */
    .table-impresion-fuerte {
        width: 100% !important;
        table-layout: fixed !important;
        border-collapse: collapse !important;
        margin-top: 5px;
    }

    .table-impresion-fuerte th, 
    .table-impresion-fuerte td {
        font-size: 8.5px !important; /* Texto de productos pequeño */
        padding: 2px 1px !important;
        border-bottom: 0.5px solid #ececec !important;
    }

    .col-item { width: 25px !important; }
    .col-cod  { width: 85px !important; }
    .col-desc { width: auto !important; }
    .col-bult { width: 50px !important; }
    .col-cant { width: 50px !important; }
    .col-prec { width: 70px !important; }
    .col-desc-por { width: 45px !important; }
    .col-tot  { width: 90px !important; }

    /* SECCIÓN TOTALES USD (Imagen 5 y 7) */
    .table-totales-finales {
        margin-top: 5px;
        line-height: 1 !important;
    }
    .table-totales-finales td {
        font-size: 8pt !important;
        padding: 1px 0 !important;
    }
    .fila-total-usd td {
        font-size: 9.5pt !important;
        border-top: 1px solid #000 !important;
        padding-top: 3px !important;
    }

   
    .seccion-notas-compacta {
        font-size: 7.2pt !important;
        line-height: 1.1 !important;
        margin-top: 8px !important;
        color: #444 !important;
    }

    
    .contenedor-bs {
        font-size: 8.2pt !important;
        margin-top: 10px !important;
        padding-top: 5px;
    }

    .linea-subrayada {
        border-bottom: 1px solid #000;
        display: inline-block;
        min-width: 80px;
        text-align: center;
        padding: 0 4px;
    }

    .total-bold-bs {
        font-size: 10.5pt !important;
        border-bottom: 2px solid #000;
        padding: 0 10px;
    }
    footer.footer {
        visibility: visible !important;
        position: absolute;
        bottom: 5px; /* Un poco más arriba del borde del papel */
        left: 0;
        width: 100%;
        border-top: 0.5px solid #eee !important;
        padding-top: 5px !important;
        background: transparent !important;
    }

    footer.footer * {
        visibility: visible !important;
        font-size: 7.5pt !important; /* Texto pequeño para que no distraiga */
        color: #666 !important; /* Gris oscuro para impresión clara */
    }
}
</style>

<div class="container mt-4 mb-5" factura-container>

   <div class="d-flex gap-2 no-print"> 
        <button type="button" class="btn btn-secondary mb-5" onclick="history.back()">
            <i class='bx bx-arrow-back'></i> Volver
        </button>

       <button type="button" class="btn btn-danger mb-5" onclick="window.print()">
            <i class='bx bx-printer'></i> Imprimir
        </button>
    </div>



<div id="area-impresion" class="card shadow-sm border-0">
        
        <div class="card-body p-4">
            <div class="watermark">Copia del Original</div>
           <div class="row mb-1">
    <div class="col-7">
        <p class="mb-0"><strong>RIF:</strong> <?php echo str_replace("C-", "", $row['rif']); ?></p>
        <p class="mb-0"><strong>Cliente:</strong> <?php echo $row['name']; ?></p>
    </div>
    <div class="col-5 text-end">
        <h4 class="fw-bold mb-0">Factura N°: <?php echo $num_fact; ?></h4>
        <p class="mb-0 fw-bold">N° de Control: 00-<?php echo $num_control; ?></p>
        <p class="mb-0 small">Fecha Emisión: <?php echo $fecha_emi; ?></p>
        <p class="mb-0 small">Fecha Vencimiento: <?php echo $fecha_venc; ?></p>
    </div>
</div>

           <div class="table-responsive">
                <table class="table table-sm align-middle border-dark table-impresion-fuerte" style="font-size: 0.82rem;">
                    <thead class="border-top border-bottom border-dark text-center">
    <tr>
        <th class="col-item">#</th>
        <th class="text-start col-cod">Código</th>
        <th class="text-start col-desc">Descripción</th>
        <th class="d-print-none" style="width: 0px; padding: 0;"></th> <th class="col-bult">Bultos</th>
        <th class="col-cant">Cantidad</th>
        <th class="col-prec">Precio(<?php echo $moneda; ?>)</th>
        <th class="col-desc-por">% Desc</th>
        <th class="text-end col-tot">Total(<?php echo $moneda; ?>)</th>
    </tr>
</thead>
              <tbody>
                        <?php 
                        $res_det = $linki->query("SELECT lf.*, fc.codigo, fc.descripcion, fc.und_empaque 
                                                  FROM lista_factura lf
                                                  INNER JOIN filtro_codificacion fc ON lf.id_producto = fc.id
                                                  WHERE lf.id_factura = '$id_factura'");
                        $i = 1; $total_bultos = 0; $total_cantidad = 0; $pedidos_array = [];

                        while($item = $res_det->fetch_array()){
                            $bultos = $item['cantidad'] / (($item['und_empaque'] > 0) ? $item['und_empaque'] : 1);
                            $total_bultos += $bultos;
                            $total_cantidad += $item['cantidad'];
                            if(!empty($item['na_pedido'])) $pedidos_array[] = $item['na_pedido'];
                            
                           echo "<tr class='text-center'>
        <td class='col-item fw-bold'>$i</td>
        <td class='text-start fw-bold col-cod'>{$item['codigo']}</td>
        <td class='text-start col-desc'><span class='texto-cortado'>{$item['descripcion']}</span></td>
        
        <td class='d-print-none text-center'>"; // Esta celda desaparece en impresión
            $res_p = $linki->query("SELECT id FROM pedidos WHERE na_pedido = '{$item['na_pedido']}' LIMIT 1");
            if($rp = $res_p->fetch_array()){
                echo "<a href='index.php?pag=pedido&id_ver={$rp['id']}' class='btn btn-outline-primary btn-sm py-0 px-1 mb-1 shadow-sm fw-bold' style='font-size:10px;'>{$item['na_pedido']}</a>";
            }
        echo "</td>

        <td class='col-bult'>" . number_format($bultos, 2, ',', '.') . "</td>
        <td class='col-cant'>" . number_format($item['cantidad'], 0, ',', '.') . "</td>
        <td class='col-prec'>" . number_format($item['precio'], 2, ',', '.') . "</td>
        <td class='col-desc-por'>0,00</td>
        <td class='text-end fw-bold col-tot'>" . number_format($item['total'], 2, ',', '.') . "</td>
      </tr>";
                            $i++;
                        }
                        $lista_pedidos = implode(', ', array_unique($pedidos_array));
                        ?>
                    </tbody>
                    <tfoot class="fw-bold">
                        <tr class="text-center">
                            <td colspan="3" class="text-end pe-3">Totales:</td>
                            <td class="d-print-none"></td> <td><?php echo number_format($total_bultos, 2, ',', '.'); ?></td>
                            <td><?php echo number_format($total_cantidad, 0, ',', '.'); ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row justify-content-end mb-2"> <div class="col-md-4">
       <table class="table table-sm table-borderless fw-bold mb-0 table-totales-finales">
                        <tr>
                            <td class="text-end">Sub-Total (<?php echo $moneda; ?>):</td>
                            <td class="text-end" style="width: 130px;"><?php echo number_format($sub_total_val, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-end">Descuento % (<?php echo $moneda; ?>): <span class="ms-1"><?php echo number_format($desc_porce, 2, ',', '.'); ?></span></td>
                            <td class="text-end"><?php echo number_format($desc_monto, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-end ">IVA % (<?php echo $moneda; ?>): <span class=" ms-1"><?php echo $iva_porce; ?></span></td>
                            <td class="text-end"><?php echo number_format($iva_val, 2, ',', '.'); ?></td>
                        </tr>
                        <tr class="fs-5">
                            <td class="text-end pt-2">TOTAL (<?php echo $moneda; ?>):</td>
                            <td class="text-end pt-2 "><?php echo number_format($total_val, 2, ',', '.'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="border-dark">

            <div class="mt-2 small text-muted" style="line-height: 1.2;">
                <p class="mb-1"><strong>Notas:</strong> Basado en Pedidos de cliente: <?php echo $lista_pedidos; ?>.</p>
                <p class="mb-1 text-dark">Favor emitir pago o hacer transferencia a nombre de Jet-Filter, C.A. y exigir su recibo el cual será el único comprobante de pago de esta factura.</p>
                <p class="mb-1">El cliente o quien recibe esta mercancía en su nombre, certifica haber recibido el original de esta factura para procesar su pago.</p>
                <p class="mb-1">Esta factura se entiende en dólares de los Estados Unidos de Norte América, la misma debe ser cancelada en bolívares a la tasa de cambio del Banco Central de Venezuela a la fecha de su vencimiento. En caso de ser pagada o hacer abonos parciales en cualquier otra moneda distinta al Bolívar o el Petro se debe pagar adicional el 3%, por concepto de IGTF, cuando el pago sea en efectivo o sin usar el sistema financiero venezolano.</p>
                <p class="mb-3">Todo esto, de acuerdo a lo estipulado en la Providencia Administrativa SNAT/2022/000013 del 17 de marzo de 2022.</p>
            </div>

         

 <div class="contenedor-bs fw-bold">
    <div class="row g-0 border-top border-dark pt-2 fw-bold">
        <div class="col-4 mt-2">Tasa de cambio hoy USD 1 = Bs. <span class="linea-subrayada  border-bottom border-dark "><?php echo number_format($valor_Moneda, 4, ',', '.'); ?></span></div>
        <div class="col-4 text-center mt-2"> Monto antes del Descuento  <span class="linea-subrayada  border-bottom border-dark"><?php echo number_format($monto_antes_desc_bs, 2, ',', '.'); ?></span></div>
        <div class="col-4 text-end mt-2">Descuento Bs.<span class="linea-subrayada  border-bottom border-dark"><?php echo number_format($descuento_bs, 2, ',', '.'); ?></span></div>
    </div>
    <div class="row g-0 mt-2 align-items-center">
        <div class="col-4 mt-2">Base Imponible Bs. <span class="linea-subrayada  border-bottom border-dark"><?php echo number_format($base_imp_bs, 2, ',', '.'); ?></span></div>
        <div class="col-4 text-center mt-2">IVA = Bs. <span class="linea-subrayada  border-bottom border-dark"><?php echo number_format($iva_bs, 2, ',', '.'); ?></span></div>
        <div class="col-4 text-end mt-2">
            Total = <span class="total-bold-bs  border-bottom border-dark border-2 "><?php echo number_format($total_bs, 2, ',', '.'); ?></span>
        </div>
    </div>
</div>
        </div>
    </div>
</div>