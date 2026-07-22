<?php
$loc = "../../../";
$locj = "./../../";
$title = "Administrador - Backorders";
include_once('../index/header.php'); 

// 1. Gestión de Fechas y Filtros
$fecha_inicio = isset($_POST['f_inicio']) ? $_POST['f_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin    = isset($_POST['f_fin']) ? $_POST['f_fin'] : date('Y-m-d');
$filtro_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : '';
$filtro_linea = isset($_POST['linea']) ? $_POST['linea'] : '';

try {
    // Consulta para Select de Clientes
    $stmt_clientes = $base_de_datos->query("SELECT id, name FROM users WHERE rol = '2' ORDER BY name ASC");
    $clientes = $stmt_clientes->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para Select de Líneas (Clase)
    $stmt_lineas = $base_de_datos->query("SELECT DISTINCT clase FROM filtro_codificacion WHERE deleted_at IS NULL AND clase IS NOT NULL AND clase <> '' ORDER BY clase ASC");
    $lineas = $stmt_lineas->fetchAll(PDO::FETCH_ASSOC);

    // 2. Query con filtros aplicados
    $sql_str = "SELECT 
                    p.id AS id_pedido_interno, 
                    p.na_pedido, 
                    p.fecha, 
                    p.fecha_sap, 
                    u.id AS id_usuario,
                    u.name AS name_cliente,
                    fc.id AS id_prod,
                    fc.codigo AS codigo_comercial, 
                    fc.clase AS linea,
                    lp.cantidad AS solicitado,
                    (SELECT IFNULL(SUM(lf.cantidad), 0) 
                     FROM lista_factura lf 
                     INNER JOIN factura f_sub ON lf.id_factura = f_sub.id
                     WHERE lf.na_pedido = p.na_pedido 
                     AND lf.id_producto = lp.id_producto
                     AND f_sub.id_users = p.id_users
                     AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) AS despachado,
                    (SELECT GROUP_CONCAT(DISTINCT f_sub2.num_fact SEPARATOR ', ')
                     FROM lista_factura lf2
                     INNER JOIN factura f_sub2 ON lf2.id_factura = f_sub2.id
                     WHERE lf2.na_pedido = p.na_pedido 
                     AND lf2.id_producto = lp.id_producto
                     AND f_sub2.id_users = p.id_users AND (f_sub2.nota_credito IS NULL OR f_sub2.nota_credito = '')) AS facturas_afectadas
                FROM pedidos p
                INNER JOIN users u ON p.id_users = u.id
                INNER JOIN lista_pedidos lp ON p.id = lp.id_pedido
                INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id
                WHERE p.stat = 'C' AND u.rol = '2'
                AND (p.na_pedido IS NOT NULL AND p.na_pedido <> '') 
                AND p.fecha_sap BETWEEN :f_inicio AND :f_fin";

    if (!empty($filtro_cliente)) $sql_str .= " AND p.id_users = :id_cliente";
    if (!empty($filtro_linea)) $sql_str .= " AND fc.clase = :linea";
    

      $sql_str .= " ORDER BY fc.clase ASC, fc.codigo ASC, u.name ASC, p.fecha_sap DESC";

    $stmt_back = $base_de_datos->prepare($sql_str);
    $params = [':f_inicio' => $fecha_inicio, ':f_fin' => $fecha_fin];
    if ($filtro_cliente) $params[':id_cliente'] = $filtro_cliente;
    if ($filtro_linea) $params[':linea'] = $filtro_linea;
    
    $stmt_back->execute($params);
    $raw_data = $stmt_back->fetchAll(PDO::FETCH_ASSOC);

 $backorders = [];
    foreach ($raw_data as $r) {
        $cp = $r['id_prod'];
        $cu = $r['id_usuario'];

        if (!isset($backorders[$cp])) {
            $backorders[$cp] = [
                'codigo' => $r['codigo_comercial'], 
                'linea' => $r['linea'], 
                'sol' => 0, 
                'des' => 0, 
                'pedidos_count' => 0, 
                'clientes' => []
            ];
        }

        if (!isset($backorders[$cp]['clientes'][$cu])) {
            $backorders[$cp]['clientes'][$cu] = [
                'name' => $r['name_cliente'], 
                'sol' => 0, 
                'des' => 0, 
                'pedidos_count' => 0, 
                'detalles' => []
            ];
        }

        // Acumuladores por Producto
        $backorders[$cp]['sol'] += $r['solicitado'];
        $backorders[$cp]['des'] += $r['despachado'];
        $backorders[$cp]['pedidos_count']++;

        // Acumuladores por Cliente
        $backorders[$cp]['clientes'][$cu]['sol'] += $r['solicitado'];
        $backorders[$cp]['clientes'][$cu]['des'] += $r['despachado'];
        $backorders[$cp]['clientes'][$cu]['pedidos_count']++;
        $backorders[$cp]['clientes'][$cu]['detalles'][] = $r;
    }

    // --- SECCIÓN DE ORDENAMIENTO ---

    // 1. Ordenar Productos: Primero por Clase (A-Z) y luego por Código (Alfanumérico)
    uasort($backorders, function($a, $b) {
        $resLine = strnatcasecmp($a['linea'], $b['linea']);
        if ($resLine === 0) {
            return strnatcasecmp($a['codigo'], $b['codigo']);
        }
        return $resLine;
    });

    // 2. Ordenar Clientes: Dentro de cada producto, ordenar por nombre (A-Z)
    foreach ($backorders as &$producto) {
        uasort($producto['clientes'], function($ca, $cb) {
            return strnatcasecmp($ca['name'], $cb['name']);
        });
    }
    unset($producto); // Romper la referencia por seguridad

} catch (PDOException $e) { 
    die("Error en la base de datos: " . $e->getMessage()); 
}
?>

<div class="container p-0 my-5">
   <div class="text-center mb-4">
        <h1 class="titulo text-uppercase text-center"><strong>Backorders Ventas</strong></h1>
        <h5 class="text-muted">Panel Administrativo de Control</h5>
    </div>
    
    <!-- Filtros con el estilo exacto de image_1c4541.png -->
    <div class="card p-4 border-0 shadow-sm bg-white mb-4">
        <form method="POST" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="small fw-bold text-muted">Filtrar por Cliente:</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-person"></i></span>
                    <select name="id_cliente" class="form-select border-start-0 shadow-none">
                        <option value="">Todos los Clientes</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filtro_cliente == $c['id']) ? 'selected' : '' ?>><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted">Línea:</label>
                <select name="linea" class="form-select shadow-none">
                    <option value="">Todas las Líneas</option>
                    <?php foreach ($lineas as $l): ?>
                        <option value="<?= $l['clase'] ?>" <?= ($filtro_linea == $l['clase']) ? 'selected' : '' ?>><?= $l['clase'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted">Desde:</label>
                <input type="date" name="f_inicio" class="form-control shadow-none" value="<?= $fecha_inicio ?>">
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted">Hasta:</label>
                <input type="date" name="f_fin" class="form-control shadow-none" value="<?= $fecha_fin ?>">
            </div>
            <div class="col-md-3 d-flex gap-2">
    <button type="submit" class="btn btn-primary w-100 shadow-sm">
        <i class="bx bx-search me-1"></i>Buscar
    </button>
    <button type="submit" formaction="exportar_excel.php" class="btn btn-success w-100 shadow-sm">
        <i class="bx bxs-file-export me-1"></i>Excel
    </button>
</div>
        </form>
    </div>

    <!-- Estructura de Tabla Multinivel -->
    <div class="card bg-transparent border-0 shadow-sm">
        <div class='bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm border'>
            <table class="table align-middle text-center mb-0" >
               <thead class="table-light text-muted">
                <tr>
                    <th class="text-start ps-4">CÓDIGO PRODUCTO</th>
                    <th>VECES PED.</th>
                    <th>SOLICITADO</th>
                    <th class="text-success">DESPACHADO</th>
                    <th class="text-danger">PENDIENTE</th>
                    <th class="text-primary">ADICIONAL</th>
                    <th class="text-end pe-4">ACCIÓN</th>
                </tr>
            </thead>
                <tbody>
                    <?php foreach ($backorders as $id_p => $p): 
                        $p_sol = (float)$p['sol']; $p_des = (float)$p['des'];
                        $p_pen = max(0, $p_sol - $p_des);
                        $p_adi = ($p_sol - $p_des < 0) ? abs($p_sol - $p_des) : 0;
                    ?>
                    <tr class="border-bottom">
                        <td class="text-start ps-4">
                            <small class="text-muted d-block" style="font-size: 0.7rem;"><?= $p['linea'] ?></small>
                            <strong class="text-primary fs-5"><?= $p['codigo'] ?></strong>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-normal"><?= $p['pedidos_count'] ?></span></td>
                        <td class="fw-bold"><?= number_format($p_sol, 0) ?></td>
                        <td class="text-success fw-bold"><?= number_format($p_des, 0) ?></td>
                        <td class="text-danger fw-bold"><?= number_format($p_pen, 0) ?></td>
                        <td class="text-primary fw-bold"><?= ($p_adi > 0 ? '+'.number_format($p_adi, 0) : '0') ?></td>
                        <td><button class="btn btn-outline-primary"  style="font-size: 0.75rem;"  data-bs-toggle="collapse" data-bs-target="#col_p_<?= $id_p ?>">Detalle</button></td>
                    </tr>
                    <tr>
                        <td colspan="7" class="p-0 border-0">
                            <div class="collapse bg-light" id="col_p_<?= $id_p ?>">
                                <div class="p-4">
                                    <!-- Encabezados Nivel 2 -->
                                    <div class="row text-muted fw-bold mb-2 text-center" style="font-size: 0.65rem; text-transform: uppercase;">
                                        <div class="col-3 text-start ps-4">Cliente</div>
                                        <div class="col-1">Veces Ped.</div>
                                        <div class="col-2">Solicitado</div>
                                        <div class="col-2">Despachado</div>
                                        <div class="col-2">Pendiente</div>
                                        <div class="col-1">Adic.</div>
                                        <div class="col-1 text-end pe-4">Acción</div>
                                    </div>
                                    <?php foreach ($p['clientes'] as $id_u => $u): 
                                        $u_sol = (float)$u['sol']; $u_des = (float)$u['des'];
                                        $u_pen = max(0, $u_sol - $u_des);
                                        $u_adi = ($u_sol - $u_des < 0) ? abs($u_sol - $u_des) : 0;
                                    ?>
                                    <div class="card mb-2 border-0 shadow-sm rounded-3">
                                        <div class="card-body p-2">
                                            <div class="row align-items-center text-center">
                                                <div class="col-3 text-start ps-3"><strong class="small text-dark"><?= $u['name'] ?></strong></div>
                                                <div class="col-1"><span class="badge border text-dark fw-normal bg-white"><?= $u['pedidos_count'] ?></span></div>
                                                <div class="col-2 fw-bold small"><?= number_format($u_sol, 0) ?></div>
                                                <div class="col-2 fw-bold small text-success"><?= number_format($u_des, 0) ?></div>
                                                <div class="col-2 fw-bold small text-danger"><?= number_format($u_pen, 0) ?></div>
                                                <div class="col-1 fw-bold small text-primary"><?= ($u_adi > 0 ? '+'.number_format($u_adi, 0) : '0') ?></div>
                                                <div class="col-1 text-end pe-3"><button class="btn btn-info text-white btn-sm text-decoration-none fw-bold" 
                                                    style="font-size: 0.65rem;" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#col_u_<?= $id_p ?>_<?= $id_u ?>">
                                                Ver Pedidos
                                            </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Nivel 3: Historial -->
                                        <div class="collapse" id="col_u_<?= $id_p ?>_<?= $id_u ?>">
                                            <div class="bg-white border-top p-3 rounded-bottom">
                                               <table class="table table-sm table-borderless small mb-0 align-middle text-center ">
                                                   <thead class="text-muted border-bottom" style="font-size: 0.7rem;">
                                                    <tr>
                                                        <th>FECHA</th>
                                                        <th>PEDIDO</th>
                                                        <th>FACTURAS</th>
                                                        <th>SOLICITADO</th>
                                                        <th class="text-success">DESPACHADO</th>
                                                        <th class="text-danger">PENDIENTE</th>
                                                        <th class="text-primary">ADICIONAL</th>
                                                        <th style="width: 180px;">PROGRESO</th>
                                                    </tr>
                                                </thead>
                                                   <tbody class="align-middle text-center">
    <?php foreach ($u['detalles'] as $d): 
        $d_sol = (float)$d['solicitado']; 
        $d_des = (float)$d['despachado'];
        // Cálculos de Pendiente y Adicional
        $d_pen = max(0, $d_sol - $d_des);
        $d_adi = ($d_sol - $d_des < 0) ? abs($d_sol - $d_des) : 0;
        
        $porc = ($d_sol > 0) ? ($d_des / $d_sol) * 100 : 0;
        $badge = ($porc >= 100) ? "bg-success" : (($porc > 0) ? "bg-warning text-dark" : "bg-danger");
    ?>
    <tr class="border-bottom">
        <td><?= date('d/m/Y', strtotime($d['fecha_sap'])) ?></td>
        <td>
            <a href="./../pedido/index.php?id_ver=<?= $d['id_pedido_interno'] ?>" class="text-decoration-none">
                <span class="badge bg-light text-primary border"><i class="bi bi-eye me-1"></i><?= $d['na_pedido'] ?></span>
            </a>
        </td>
        <td class="text-muted small"><?= $d['facturas_afectadas'] ?: '-' ?></td>
        <td class="fw-bold"><?= number_format($d_sol, 0) ?></td>
        <td class="text-success fw-bold"><?= number_format($d_des, 0) ?></td>
        <td class="text-danger fw-bold"><?= number_format($d_pen, 0) ?></td>
        <td class="text-primary fw-bold"><?= ($d_adi > 0 ? '+'.number_format($d_adi, 0) : '0') ?></td>
        <td>
            <!-- Barra de Progreso Estilo Pill -->
            <div class="progress mb-2 rounded-pill" style="height: 8px; background-color: #e9ecef;">
                <div class="progress-bar bg-success rounded-pill" style="width: <?= min(100, $porc) ?>%;"></div>
            </div>
            <!-- Badge de Porcentaje -->
            <span class="badge rounded-pill <?= $badge ?> shadow-sm" style="font-size: 0.75rem; padding: 0.4em 1em;">
                <?= round($porc) ?>% Despachado
            </span>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../index/footer.php"); ?>