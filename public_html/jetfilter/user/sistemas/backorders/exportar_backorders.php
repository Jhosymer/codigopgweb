<?php
// Conexión a base de datos
include("./../../../config/conex.php");

$fecha_inicio = isset($_GET['f_inicio']) ? $_GET['f_inicio'] : date('Y-m-d', strtotime('-30 days'));
$fecha_fin    = isset($_GET['f_fin']) ? $_GET['f_fin'] : date('Y-m-d');
$id_users     = isset($_GET['id_users']) ? $_GET['id_users'] : '';

// Consulta SQL
$sql_back = "SELECT p.id AS id_pedido_interno, p.na_pedido, p.fecha_sap, fc.id AS id_prod, fc.codigo AS codigo_comercial, lp.cantidad AS solicitado,
            (SELECT IFNULL(SUM(lf.cantidad), 0) FROM lista_factura lf INNER JOIN factura f_sub ON lf.id_factura = f_sub.id 
             WHERE lf.na_pedido = p.na_pedido AND lf.id_producto = lp.id_producto AND f_sub.id_users = p.id_users 
             AND (f_sub.nota_credito IS NULL OR f_sub.nota_credito = '')) AS despachado
            FROM pedidos p
            INNER JOIN lista_pedidos lp ON p.id = lp.id_pedido
            INNER JOIN filtro_codificacion fc ON lp.id_producto = fc.id
            WHERE p.id_users = '$id_users' AND p.stat = 'C' AND p.na_pedido <> ''
            AND p.fecha_sap BETWEEN '$fecha_inicio' AND '$fecha_fin'
            ORDER BY fc.codigo ASC, p.fecha_sap DESC";

$res_back = $linki->query($sql_back);
$backorders = [];
while ($r = $res_back->fetch_assoc()) {
    $cp = $r['id_prod'];
    if (!isset($backorders[$cp])) {
        $backorders[$cp] = ['codigo' => $r['codigo_comercial'], 'sol' => 0, 'des' => 0, 'pedidos_count' => 0, 'detalles' => []];
    }
    $backorders[$cp]['sol'] += (float)$r['solicitado'];
    $backorders[$cp]['des'] += (float)$r['despachado'];
    $backorders[$cp]['pedidos_count']++;
    $backorders[$cp]['detalles'][] = $r;
}

// Configuración de cabeceras para forzar Excel con UTF-8
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Reporte_Backorders_" . date('Y-m-d') . ".xls");
echo "\xEF\xBB\xBF"; 

echo "<table border='1'>";
foreach ($backorders as $p) {
    // Fila 1: Resumen del Producto
    echo "<tr style='background-color:#f8f9fa; font-weight:bold;'>
            <th>PRODUCTO</th><th>VECES PED.</th><th>SOLICITADO</th><th style='color:#1CBB8C;'>RECIBIDO</th><th style='color:#DC3545;'>PENDIENTE</th><th style='color:#007bff;'>ADICIONAL</th>
          </tr>";
    echo "<tr>
            <td><b>{$p['codigo']}</b></td><td><b>{$p['pedidos_count']}</b></td><td>{$p['sol']}</td>
            <td style='color:#1CBB8C; font-weight:bold;'>{$p['des']}</td>
            <td style='color:#DC3545; font-weight:bold;'>" . max(0, $p['sol'] - $p['des']) . "</td>
            <td style='color:#007bff; font-weight:bold;'>" . (($p['des'] > $p['sol']) ? ($p['des'] - $p['sol']) : 0) . "</td>
          </tr>";
    
    // Fila 2: Cabecera Detalle
    echo "<tr style='background-color:#e9ecef; font-weight:bold;'>
            <th>FECHA PEDIDO</th><th>N° PEDIDO</th><th>SOLICITADO</th><th style='color:#1CBB8C;'>RECIBIDO</th><th style='color:#DC3545;'>PENDIENTE</th><th style='color:#007bff;'>ADICIONAL</th><th>PROGRESO</th>
          </tr>";
    
    foreach ($p['detalles'] as $d) {
        $d_sol = (float)$d['solicitado'];
        $d_des = (float)$d['despachado'];
        $d_porc = ($d_sol > 0) ? round(($d_des / $d_sol) * 100) : 0;
        
        // Lógica de colores de fondo según porcentaje
        if ($d_porc >= 100) { $bg = "#1CBB8C"; $txtC = "#fff"; }
        elseif ($d_porc > 0) { $bg = "#FFC107"; $txtC = "#000"; }
        else { $bg = "#DC3545"; $txtC = "#fff"; }

        echo "<tr>
                <td>" . date('d/m/Y', strtotime($d['fecha_sap'])) . "</td>
                <td><b>{$d['na_pedido']}</b></td>
                <td>$d_sol</td>
                <td style='color:#1CBB8C; font-weight:bold;'>$d_des</td>
                <td style='color:#DC3545; font-weight:bold;'>" . max(0, $d_sol - $d_des) . "</td>
                <td style='color:#007bff; font-weight:bold;'>" . (($d_des > $d_sol) ? ($d_des - $d_sol) : 0) . "</td>
                <td style='background-color:$bg; mso-pattern:gray-125 $bg; color:$txtC; text-align:center; font-weight:bold;'>$d_porc% Recibido</td>
              </tr>";
    }
    // Fila de separación
    echo "<tr><td colspan='7' style='border:none;'>&nbsp;</td></tr>";
}
echo "</table>";
?>