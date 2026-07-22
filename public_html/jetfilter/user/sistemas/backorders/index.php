<?php
// 1. Gestión de Fechas
$fecha_inicio = isset($_POST['f_inicio']) ? $_POST['f_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin    = isset($_POST['f_fin']) ? $_POST['f_fin'] : date('Y-m-d');

// 2. Consulta SQL (Agregamos fc.id y otros campos para la agrupación)
$sql_back = "SELECT 
                p.id AS id_pedido_interno, 
                p.na_pedido, 
                p.fecha_sap, 
                fc.id AS id_prod,
                fc.codigo AS codigo_comercial, 
                lp.cantidad AS solicitado,
                (SELECT IFNULL(SUM(lf.cantidad), 0) 
                 FROM lista_factura lf 
                 INNER JOIN factura f_sub ON lf.id_factura = f_sub.id
                 WHERE lf.na_pedido = p.na_pedido 
                 AND lf.id_producto = lp.id_producto
                 AND f_sub.id_users = p.id_users 
                 AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) AS despachado
            FROM pedidos p
            INNER JOIN lista_pedidos lp ON p.id = lp.id_pedido
            INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id
            WHERE p.id_users = '$id_users' 
            AND p.stat = 'C' 
            AND (p.na_pedido IS NOT NULL AND p.na_pedido <> '')
            AND p.fecha_sap BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY fc.codigo ASC, p.fecha_sap DESC";

$res_back = $linki->query($sql_back);

// 3. Procesamiento de datos para Agrupación por Producto
$backorders = [];
if ($res_back && $res_back->num_rows > 0) {
    while ($r = $res_back->fetch_assoc()) {
        $cp = $r['id_prod'];
        if (!isset($backorders[$cp])) {
            $backorders[$cp] = [
                'codigo' => $r['codigo_comercial'],
                'sol' => 0,
                'des' => 0,
                'pedidos_count' => 0,
                'detalles' => []
            ];
        }
        $backorders[$cp]['sol'] += (float)$r['solicitado'];
        $backorders[$cp]['des'] += (float)$r['despachado'];
        $backorders[$cp]['pedidos_count']++;
        $backorders[$cp]['detalles'][] = $r;
    }
}
?>

<div class="container p-0">
    <h1 class="titulo text-center"><strong>Backorders de Compra</strong></h1>
    
    <!-- Filtros (Tu estilo original) -->
    <div class="search-container mb-4">
        <div class="card p-4 border-0 shadow-sm bg-white">
            <form method="POST" class="row g-3 align-items-end justify-content-center">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-muted">Desde la fecha:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-calendar text-primary"></i></span>
                        <input type="date" name="f_inicio" class="form-control border-start-0" value="<?= $fecha_inicio ?>">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-muted">Hasta la fecha:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-calendar-check text-primary"></i></span>
                        <input type="date" name="f_fin" class="form-control border-start-0" value="<?= $fecha_fin ?>">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bx bx-search me-1"></i> Buscar
                    </button>
                </div>


               <div class="col-12 col-md-2">
                    <a href="sistemas/backorders/exportar_backorders.php?f_inicio=<?= $fecha_inicio ?>&f_fin=<?= $fecha_fin ?>&id_users=<?= $id_users ?>" 
                    target="_blank" 
                    class="btn btn-success w-100 shadow-sm"
                    title="Descargar reporte en Excel">
                        <i class="bx bxs-file-export me-1"></i> Excel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Tabla Multinivel -->
    <div class="card bg-transparent border-0 shadow-sm">
        <div class='bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm border'>
            <table class="table align-middle text-center mb-0" >
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-4">CÓDIGO PRODUCTO</th>
                        <th>VECES PED.</th>
                        <th>SOLICITADO</th>
                        <th class="text-success">RECIBIDO</th>
                        <th class="text-danger">PENDIENTE</th>
                        <th class="text-primary">ADICIONAL</th>
                        <th class="pe-4">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backorders)): ?>
                        <tr><td colspan="7" class="py-4 text-muted">No se encontraron productos en este rango.</td></tr>
                    <?php else: 
                        foreach ($backorders as $id_p => $p): 
                            $p_sol = $p['sol']; $p_des = $p['des'];
                            $p_pen = max(0, $p_sol - $p_des);
                            $p_adi = ($p_des > $p_sol) ? ($p_des - $p_sol) : 0;
                            $porc_total = ($p_sol > 0) ? min(100, ($p_des / $p_sol) * 100) : 0;
                    ?>
                        <!-- Fila Principal de Producto -->
                        <tr class="border-bottom">
                            <td class="text-start ps-4">
                                <strong class="text-primary fs-5"><?= $p['codigo'] ?></strong>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= $p['pedidos_count'] ?></span></td>
                            <td class="fw-bold"><?= number_format($p_sol, 0) ?></td>
                            <td class="text-success fw-bold"><?= number_format($p_des, 0) ?></td>
                            <td class="text-danger fw-bold"><?= number_format($p_pen, 0) ?></td>
                            <td class="text-primary fw-bold"><?= ($p_adi > 0 ? '+'.number_format($p_adi, 0) : '0') ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="collapse" data-bs-target="#col_p_<?= $id_p ?>">
                                    <i class="bx bx-list-ul"></i> Ver Pedidos
                                </button>
                            </td>
                        </tr>
                        <!-- Contenido Desplegable (Pedidos del Producto) -->
                        <tr data-child="true"> 
    <td colspan="7" class="p-0 border-0">
        <div class="collapse bg-light" id="col_p_<?= $id_p ?>">
            <div class="p-3">
                                       <table class="table table-sm table-borderless bg-white shadow-sm rounded-3 mb-0">
    <thead class="text-muted border-bottom" style="font-size: 0.75rem;">
        <tr>
            <th class="ps-3">FECHA</th>
            <th>N° PEDIDO</th>
            <th>SOLICITADO</th>
            <th class="text-success">RECIBIDO</th>
            <th class="text-danger">PENDIENTE</th>
            <th class="text-primary">ADICIONAL</th> <!-- Agregada según image_937cda.png -->
            <th style="width: 200px;">PROGRESO</th>
        </tr>
    </thead>
    <tbody style="font-size: 0.85rem;">
        <?php foreach ($p['detalles'] as $d): 
            $d_sol = (float)$d['solicitado'];
            $d_des = (float)$d['despachado'];
            
            // Lógica de Pendiente vs Adicional
            $d_pen = max(0, $d_sol - $d_des);
            $d_adi = ($d_des > $d_sol) ? ($d_des - $d_sol) : 0;
            
            $d_porc = ($d_sol > 0) ? min(100, ($d_des / $d_sol) * 100) : 0;
            $badge_c = ($d_porc >= 100) ? "bg-success" : (($d_porc > 0) ? "bg-warning text-dark" : "bg-danger");
        ?>
        <tr class="border-bottom text-center">
            <td class="ps-3 text-muted"><?= date('d/m/Y', strtotime($d['fecha_sap'])) ?></td>
            <td>
                <a href="index.php?pag=pedido&id_ver=<?= $d['id_pedido_interno'] ?>" class="text-decoration-none fw-bold">
                    <i class="bx bx-receipt"></i> <?= $d['na_pedido'] ?>
                </a>
            </td>
            <td class="fw-bold"><?= number_format($d_sol, 0) ?></td>
            <td class="text-success fw-bold"><?= number_format($d_des, 0) ?></td>
            <td class="text-danger fw-bold"><?= number_format($d_pen, 0) ?></td>
            <td class="text-primary fw-bold"><?= ($d_adi > 0 ? '+'.number_format($d_adi, 0) : '0') ?></td>
            <td>
                <!-- Barra de Progreso -->
                <div class="progress mb-1 rounded-pill" style="height: 6px; background-color: #eee;">
                    <div class="progress-bar bg-success rounded-pill" style="width: <?= $d_porc ?>%"></div>
                </div>
                <!-- Badge de Estado -->
                <span class="badge rounded-pill <?= $badge_c ?>" style="font-size: 10px; padding: 0.35em 0.8em;">
                    <?= round($d_porc) ?>% Recibido
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>