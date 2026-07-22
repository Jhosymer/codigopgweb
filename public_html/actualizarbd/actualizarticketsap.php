<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
date_default_timezone_set('America/Caracas');

require_once "./../config/conexion.php"; 

// --- BLOQUEO DE SEGURIDAD PARA CPU ---
$lockFile = './../ftp/tickets_sap.lock';
if (file_exists($lockFile)) {
    die("<div class='alert alert-warning'>Proceso en ejecución, saltando para proteger el servidor.</div>");
}
file_put_contents($lockFile, getmypid());

$rutaJson = "./../ftp/llamadasprocesadas.json";

echo "<div class='container mt-4'>";
echo "<h2 class='border-bottom pb-2'>Actualización de Tickets SAP</h2>";

if (!file_exists($rutaJson) || !($contenido = file_get_contents($rutaJson)) || !($llamadasLista = json_decode($contenido, true))) {
    if (file_exists($lockFile)) unlink($lockFile);
    die("<div class='alert alert-danger'>Error: Archivo no encontrado o JSON inválido.</div>");
}

$contadorActualizados = 0;
$stmtUpdate = $base_de_datos->prepare("UPDATE tickt_soporte_sap SET num_tick_sap = :id_llamada_sap WHERE id_soporte = :id_ticket");

try {
    // --- USO DE TRANSACCIÓN PARA OPTIMIZAR ESCRITURA EN DISCO ---
    $base_de_datos->beginTransaction();

    foreach ($llamadasLista as $item) {
        $id_llamada_sap = $item['IDLlamada'] ?? null;
        $id_ticket      = $item['U_Procesadoweb'] ?? null;

        if ($id_llamada_sap && $id_ticket) {
            $stmtUpdate->execute([':id_llamada_sap' => $id_llamada_sap, ':id_ticket' => $id_ticket]);
            if ($stmtUpdate->rowCount() > 0) $contadorActualizados++;
        }
    }
    
    $base_de_datos->commit();
    echo "<div class='alert alert-success'>Proceso finalizado. Registros actualizados exitosamente: <b>$contadorActualizados</b></div>";

} catch (Exception $e) {
    $base_de_datos->rollBack();
    echo "<div class='alert alert-danger'>Error crítico: " . $e->getMessage() . "</div>";
} finally {
    // Liberamos el bloqueo siempre
    if (file_exists($lockFile)) unlink($lockFile);
}

echo "</div>";
?>