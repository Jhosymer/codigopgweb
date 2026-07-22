<?php

$query_cliente = $linki->query("SELECT name, rif, saldo FROM users WHERE id = '$id_users'");
$datos_cliente = $query_cliente->fetch_assoc();
$saldo_total_cliente = $datos_cliente['saldo'] ?? 0;

$saldo_valor = $saldo_total_cliente;

if ($saldo_valor > 0) {
    $clase_color_texto = "text-danger"; 
} elseif ($saldo_valor < 0) {
    $clase_color_texto = "text-success"; 
} else {
    $clase_color_texto = "text-muted"; 
}

//Consulta principal
$sql = "SELECT s.*, f.id as id_factura_web 
        FROM sap_estado_cuenta s
        LEFT JOIN factura f ON s.documento = f.num_fact AND s.id_users = f.id_users
        WHERE s.id_users = '$id_users' AND s.saldo_vencido != 0
        ORDER BY s.fecha ASC";

$result = $linki->query($sql);
if (!$result) { die("Error: " . $linki->error); }

$fecha_reporte = "";
$movimientos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (empty($fecha_reporte)) $fecha_reporte = $row['created_at'];
        $movimientos[] = $row;
    }
}

$display_fecha = !empty($fecha_reporte) ? date('d/m/Y', strtotime($fecha_reporte)) : date('d/m/Y');
$display_hora = !empty($fecha_reporte) ? date('H:i:s', strtotime($fecha_reporte)) : date('H:i:s');
?>

<div class="card border-0 shadow-sm mb-4 print-container">
   
    
    <div class="card-body p-4">
        <div class="row align-items-center mb-3 d-print-none">
            <div class="col-6">
                <button type="button" class="btn btn-danger shadow-sm" onclick="window.print()">
                    <i class='bx bx-printer'></i> Imprimir
                </button>

             <a href="sistemas/estado_cuenta/exportar_estado_cuenta.php?id_users=<?= $id_users ?>&fecha=<?= urlencode($display_fecha) ?>&hora=<?= urlencode($display_hora) ?>" 
                target="_blank" 
                class="btn btn-success shadow-sm"
                title="Descargar reporte en Excel">
                <i class="bx bxs-file-export"></i> Excel
            </a>
            </div>
            <div class="col-6 text-end small text-muted">
                <strong>Fecha:</strong> <?php echo $display_fecha; ?> | <strong>Tiempo:</strong> <?php echo $display_hora; ?>
            </div>
        </div>

        <div class="d-none d-print-block mb-4 pt-3 text-start print-header-container">
            <h3 class="fw-bold text-center text-uppercase text-decoration-underline mb-4">JET-FILTER, C.A</h3>
            <div class="row mb-2">
                <div class="col-8">
                    <p class="mb-1"><strong>Código Cliente:</strong> <?php echo str_replace('C-', '', $datos_cliente['rif'] ?? 'N/A'); ?></p>
                    <p class="mb-1"><strong>Nombre Cliente:</strong> <?php echo $datos_cliente['name'] ?? 'No disponible'; ?></p>
                    <p class="mb-1"><strong>Moneda Cliente:</strong> Dólar americano</p>
                </div>
                <div class="col-4 text-end">
                    <p class="mb-1"><strong>Fecha:</strong> <?php echo $display_fecha; ?></p>
                    <p class="mb-1"><strong>Tiempo:</strong> <?php echo $display_hora; ?></p>
                </div>
            </div>
            <h4 class="fw-bold text-center mt-3 mb-3">Estado de cuenta clientes</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr class="text-center text-uppercase small-header"> 
                        <th class="py-1">Fecha Contabilización</th>
                        <th class="py-1">Nro. Documento</th>
                        <th class="py-1">Estado</th>
                        <th class="py-1">Info Detallada</th>
                        <th class="py-1 text-end">Monto (ME)</th>
                        <th class="py-1 text-end">Saldo Vencido (ME)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($movimientos) > 0): ?>
                        <?php foreach ($movimientos as $m): ?>
                        <?php 
                                // Calculamos la diferencia de días
                                $fecha_contabilizacion = new DateTime($m['fecha']);
                                $fecha_hoy = new DateTime(); // Fecha actual
                                $diferencia = $fecha_hoy->diff($fecha_contabilizacion);
                                $dias_transcurridos = $diferencia->days;
                                
                                // Determinamos el estado y el color
                                if ($dias_transcurridos > 30) {
                                    $estado_texto = "Vencida";
                                    $estado_clase = "text-danger fw-bold";
                                } else {
                                    $estado_texto = "Vigente";
                                    $estado_clase = "text-success";
                                }
                            ?>
                            <tr>
                                <td class="text-center"><?php echo date('d/m/Y', strtotime($m['fecha'])); ?></td>
                                <td class="text-center fw-bold">
                                    <?php if (!empty($m['id_factura_web'])): ?>
                                        <a href="index.php?pag=factura&id_ver=<?php echo $m['id_factura_web']; ?>" 
                                        class="btn btn-outline-primary btn-sm py-0 px-1 mb-0 shadow-sm fw-bold d-print-none">
                                            <?php echo $m['documento']; ?>
                                        </a>
                                        <div class="d-none d-print-block">
                                            <?php echo $m['documento']; ?>
                                        </div>
                                    <?php else: ?>
                                        <?php echo $m['documento']; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center <?php echo $estado_clase; ?>" style="font-size: 0.8rem;">
                                    <?php echo $estado_texto; ?>
                                    <div class="small text-muted d-print-none" style="font-size: 0.65rem;">
                                        (<?php echo $dias_transcurridos; ?> días)
                                    </div>
                                </td>
                                <td class="text-muted"><small><?php echo $m['detalle']; ?></small></td>
                                <td class="text-end font-monospace"><?php echo number_format($m['monto'], 6, ',', '.'); ?>$</td>
                                <td class="text-end font-monospace text-danger fw-bold">
                                    <?php echo number_format($m['saldo_vencido'], 6, ',', '.'); ?> $
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No existen movimientos pendientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end fw-bold py-2 text-uppercase" style="font-size: 0.75rem;">Saldo Total a Pagar:</td>
                        <td class="text-end py-2">
                            <span class="font-monospace fw-bold <?php echo $clase_color_texto; ?>" style="font-size: 1rem;">
                                <?php echo number_format(abs($saldo_total_cliente), 6, ',', '.'); ?> $
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        @page {
            size: letter landscape;
            margin: 1cm; /* Margen un poco más amplio para que no se corte el texto */
        }

        body {
            counter-reset: page; /* Inicializa el contador al empezar */
        }

        /* Limpieza general */
        body, .card, .card-body, .table-responsive {
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background-color: #fff !important;
        }

        /* Visibilidad */
        body * { visibility: hidden; }
        .print-container, .print-container * { visibility: visible; }

        .print-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
        }

        /* --- LOGICA DEL CONTADOR CORREGIDA --- */
        .page-counter {
            position: fixed; /* Esto hace que se repita en cada hoja */
            top: 0;
            right: 0;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 9999;
        }

        .page-counter::after {
            counter-increment: page; /* ESTA LINEA ES LA QUE SUMA 1, 2, 3... */
            content: "Página " counter(page) " de " counter(pages);
        }

        /* --- TABLA --- */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            border: 1px solid #000 !important;
            font-size: 0.7rem !important;
        }

        .table thead th, .table tbody td, .table tfoot td {
            border: 1px solid #000 !important;
            padding: 4px 6px !important;
            color: #000 !important;
        }

        

        .text-danger, .text-success, .text-muted, .font-monospace {
            color: #000 !important;
            font-weight: bold !important;
        }

        .d-print-none { display: none !important; }
    }
</style>