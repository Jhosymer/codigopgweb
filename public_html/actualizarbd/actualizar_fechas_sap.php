<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
date_default_timezone_set('America/Caracas');

// Conexión a la base de datos local (MySQL/MariaDB)
require_once "./../config/conexion.php"; 

// Ruta al archivo JSON en la carpeta FTP
$rutaJson = "./../ftp/fecha_pedido.json";

echo "<div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9;'>";
echo "<h2 style='color: #2c3e50;'>Sincronización: SAP -> Base de Datos Web</h2>";
echo "<p style='color: #7f8c8d;'>Procesando fechas de pedidos desde SAP...</p><hr>";

if (!file_exists($rutaJson)) {
    die("<div style='color: red; font-weight: bold;'>Error: El archivo 'fecha_pedido.json' no existe en la carpeta FTP.</div>");
}

$contenido = file_get_contents($rutaJson);
$pedidosLista = json_decode($contenido, true);

if (!$pedidosLista || !is_array($pedidosLista)) {
    die("<div style='color: orange;'>Aviso: El archivo JSON está vacío o tiene un formato inválido.</div>");
}

// Preparamos la consulta SQL
// Actualizamos la tabla 'pedidos', columna 'fecha_sap', basándonos en 'na_pedido'
$sqlUpdate = "UPDATE pedidos SET fecha_sap = :fecha_sap WHERE na_pedido = :num_pedido";
$stmtUpdate = $base_de_datos->prepare($sqlUpdate);

$contadorActualizados = 0;
$totalRegistros = count($pedidosLista);

foreach ($pedidosLista as $item) {
    // Validamos que el JSON contenga las llaves exactas que me pasaste
    if (isset($item['Num_pedido']) && isset($item['fecha_pedido'])) {
        try {
            $stmtUpdate->execute([
                ':fecha_sap'  => $item['fecha_pedido'],
                ':num_pedido' => $item['Num_pedido']
            ]);

            // Si se afectó alguna fila, lo contamos
            if ($stmtUpdate->rowCount() > 0) {
                echo "<div style='color: #27ae60; margin-bottom: 5px;'> ✓ Pedido #<b>{$item['Num_pedido']}</b>: Fecha actualizada a <b>{$item['fecha_pedido']}</b>.</div>";
                $contadorActualizados++;
            } else {
                echo "<div style='color: #95a5a6; margin-bottom: 5px;'> ℹ Pedido #{$item['Num_pedido']}: Sin cambios (ya actualizado o no existe en la web).</div>";
            }

        } catch (PDOException $e) {
            echo "<div style='color: #c0392b; margin-bottom: 5px;'> ✗ Error en Pedido {$item['Num_pedido']}: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div style='color: #d35400; margin-bottom: 5px;'> ! Registro con formato incorrecto detectado en el JSON.</div>";
    }
}

echo "<hr>";
echo "<div style='padding: 10px; background-color: #ecf0f1; border-radius: 5px;'>";
echo "<strong>Resumen del proceso:</strong><br>";
echo "Registros en JSON: <b>$totalRegistros</b><br>";
echo "Base de datos modificada: <b style='color: #27ae60;'>$contadorActualizados</b> filas.";
echo "</div>";
echo "</div>";
?>