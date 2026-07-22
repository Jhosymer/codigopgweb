<?php 
$id_pago = $id; 

// 1. Marcar como visto
$linki->query("UPDATE pagos SET visto = 'Y' WHERE id = '$id_pago' AND id_users = '$id_users'");

// 2. Consulta de Cabecera: Incluimos total_bs y tasa_cambio
$sqlP = "SELECT p.*, u.name, u.rif 
         FROM pagos p 
         INNER JOIN users u ON p.id_users = u.id 
         WHERE p.id = '$id_pago'";

$result = $linki->query($sqlP);

$num_sap    = "N/A";
$fecha_pago = "";
$moneda     = "";
$total_pag  = 0;
$total_bs   = 0;
$tasa       = 0;
$coment     = "";
$referencia = "";
$nombre_cli = "";
$rif_cli    = "";

if($pago = $result->fetch_array()){
    $num_sap    = $pago['num_pago_sap'];
    $fecha_pago = date("d/m/Y", strtotime($pago['fecha_pago']));
    $moneda     = $pago['moneda'];
    $total_pag  = $pago['total_pago'];
    $total_bs   = $pago['total_bs'];
    $tasa       = $pago['tasa_cambio'];
    $coment     = $pago['comentarios'];
    $referencia = $pago['referencia'];
    $nombre_cli = $pago['name'];
    $rif_cli    = str_replace("C-", "", $pago['rif']);

   
}
?>

<style>
    .sap-container { font-family: Arial, sans-serif; color: #000; line-height: 1.2; }
    .sap-title { font-size: 1.6rem; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
    .table-sap { border: 1px solid #000; width: 100%; border-collapse: collapse; margin-top: 10px; }
    .table-sap th, .table-sap td { border: 1px solid #000; padding: 3px 5px; font-size: 10.5px; }
    .label-bold { font-weight: bold; width: 110px; display: inline-block; }
    .border-box { border: 1px solid #000; padding: 2px 8px; min-width: 140px; display: inline-block; text-align: right; }
    
 
    @media print {
        /* 1. Ocultamos TODO el cuerpo de la página */
        body * {
            visibility: hidden;
        }
        /* 2. Hacemos visible solo el área de impresión y sus hijos */
        #area-impresion, #area-impresion * {
            visibility: visible;
        }
        /* 3. Posicionamos el área de impresión al inicio de la página */
        #area-impresion {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        /* 4. Quitamos márgenes innecesarios del navegador */
        @page { 
            size: letter; 
            margin: 0.5cm; 
        }
        .no-print { display: none !important; }
    }

</style>

<div class="container mt-4 mb-5 sap-container">
    <div class="d-flex gap-2 no-print mb-4"> 
        <button type="button" class="btn btn-secondary" onclick="history.back()">
            <i class='bx bx-arrow-back'></i> Volver
        </button>
        <button type="button" class="btn btn-danger" onclick="window.print()">
            <i class='bx bx-printer'></i> Imprimir Recibo
        </button>
    </div>

    <div id="area-impresion" class="card shadow-sm border-0">
        <div class="card-body p-5">
            
            <div class="text-center mb-4">
                <h2 class="sap-title">JET-FILTER, C.A</h2>
            </div>

            <div class="row mb-3">
                <div class="col-7">
                    <p class="mb-1"><span class="label-bold">A:</span> <strong><?php echo $nombre_cli; ?></strong></p>
                </div>
                <div class="col-5 text-end small">
                    <p class="mb-1"><strong>Fecha:</strong> <?php echo $fecha_pago; ?></p>
                    <p class="mb-1"><strong>RIF:</strong> <?php echo $rif_cli; ?></p>
                </div>
            </div>

            <div class="row text-center mb-4 mt-2">
                <div class="col-12"><h4 class="fw-bold">Recibo de Caja Nro: <span style="margin-left:50px;"><?php echo $num_sap; ?></span></h4></div>
            </div>

            <table class="table-sap mb-0">
                <thead class="text-center bg-light">
                    <tr>
                        <th>#</th>
                        <th>Documento pago</th>
                        <th>N° doc.</th>
                        <th>Fecha</th>
                        <th>Núm.de ref.</th>
                        <th>Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $res_det = $linki->query("SELECT * FROM pagos_detalle WHERE id_pagos = '$id_pago'");
                    $i = 1;
                    $lista_facturas_texto = "";
                    
                    while($item = $res_det->fetch_array()){
                        $monto_f = number_format($item['monto_aplicado'], 6, ',', '.');
                        $f_doc = (!empty($item['fecha_factura'])) ? date("d/m/Y", strtotime($item['fecha_factura'])) : $fecha_pago;
                        
                        $lista_facturas_texto .= $item['num_fact_externa'] . " - $moneda " . $monto_f . "; ";
                        
                        echo "<tr class='text-center'>
                            <td>$i</td>
                            <td>Factura de de deudores</td>
                            <td>" . $item['num_fact_externa'] . "</td>
                            <td>$f_doc</td> 
                            <td>" . ($item['comprobante_retencion'] ?? $num_sap) . "</td>
                            <td class='text-end'>$moneda $monto_f</td>
                        </tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>

            <div class="row">
                <div class="col-12">
                    <table class="table-sap" style="border: none;">
                        <tr>
                            <td style="border:none; width: 75%; text-align: right; padding-right: 40px;"><strong>Total Bs</strong></td>
                            <td style="border:none; width: 25%; text-align: right;" class="fw-bold"><?php echo number_format($total_bs, 6, ',', '.'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row mb-4 mt-4">
    <div class="col-7">
        <p class="small mb-3"><strong>Comentarios:</strong> <?php echo $coment; ?></p>
    </div>

    <div class="col-5">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <span class="small"><strong>Total Efectivo:</strong></span>
            <span class="border-box fw-bold">0,000000</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="small"><strong>Total</strong></span>
            <span class="border-box fw-bold">
                <?php echo $moneda . " " . number_format($total_pag, 6, ',', '.'); ?>
            </span>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-6">
        <p><strong>Firma:</strong> </p>
    </div>
    <div class="col-6 text-start">
        <p><strong>Banco:</strong></p>
    </div>
</div>

        </div>
    </div>
</div>