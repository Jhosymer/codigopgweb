<?php
// Capturar las variables de la URL (Query String)
$codigo = isset($_GET['codigo']) ? urlencode($_GET['codigo']) : '';
$clase  = isset($_GET['clase']) ? urlencode($_GET['clase']) : '';
$cod    = isset($_GET['cod']) ? urlencode($_GET['cod']) : '';

// Construir la nueva URL dinámicamente
$nueva_url = "https://webfiltros.com/catalogo/ficha_tecnica/index.php?codigo={$codigo}&clase={$clase}&cod={$cod}";

// Redirección 301
header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $nueva_url);
exit();
?>