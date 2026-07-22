<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
date_default_timezone_set('America/Caracas');

require_once "./../config/conexion.php"; 

// --- BLOQUEO DE SEGURIDAD PARA CPU ---
$lockFile = './../ftp/pagos_sap.lock';
if (file_exists($lockFile)) {
    die("<div style='color:orange;'>Proceso en ejecución, por favor espere a que termine la sincronización actual.</div>");
}
file_put_contents($lockFile, getmypid());

$rutaJson = "./../ftp/pagosrecibidos_Sap.json";
$rutaResultadoJson = "./../ftp/pagosrecibidos_Sappor.json"; 

echo "<div style='font-family: Arial, sans-serif; padding: 20px;'>";
echo "<h2><i class='bx bx-sync'></i> Sincronización Integral de Pagos SAP</h2>";

if (!file_exists($rutaJson) || !($contenido = file_get_contents($rutaJson)) || !($pagosLista = json_decode($contenido, true))) {
    if (file_exists($lockFile)) unlink($lockFile);
    die("<p style='color:red;'>Error: No se encontró el archivo origen o el JSON es inválido.</p>");
}

// --- CONSULTAS PREPARADAS FUERA DEL BUCLE ---
$stmtCheck = $base_de_datos->prepare("SELECT id FROM pagos WHERE num_pago_sap = ? LIMIT 1");
$stmtUser = $base_de_datos->prepare("SELECT id FROM users WHERE rif = ? LIMIT 1");
$stmtInsertCab = $base_de_datos->prepare("INSERT INTO pagos (id_users, num_pago_sap, fecha_pago, referencia, moneda, tasa_cambio, total_pago, total_bs, comentarios, sap_reference_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmtInsertDet = $base_de_datos->prepare("INSERT INTO pagos_detalle (id_pagos, num_fact_externa, num_control, fecha_factura, comprobante_retencion, tipo_doc, plazo, monto_retencion, monto_aplicado, monto_bs) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$contNuevos = 0; $contYaExisten = 0; $contErrores = 0;
$soloNuevosReg = [];

foreach ($pagosLista as $pago) {
    $numSap = $pago['num_pago'];
    $sapRefId = $pago['id_pago'];

    // 1. Verificar duplicidad
    $stmtCheck->execute([$numSap]);
    if ($pagoExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $soloNuevosReg[] = ["id_pago_web" => $pagoExistente['id'], "num_pago" => $sapRefId];
        echo "<p style='color:orange;'>Pago SAP #$numSap: Ya existe.</p>";
        $contYaExisten++;
        continue;
    }

    // 2. Validar usuario
    $stmtUser->execute([$pago['rif']]);
    $userRow = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($userRow) {
        try {
            $base_de_datos->beginTransaction();

            $documentos = $pago['DocumentosPagados'] ?? [];
            $ref = (!empty($documentos)) ? $documentos[0]['comprobante_retencion'] : $numSap;

            $stmtInsertCab->execute([
                $userRow['id'], $numSap, $pago['fecha_pago'], $ref, $pago['moneda'], 
                $pago['tasa_cambio'], $pago['total_usd'], $pago['total_bs'], $pago['comentarios'], $sapRefId
            ]);
            $idMaestro = $base_de_datos->lastInsertId();

            foreach ($documentos as $doc) {
                $stmtInsertDet->execute([
                    $idMaestro, $doc['num_documento'], $doc['num_control'], $doc['fecha_factura'], 
                    $doc['comprobante_retencion'], $doc['tipo'], $doc['plazo'], $doc['monto_retencion'], 
                    $doc['monto_usd'], $doc['monto_bs']
                ]);
            }

            $base_de_datos->commit();
            $soloNuevosReg[] = ["id_pago_web" => $idMaestro, "num_pago" => $sapRefId];
            echo "<p style='color:green;'>Pago SAP #$numSap: Registrado.</p>";
            $contNuevos++;

        } catch (Exception $e) {
            if ($base_de_datos->inTransaction()) $base_de_datos->rollBack();
            echo "<p style='color:red;'>Error crítico en Pago #$numSap.</p>";
            $contErrores++;
        }
    } else {
        echo "<p style='color:red;'>Error: RIF {$pago['rif']} no encontrado.</p>";
        $contErrores++;
    }
}

file_put_contents($rutaResultadoJson, json_encode($soloNuevosReg, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
if (file_exists($lockFile)) unlink($lockFile);

echo "<hr><h4>Resumen Final:</h4>";
echo "Nuevos: $contNuevos | Existentes: $contYaExisten | Errores: $contErrores";
echo "</div>";
?>