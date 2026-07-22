<?php

// Bloqueo de seguridad para que solo el Cron lo use
if (php_sapi_name() !== 'cli') {
    die("Acceso denegado.");
}

$directorio = 'cache';
// Directorio a limpiar
$directorio = 'cache';

// Verificamos si la ruta existe
if (is_dir($directorio)) {
    $archivos = glob($directorio . '/*.json');
    $ahora = time();
    $segundos_dia = 86400; // 24 horas 

    $eliminados = 0;
    foreach ($archivos as $archivo) {
        if (($ahora - filemtime($archivo)) > $segundos_dia) {
            unlink($archivo);
            $eliminados++;
        }
    }
    echo "Limpieza completada. Se eliminaron " . $eliminados . " archivos obsoletos.";
} else {
    echo "Error: No se encontró el directorio de caché.";
}
?>