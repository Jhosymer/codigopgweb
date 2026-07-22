<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('./../../../config/conex_combos.php');
header('Content-Type: application/json');

$id_pro = isset($_POST['id_pro']) ? (int)$_POST['id_pro'] : 0;
$id_users = isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0;

try {
    // 1. Consulta del balance total
    $sql_balance = "SELECT 
                fc.codigo,
                (SELECT SUM(lp2.cantidad) FROM lista_pedidos lp2 
                 INNER JOIN pedidos p2 ON lp2.id_pedido = p2.id 
                 WHERE p2.id_users = ? AND lp2.id_producto = ?) as total_solicitado,
                (SELECT IFNULL(SUM(lf.cantidad), 0) FROM lista_factura lf 
                 INNER JOIN factura f_sub ON lf.id_factura = f_sub.id 
                 WHERE lf.id_producto = ? AND f_sub.id_users = ?) as total_despachado
             FROM filtro_codificacion fc
             WHERE fc.id = ? LIMIT 1";

    $stmt = $pdo->prepare($sql_balance);
    $stmt->execute([$id_users, $id_pro, $id_pro, $id_users, $id_pro]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $pendiente = ($data) ? (float)($data['total_solicitado'] - $data['total_despachado']) : 0;

    // 2. Consulta de pedidos filtrada
    // Usamos HAVING para asegurar que el saldo de (Pedido - Facturado) sea > 0
   // 2. Consulta de pedidos filtrada (Lógica espejo del balance)
    // Usamos una subconsulta en el WHERE para descartar pedidos sin saldo pendiente
    $sql_pedidos = "SELECT 
    p.id, 
    p.na_pedido, 
    p.stat, 
    lp.cantidad
FROM pedidos p
INNER JOIN lista_pedidos lp ON p.id = lp.id_pedido
WHERE p.id_users = ? 
  AND lp.id_producto = ?
  AND (
      -- Condición 1: Pedidos con saldo pendiente usando la relación exacta na_pedido
      (lp.cantidad - (
          SELECT IFNULL(SUM(lf.cantidad), 0)
          FROM lista_factura lf
          INNER JOIN factura f ON lf.id_factura = f.id
          WHERE lf.id_producto = lp.id_producto
          AND lf.na_pedido = p.na_pedido  -- Aquí está la relación clave que me diste
      )) > 0
      OR 
      -- Condición 2: Pedidos abiertos (na_pedido vacío o nulo)
      (p.na_pedido IS NULL OR p.na_pedido = '')
  );";
    
    $stmt_p = $pdo->prepare($sql_pedidos);
    $stmt_p->execute([$id_users, $id_pro]);
    $lista_pedidos = $stmt_p->fetchAll(PDO::FETCH_ASSOC);

    // 3. Generar HTML
    $html_detalle = '';
    if ($pendiente > 0 && !empty($lista_pedidos)) {
        $html_detalle = '
        <div class="mt-3 text-start">
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#listaPedidos" 
                    style="padding: 0.375rem 0.75rem; font-size: 0.875rem; border-radius: 0.25rem; display: inline-block;">
                Ver pedidos relacionados
            </button>
            <div class="collapse mt-2" id="listaPedidos">
                <ul class="list-group list-group-flush">';

        foreach ($lista_pedidos as $i => $row) {
            $id = $row['id'];
            $label = !empty($row['na_pedido']) ? "PEDIDO " . $row['na_pedido'] : "PEDIDO ABIERTO (" . ($i + 1) . ")";
            $btn_class = ($row['stat'] == 'G' || $row['stat'] == '') ? 'btn-info' : 'btn-primary';
            $icon = ($row['stat'] == 'G' || $row['stat'] == '') ? 'edit' : 'search';
            $link = ($row['stat'] == 'G' || $row['stat'] == '') ? "index.php?pag=pedido&id=$id" : "index.php?pag=pedido&id_ver=$id";

            $html_detalle .= "
                <li class='list-group-item d-flex justify-content-between align-items-center' style='padding: 0.3rem 0.5rem;'>
                    <span style='font-size: 0.85em;'>• $label</span>
                    <a href='$link' target='_blank' class='btn btn-sm $btn_class' style='padding: 0.1rem 0.4rem; font-size: 0.75rem;'>
                        <i data-feather='$icon' style='width: 14px; height: 14px;'></i>
                    </a>
                </li>";
        }
        $html_detalle .= '</ul></div></div>';
    }

    echo json_encode([
        'status' => ($pendiente > 0) ? 'alerta' : 'ok',
        'codigo' => $data['codigo'] ?? 'N/A',
        'pendiente' => $pendiente,
        'html' => $html_detalle
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
}
exit;