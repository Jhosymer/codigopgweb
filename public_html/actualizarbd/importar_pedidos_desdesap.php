<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
date_default_timezone_set('America/Caracas');
header('Access-Control-Allow-Origin: *');

require_once "./../config/conexion.php"; 

// --- BLOQUEO DE SEGURIDAD ---
$lockFile = './../ftp/pedidos_sap.lock';
if (file_exists($lockFile)) {
    die("<div style='color: orange;'>Proceso en ejecución, saltando para proteger el CPU.</div>");
}
file_put_contents($lockFile, getmypid());

$rutaJson = "./../ftp/pedidos_desdeSap.json";
$rutaDestino = "./../ftp/pedidosdesdesapprocesados.json"; 

echo "<div style='font-family: Arial, sans-serif; padding: 20px;'>";
echo "<h2>Importación SAP -> Web</h2><hr>";

$procesadosLog = [];

if (!file_exists($rutaJson) || !($pedidosLista = json_decode(file_get_contents($rutaJson), true))) {
    file_put_contents($rutaDestino, json_encode($procesadosLog, JSON_PRETTY_PRINT), LOCK_EX);
    if (file_exists($lockFile)) unlink($lockFile);
    die("<div style='color: red;'>Error: Archivo no encontrado o JSON inválido.</div>");
}

// --- CONSULTAS PREPARADAS FUERA DEL BUCLE ---
$stmtCheck = $base_de_datos->prepare("SELECT id FROM pedidos WHERE na_pedido = ?");
$stmtUser = $base_de_datos->prepare("SELECT id FROM users WHERE rif = ? LIMIT 1");
$stmtBuscaProd = $base_de_datos->prepare("
    SELECT t1.id FROM filtro_codificacion AS t1 
    LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
    WHERE t1.codigo = :cod OR t2.codigo_alt = :cod LIMIT 1
");
$stmtPed = $base_de_datos->prepare("INSERT INTO pedidos (id_users, na_pedido, fecha_sap, fecha, total_pedido, numero_oc, stat, origen) VALUES (?, ?, ?, ?, ?, ?, 'C', 'sap')");
$stmtItem = $base_de_datos->prepare("INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad, precio_u, total) VALUES (?, ?, ?, ?, ?)");

foreach ($pedidosLista as $data) {
    $numPedidoSap = $data['Num_pedido'];

    // 1. Evitar duplicados
    $stmtCheck->execute([$numPedidoSap]);
    if ($existe = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $procesadosLog[] = ["id" => (int)$existe['id'], "na_pedido" => $numPedidoSap];
        echo "<div style='color: orange;'>🔸 $numPedidoSap ya existe (ID: {$existe['id']}).</div>";
        continue;
    }

    // 2. Buscar Usuario
    $stmtUser->execute([$data['rif']]);
    if (!($user = $stmtUser->fetch(PDO::FETCH_ASSOC))) {
        echo "<div style='color: blue;'>🔹 RIF <b>{$data['rif']}</b> no existe.</div>";
        continue;
    }

    // 3. Procesar número OC
    $numero_oc = '';
    if (isset($data['comentario']) && preg_match('/^(OC|O\/C)\s+/i', $data['comentario'])) {
        $numero_oc = trim(explode(',', preg_replace('/^(OC|O\/C)\s+/i', '', $data['comentario']))[0]);
    }

    // 4. Inserción transaccional
    try {
        $base_de_datos->beginTransaction();
        
        $stmtPed->execute([$user['id'], $numPedidoSap, $data['fecha_pedido'], $data['fecha_pedido'], $data['total_pedido'], $numero_oc]);
        $id_pedido_nuevo = $base_de_datos->lastInsertId();

        foreach ($data['DocumentLines'] as $linea) {
            $stmtBuscaProd->execute([':cod' => $linea['codigo']]);
            if (!($prod = $stmtBuscaProd->fetch(PDO::FETCH_ASSOC))) {
                throw new Exception("Producto " . $linea['codigo'] . " no mapeado.");
            }
            $stmtItem->execute([$id_pedido_nuevo, $prod['id'], $linea['cantidad'], $linea['precio_und'], $linea['total_item']]);
        }

        $base_de_datos->commit();
        $procesadosLog[] = ["id" => (int)$id_pedido_nuevo, "na_pedido" => $numPedidoSap];
        echo "<div style='color: green;'>✅ $numPedidoSap registrado exitosamente.</div>";

    } catch (Exception $e) {
        if ($base_de_datos->inTransaction()) $base_de_datos->rollBack();
        echo "<div style='color: red;'>❌ Error en $numPedidoSap: " . $e->getMessage() . "</div>";
    }
}

file_put_contents($rutaDestino, json_encode($procesadosLog, JSON_PRETTY_PRINT), LOCK_EX);
if (file_exists($lockFile)) unlink($lockFile);

echo "<hr><p>Proceso finalizado.</p>";
echo "</div>";
?>