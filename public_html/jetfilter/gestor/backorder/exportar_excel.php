<?php
$loc = "../../../";
$locj = "./../../";
require_once($loc . "config/conexion.php");

// 1. Gestión de Fechas y Filtros
$fecha_inicio   = isset($_POST['f_inicio']) ? $_POST['f_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin      = isset($_POST['f_fin']) ? $_POST['f_fin'] : date('Y-m-d');
$filtro_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : '';
$filtro_linea   = isset($_POST['linea']) ? $_POST['linea'] : '';

try {
    // 2. Consulta SQL
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
                     AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) AS despachado
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

    // 3. Agrupamiento Producto -> Cliente -> Pedidos
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

        $backorders[$cp]['sol'] += $r['solicitado'];
        $backorders[$cp]['des'] += $r['despachado'];
        $backorders[$cp]['pedidos_count']++;

        $backorders[$cp]['clientes'][$cu]['sol'] += $r['solicitado'];
        $backorders[$cp]['clientes'][$cu]['des'] += $r['despachado'];
        $backorders[$cp]['clientes'][$cu]['pedidos_count']++;
        $backorders[$cp]['clientes'][$cu]['detalles'][] = $r;
    }

    // Configuración para descarga Excel
    $filename = "Backorders_" . date('Ymd_His') . ".xls";
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "\xEF\xBB\xBF"; // UTF-8 BOM
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9pt; }
        td, th { border: 1px solid #000000; text-align: center; vertical-align: middle; padding: 3px 6px; }
        
        /* Encabezados grises generales */
        .head-gray { background-color: #AEAAAA; font-weight: bold; color: #000000; text-align: center; }

        /* Columna de Producto a la izquierda */
        .col-producto-header {
            background-color: #AEAAAA;
            font-weight: bold;
            text-align: left;
            border-right: 2px solid #000000 !important;
        }
        .col-producto-code {
            color: #0044cc;
            font-weight: bold;
            text-align: center;
            border-right: 2px solid #000000 !important;
            border-top: 1px solid #000000 !important;
            border-bottom: 1px solid #000000 !important;
            background-color: #D8D8D8;
        }
        .col-producto-empty {
            border-left: none !important;
            border-top: none !important;
            border-bottom: none !important;
            border-right: 2px solid #000000 !important;
            background-color: #ffffff;
        }

        /* Fila de datos general del producto en gris claro */
        .row-prod-data {
            background-color: #D8D8D8;
            font-weight: bold;
        }

        /* Columna de Cliente (Identada debajo del producto) */
        .col-cliente-name {
            font-weight: bold;
            text-align: center;
            border: 1px solid #000000 !important;
            background-color: #ffffff;
        }

        /* Colores de texto numéricos */
        .txt-green { color: #198754; font-weight: bold; }
        .txt-red { color: #dc3545; font-weight: bold; }
        .txt-blue { color: #0d6efd; font-weight: bold; }
        
        /* Celda de Porcentaje (%) */
        .prog-100 { background-color: #198754; color: #ffffff; font-weight: bold; }
        .prog-par { background-color: #ffc107; color: #000000; font-weight: bold; }
        .prog-zero { background-color: #dc3545; color: #ffffff; font-weight: bold; }
        
        .no-border { border: none !important; background-color: #ffffff; }
        .row-sep { border: none; height: 12px; background-color: #ffffff; }
    </style>
</head>
<body>
   <table>
        <tbody>
            <?php foreach ($backorders as $id_p => $p): 
                $p_sol  = (float)$p['sol']; 
                $p_des  = (float)$p['des'];
                $p_pen  = max(0, $p_sol - $p_des);
                $p_adi  = ($p_sol - $p_des < 0) ? abs($p_sol - $p_des) : 0;
                $p_porc = ($p_sol > 0) ? ($p_des / $p_sol) * 100 : 0;

                $p_cls_prog = ($p_porc >= 100) ? 'prog-100' : (($p_porc > 0) ? 'prog-par' : 'prog-zero');
            ?>
                <!-- 1. BLOQUE PRODUCTO -->
                <tr>
                    <th class="col-producto-header">PRODUCTO</th>
                    <th class="head-gray">CLIENTE</th>
                    <th class="head-gray">VECES PED.</th>
                    <th class="head-gray">SOLICITADO</th>
                    <th class="head-gray" style="color: #198754;">RECIBIDO</th>
                    <th class="head-gray" style="color: #dc3545;">PENDIENTE</th>
                    <th class="head-gray" style="color: #0d6efd;">ADICIONAL</th>
                    <th class="head-gray">PROGRESO TOTAL</th>
                </tr>
                <tr class="row-prod-data">
                    <td class="col-producto-code"><?= htmlspecialchars($p['codigo']) ?></td>
                    <!-- Relleno del espacio en gris claro para mantener la fila uniforme -->
                    <td style="background-color: #D8D8D8; border: 1px solid #000000;"></td>
                    <td><?= $p['pedidos_count'] ?></td>
                    <td><?= number_format($p_sol, 0) ?></td>
                    <td class="txt-green"><?= number_format($p_des, 0) ?></td>
                    <td class="txt-red"><?= number_format($p_pen, 0) ?></td>
                    <td class="txt-blue"><?= number_format($p_adi, 0) ?></td>
                    <td class="<?= $p_cls_prog ?>"><?= round($p_porc) ?>%</td>
                </tr>

                <!-- RECORRIDO CLIENTES -->
                <?php foreach ($p['clientes'] as $id_u => $u): 
                    $u_sol = (float)$u['sol']; 
                    $u_des = (float)$u['des'];
                    $u_pen = max(0, $u_sol - $u_des);
                    $u_adi = ($u_sol - $u_des < 0) ? abs($u_sol - $u_des) : 0;
                ?>
                    <!-- 2. BLOQUE CLIENTE -->
                    <tr>
                        <td class="col-producto-empty"></td>
                        <td class="no-border"></td>
                        <th class="head-gray">VECES PED.</th>
                        <th class="head-gray">SOLICITADO</th>
                        <th class="head-gray" style="color: #198754;">RECIBIDO</th>
                        <th class="head-gray" style="color: #dc3545;">PENDIENTE</th>
                        <th class="head-gray" style="color: #0d6efd;">ADICIONAL</th>
                        <td class="no-border"></td>
                    </tr>
                    <tr>
                        <td class="col-producto-empty"></td>
                        <td class="col-cliente-name"><?= htmlspecialchars($u['name']) ?></td>
                        <td style="font-weight: bold;"><?= $u['pedidos_count'] ?></td>
                        <td><?= number_format($u_sol, 0) ?></td>
                        <td class="txt-green"><?= number_format($u_des, 0) ?></td>
                        <td class="txt-red"><?= number_format($u_pen, 0) ?></td>
                        <td class="txt-blue"><?= number_format($u_adi, 0) ?></td>
                        <td class="no-border"></td>
                    </tr>

                    <!-- 3. DETALLE DE PEDIDO Y PROGRESO -->
                    <?php foreach ($u['detalles'] as $d): 
                        $d_sol = (float)$d['solicitado']; 
                        $d_des = (float)$d['despachado'];
                        $d_pen = max(0, $d_sol - $d_des);
                        $d_adi = ($d_sol - $d_des < 0) ? abs($d_sol - $d_des) : 0;
                        $porc  = ($d_sol > 0) ? ($d_des / $d_sol) * 100 : 0;

                        $cls_prog = ($porc >= 100) ? 'prog-100' : (($porc > 0) ? 'prog-par' : 'prog-zero');
                    ?>
                        <tr>
                            <td class="col-producto-empty"></td>
                            <td class="no-border"></td>
                            <th class="head-gray">N° PEDIDO</th>
                            <th class="head-gray">SOLICITADO</th>
                            <th class="head-gray" style="color: #198754;">RECIBIDO</th>
                            <th class="head-gray" style="color: #dc3545;">PENDIENTE</th>
                            <th class="head-gray" style="color: #0d6efd;">ADICIONAL</th>
                            <th class="head-gray">PROGRESO</th>
                        </tr>
                        <tr>
                            <td class="col-producto-empty"></td>
                            <td class="no-border"></td>
                            <td style="font-weight: bold;"><?= htmlspecialchars($d['na_pedido']) ?></td>
                            <td><?= number_format($d_sol, 0) ?></td>
                            <td class="txt-green"><?= number_format($d_des, 0) ?></td>
                            <td class="txt-red"><?= number_format($d_pen, 0) ?></td>
                            <td class="txt-blue"><?= number_format($d_adi, 0) ?></td>
                            <td class="<?= $cls_prog ?>"><?= round($porc) ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <!-- Espaciador entre bloques de productos -->
                <tr><td colspan="8" class="row-sep"></td></tr>

            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
<?php
} catch (PDOException $e) {
    die("Error al generar el archivo Excel: " . $e->getMessage());
}