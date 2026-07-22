<?php


// 1. Gestión de Fechas
$fecha_inicio = isset($_POST['f_inicio']) ? $_POST['f_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin    = isset($_POST['f_fin']) ? $_POST['f_fin'] : date('Y-m-d');

// 2. Consulta SQL con p.id para el enlace
$sql_back = "SELECT 
                p.id AS id_pedido_interno, 
                p.na_pedido, 
                p.fecha, 
                fc.codigo AS codigo_comercial, 
                lp.cantidad AS solicitado,
                -- Subconsulta para Despachado: Suma aislada por pedido, producto y usuario
                (SELECT IFNULL(SUM(lf.cantidad), 0) 
                 FROM lista_factura lf 
                 INNER JOIN factura f_sub ON lf.id_factura = f_sub.id
                 WHERE lf.na_pedido = p.na_pedido 
                 AND lf.id_producto = lp.id_producto
                 AND f_sub.id_users = p.id_users AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) AS despachado,
                -- Cálculo de Pendiente: Basado en la misma lógica de suma
                (lp.cantidad - (
                    SELECT IFNULL(SUM(lf2.cantidad), 0) 
                    FROM lista_factura lf2 
                    INNER JOIN factura f_sub2 ON lf2.id_factura = f_sub2.id
                    WHERE lf2.na_pedido = p.na_pedido 
                    AND lf2.id_producto = lp.id_producto
                    AND f_sub2.id_users = p.id_users
                AND (f_sub2.nota_credito IS NULL OR f_sub2.nota_credito = '')
             )) AS pendiente
            FROM pedidos p
            INNER JOIN lista_pedidos lp ON p.id = lp.id_pedido
            INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id
            WHERE p.id_users = '$id_users' 
            AND p.stat = 'C' 
            AND (p.na_pedido IS NOT NULL AND p.na_pedido <> '')
            AND p.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'
            -- Opcional: Descomenta la siguiente línea si usas un alias en el WHERE/HAVING
            -- HAVING pendiente > 0
            ORDER BY fc.codigo DESC";

$res_back = $linki->query($sql_back);

if (!$res_back) {
    die("<div class='alert alert-danger'>Error: " . $linki->error . "</div>");
}
?>

<div class="container p-0">
    <h1 class="titulo text-center"><strong>Backorders de Compra</strong></h1>
    
    <div class="search-container mb-4">
        <div class="card p-4 border-0 shadow-sm bg-white">
            <form method="POST" action="index.php?pag=backorders" class="row g-3 align-items-end justify-content-center">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-muted">Desde la fecha:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-calendar text-primary"></i></span>
                        <input type="date" name="f_inicio" class="form-control border-start-0" value="<?php echo $fecha_inicio; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold text-muted">Hasta la fecha:</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bx bx-calendar-check text-primary"></i></span>
                        <input type="date" name="f_fin" class="form-control border-start-0" value="<?php echo $fecha_fin; ?>">
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bx bx-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
            <small class="text-muted mt-3 text-center d-block">La búsqueda se realiza por el rango de fechas seleccionado.</small>
        </div>
    </div>

    <div class="card bg-transparent border-0 shadow-sm">
       
            <div class='bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm  border'>

            <table class="table table-hover mb-0 align-middle text-center" id="tablaBackorders">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3 py-3">Fecha</th>
                        <th class="py-3">Pedido</th>
                        <th class="py-3 text-start">Código Producto</th>
                        <th class="py-3">Solicitado</th>
                        <th class="py-3 text-success fw-bold">Despachado</th>
                        <th class="py-3 text-danger">Pendiente</th>
                        <th class="py-3 text-primary">Adicional</th>
                        <th class="py-3 pe-3" style="width: 200px;">Estado</th>
                    </tr>
                </thead>
               <tbody>
                      <?php 
                    if ($res_back->num_rows > 0) {
                    while ($row = $res_back->fetch_assoc()) { 
                    $solicitado = $row['solicitado'];
                    $despachado = $row['despachado'];
                    
                    // 1. LÓGICA DE CÁLCULOS
                    $pendiente_real = ($despachado >= $solicitado) ? 0 : ($solicitado - $despachado);
                    $adicional = ($despachado > $solicitado) ? ($despachado - $solicitado) : 0;

                    // 2. Cálculo base del porcentaje
                    $porcentaje_raw = ($solicitado > 0) ? ($despachado / $solicitado) * 100 : 0;

                    // 3. LIMITAR AL 100% (Tanto para la barra como para el texto)
                    $porcentaje_limitado = ($porcentaje_raw > 100) ? 100 : $porcentaje_raw;
                    $porcentaje_texto = round($porcentaje_limitado);
                    
                    // Variable para el CSS de la barra
                    $ancho_barra = $porcentaje_limitado;

                    // 4. Clases de Badge (Se mantiene igual, ahora basado en el límite de 100)
                    $badge_class = "bg-info"; 
                    if($porcentaje_texto == 0) $badge_class = "bg-danger";
                    if($porcentaje_texto > 0 && $porcentaje_texto < 100) $badge_class = "bg-warning text-dark";
                    if($porcentaje_texto >= 100) $badge_class = "bg-success";
            ?>
            <tr>
                <!-- 1. FECHA -->
                <td><?php echo date('d/m/Y', strtotime($row['fecha'])); ?></td>
                
                <!-- 2. PEDIDO -->
                <td>
                    <?php if (!empty($row['na_pedido'])): ?>
                        <a href='index.php?pag=pedido&id_ver=<?php echo $row['id_pedido_interno']; ?>' 
                           class='btn btn-outline-primary btn-sm py-0 px-2 shadow-sm fw-bold' 
                           style='font-size:11px;'><?php echo $row['na_pedido']; ?></a>
                    <?php endif; ?>
                </td>

                <!-- 3. CÓDIGO PRODUCTO -->
                <td class="text-start">
                    <strong><?php echo $row['codigo_comercial']; ?></strong>
                </td>

                <!-- 4. SOLICITADO -->
                <td><strong><?php echo number_format($solicitado, 0); ?></strong></td>

                <!-- 5. DESPACHADO -->
                <td class="text-success fw-bold"><?php echo number_format($despachado, 0); ?></td>

                <!-- 6. PENDIENTE -->
                <td>
                    <span class="fw-bold text-danger"><?php echo number_format($pendiente_real, 0); ?></span>
                </td>

                <!-- 7. ADICIONAL -->
                <td class="text-primary fw-bold">
                    <?php echo ($adicional > 0) ? '+'.number_format($adicional, 0) : '0'; ?>
                </td>

                <!-- 8. ESTADO -->
                <td>
                    <div class="progress mb-1" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $ancho_barra; ?>%"></div>
                    </div>
                    <span class="badge <?php echo $badge_class; ?>" style="font-size: 0.65rem;">
                        <?php echo ($porcentaje_texto == 0) ? 'Pendiente' : $porcentaje_texto.'% Recibido'; ?>
                    </span>
                </td>
            </tr>
        
                            <?php } 
                        } else { ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>No se encontraron productos pendientes en este rango.</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
            </table>
        </div>
      
       

     

