<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
date_default_timezone_set('America/Caracas');

require_once "./../config/conexion.php"; 

// --- BLOQUEO DE SEGURIDAD ---
$lockFile = './../ftp/estado_cuenta.lock';
if (file_exists($lockFile)) {
    die("<div class='alert alert-warning'>Proceso en ejecución. Por favor, espere.</div>");
}
file_put_contents($lockFile, getmypid());

$rutaJson = "./../ftp/estado_cuenta_Sap.json";

echo "<div class='container mt-4'>";
echo "<h2 class='mb-4 text-primary'>Sincronización: SAP -> Base de Datos</h2>";

if (!file_exists($rutaJson) || !($contenido = file_get_contents($rutaJson)) || !($datosClientes = json_decode($contenido, true))) {
    if (file_exists($lockFile)) unlink($lockFile);
    die("<div class='alert alert-danger'>Error: Archivo no encontrado o JSON inválido.</div>");
}

// --- SENTENCIAS PREPARADAS FUERA DEL BUCLE ---
$stmtUser = $base_de_datos->prepare("SELECT id FROM users WHERE rif = ? LIMIT 1");
$stmtSetZero = $base_de_datos->prepare("UPDATE sap_estado_cuenta SET saldo_vencido = 0 WHERE id_users = ?");
$stmtUpsert = $base_de_datos->prepare("
    INSERT INTO sap_estado_cuenta (id_users, fecha, documento, fuente, detalle, moneda, monto, saldo_vencido, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
    monto = VALUES(monto), saldo_vencido = VALUES(saldo_vencido), detalle = VALUES(detalle), 
    fecha = VALUES(fecha), created_at = VALUES(created_at)
");

$ahora = date("Y-m-d H:i:s");

foreach ($datosClientes as $cliente) {
    $rif = $cliente['rif'];
    $stmtUser->execute([$rif]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) continue;

    $id_user = $user['id'];

    try {
        $base_de_datos->beginTransaction();

        // 1. Resetear saldos
        $stmtSetZero->execute([$id_user]);

        // 2. Insertar/Actualizar movimientos
        foreach ($cliente['movimientos'] as $mov) {
            $f = explode('/', $mov['fecha']);
            $fechaSQL = $f[2] . '-' . $f[1] . '-' . $f[0];

            $stmtUpsert->execute([
                $id_user, $fechaSQL, $mov['documento'], $mov['fuente'], 
                $mov['detalle'], $mov['moneda'], $mov['monto'], $mov['saldo'], $ahora
            ]);
        }

        $base_de_datos->commit();
        echo "<div class='alert alert-success py-1 border-0 shadow-sm mb-2'>
                <i class='bi bi-check-circle'></i> Cliente <b>" . $cliente['cliente'] . "</b>: $count movimientos actualizados.
              </div>";

   } catch (Exception $e) {
        if ($base_de_datos->inTransaction()) $base_de_datos->rollBack();
        echo "<div class='alert alert-danger py-1'>Error en RIF $rif: " . $e->getMessage() . "</div>";
  }
}

if (file_exists($lockFile)) unlink($lockFile);
echo "<div class='alert alert-success'>Sincronización finalizada correctamente.</div>";
echo "</div>";
?>