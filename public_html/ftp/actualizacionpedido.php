<?php
// Evitar caché del navegador
header('Content-Type: text/plain');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

echo "🧪 Ejecutando archivo: " . __FILE__ . "\n";
echo "🧪 Versión del script: 1.5\n";

// Verificar si OPcache está habilitado y forzar invalidación de este archivo
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status(false);
    if ($status && !empty($status['opcache_enabled'])) {
        echo "✅ OPcache está habilitado\n";
        if (function_exists('opcache_invalidate')) {
            $invalidado = opcache_invalidate(__FILE__, true);
            echo $invalidado ? "🔄 OPcache invalidado para este archivo\n" : "⚠️ No se pudo invalidar OPcache\n";
        }
    } else {
        echo "⚠️ OPcache está disponible pero no está habilitado\n";
    }
} else {
    echo "❌ OPcache no está disponible en este servidor\n";
}

// Conexión a la base de datos
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=webfiltr_webfiltros;charset=utf8",
        "webfiltr_admin",
        "123abc*1"
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos exitosa.\n";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
    exit;
}

// Leer el JSON desde el archivo pedidopro2.json
$jsonFile = 'pedidopro.json';
clearstatcache(true, $jsonFile); // fuerza lectura actualizada

echo "📁 Ruta del archivo JSON: " . realpath($jsonFile) . "\n";

if (!file_exists($jsonFile)) {
    echo "❌ Archivo pedidopro2.json no encontrado\n";
    exit;
}

echo "📅 Última modificación: " . date("Y-m-d H:i:s", filemtime($jsonFile)) . "\n";

$input = file_get_contents($jsonFile);
echo "📄 Contenido del JSON:\n$input\n";

$data = json_decode($input, true);

if (!is_array($data)) {
    echo "❌ Formato JSON inválido\n";
    exit;
}

echo "\n🔍 Datos interpretados:\n";
print_r($data);

$actualizados = [];

foreach ($data as $index => $pedido) {
    echo "\n🔧 Procesando pedido #$index\n";
    var_dump($pedido);

    if (isset($pedido['id']) && isset($pedido['doc_entry']) && isset($pedido['doc_date'])) {
        $fechaSap = substr($pedido['doc_date'], 0, 10);
        echo "🔄 Actualizando ID: {$pedido['id']} con doc_entry: {$pedido['doc_entry']}\n";

        $stmt = $pdo->prepare("UPDATE pedidos SET na_pedido = :doc_entry, fecha_sap = :fecha_sap WHERE id = :id");

        $stmt->execute([
            ':doc_entry' => $pedido['doc_entry'],
            ':fecha_sap' => $fechaSap,
            ':id'        => $pedido['id']
        ]);
       

        $filas = $stmt->rowCount();
        echo "✅ Filas afectadas: $filas\n";

        if ($filas > 0) {
            $actualizados[] = $pedido['id'];
        }
    } else {
        echo "⚠️ Pedido inválido: faltan campos 'id' o 'doc_entry'\n";
    }
}

// Resultado final en formato JSON
$resultado = [
    'mensaje' => 'Actualización completada',
    'actualizados' => $actualizados
];

echo "\n🧾 Resultado final:\n";
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
?>
