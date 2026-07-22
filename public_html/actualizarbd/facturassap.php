<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
date_default_timezone_set('America/Caracas');

require_once "./../config/conexion.php"; 

// --- BLOQUEO DE SEGURIDAD ---
$lockFile = './../ftp/facturas_sap.lock';
if (file_exists($lockFile)) {
    die("<div class='alert alert-warning'>Proceso en ejecución, saltando.</div>");
}
file_put_contents($lockFile, getmypid());

$rutaJson = "./../ftp/facturas_Sap.json";
$rutaDestino = "./../ftp/facturaspro.json"; 

echo "<div class='container mt-4'>";
echo "<h2>Proceso de Importación SAP -> Web</h2>";

if (!file_exists($rutaJson) || !($contenido = file_get_contents($rutaJson)) || !($facturasLista = json_decode($contenido, true))) {
    if (file_exists($lockFile)) unlink($lockFile);
    die("<div class='alert alert-danger'>Error: Archivo no encontrado o JSON inválido.</div>");
}

if (isset($facturasLista['Num_fact'])) $facturasLista = [$facturasLista];

// --- PREPARACIÓN DE CONSULTAS ---
$stmtCheckFact = $base_de_datos->prepare("SELECT id FROM factura WHERE num_fact = ?");
$stmtCheckUser = $base_de_datos->prepare("SELECT id FROM users WHERE rif = ? LIMIT 1");
$stmtBuscaProd = $base_de_datos->prepare("
    SELECT t1.id FROM filtro_codificacion AS t1 
    LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
    WHERE (t1.codigo = :cod OR t2.codigo_alt = :cod) 
    AND (t1.act_sap = 'Y' OR t2.act_sap = 'Y') LIMIT 1
");
$stmtFact = $base_de_datos->prepare("INSERT INTO factura (id_users, num_fact, num_control, fecha_contab, fecha_venc, valor_Moneda, Moneda, iva, porcentaje_iva, porcentaje_descuento, descuento, total_aplicada_retenciones, retencion, sub_total, total_fact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmtItem = $base_de_datos->prepare("INSERT INTO lista_factura (id_factura, id_producto, na_pedido, precio, cantidad, total) VALUES (?, ?, ?, ?, ?, ?)");

$procesadosLog = [];

foreach ($facturasLista as $data) {
    // 1. Validar existencia
    $stmtCheckFact->execute([$data['Num_fact']]);
    if ($facturaExistente = $stmtCheckFact->fetch(PDO::FETCH_ASSOC)) {
        $procesadosLog[] = ["id" => $facturaExistente['id'], "num_fact" => $data['Num_fact']];
        continue; 
    }

    // 2. Validar Usuario
    $stmtCheckUser->execute([$data['rif']]);
    $user = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);
    if (!$user) continue;

    try {
        $base_de_datos->beginTransaction();

        $stmtFact->execute([
            $user['id'], $data['Num_fact'], ($data['num_control'] ?? ''), $data['fecha_contab'],
            $data['fecha_venc'], $data['valor_Moneda'], $data['Moneda'], $data['iva'],
            $data['porcentaje_iva'], $data['porcentaje_descuento'], $data['descuento'],
            $data['total_apli_retenciones'], $data['retencion'], $data['sub_total'], $data['total_fact']
        ]);

        $id_factura = $base_de_datos->lastInsertId();

        foreach ($data['DocumentLines'] as $linea) {
            $stmtBuscaProd->execute([':cod' => $linea['codigo']]);
            $prod = $stmtBuscaProd->fetch(PDO::FETCH_ASSOC);
            if (!$prod) throw new Exception("Producto " . $linea['codigo'] . " no hallado.");

            $stmtItem->execute([$id_factura, $prod['id'], $linea['referencia_pedido'], $linea['precio_und'], $linea['cantidad'], $linea['total_item']]);
        }

        $base_de_datos->commit();
        $procesadosLog[] = ["id" => $id_factura, "num_fact" => $data['Num_fact']];
        echo "<div>Factura <b>" . $data['Num_fact'] . "</b> importada con éxito.</div>";

  } catch (Exception $e) {
        if ($base_de_datos->inTransaction()) $base_de_datos->rollBack();
        }
        echo "<div class='alert alert-danger py-1'>Error en factura " . $data['Num_fact'] . ": " . $e->getMessage() . "</div>";
 }

file_put_contents($rutaDestino, json_encode($procesadosLog, JSON_PRETTY_PRINT), LOCK_EX);
if (file_exists($lockFile)) unlink($lockFile);

echo "<div class='alert alert-success'>Proceso finalizado. Registros procesados: " . count($procesadosLog) . "</div></div>";
?>