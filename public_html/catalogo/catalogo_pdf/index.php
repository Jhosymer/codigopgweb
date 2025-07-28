<?php
session_start();
include("./../../web/arriba_carpeta_catalogo.php");

$horaActual = date("h:i:s");
$catalogo = isset($_SESSION['catalogo_pedido']) ? $_SESSION['catalogo_pedido'] : null;

if ($catalogo) {  
    switch ($catalogo) {
        case "ESPECIFICACIONES":
            echo '<meta name="robots" content="noindex">';
            echo '<title>Catálogo de especificaciones</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE ESPECIFICACIONES</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/especificaciones.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "EQUIVALENCIAS":
            echo '<title>Catálogo de equivalencias</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE EQUIVALENCIAS</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/equivalencias.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "fuerade":
            echo '<title>Catálogo de vehículos fuera de carretera</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE VEHÍCULOS FUERA DE CARRETERA</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/fuera_de_carretera.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "AGRICOLA":
            echo '<title>Catálogo de vehículos agrícolas</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE VEHÍCULOS AGRÍCOLAS</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/vehiculos_agricolas.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "COMERCIAL":
            echo '<title>Catálogo de vehículos comerciales</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE VEHÍCULOS COMERCIALES</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/vehiculos_comerciales.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "PASAJERO":
            echo '<title>Catálogo de vehículos de pasajeros</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO DE VEHÍCULOS DE PASAJEROS</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/vehiculos_pasajeros.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        case "COMPLETO":
            echo '<title>Catálogo completo</title>';
            echo '<section class="container_text about_infor_descarga">';
            echo '<h3 class="title_documento">CATÁLOGO COMPLETO</h3>'; 
            echo '<embed src="./../../jetfilter/PDF/catalogo_completo.pdf?t=' . $horaActual . '" type="application/pdf">';
            break;

        default:
            echo '<script type="text/javascript">window.location.href = "./../descargas/";</script>';
            exit();
    }

    echo '<br>';
    echo '<a href="./../descargas/"><img src="./../../img/tipo/bt_volver.png" alt="" class="bt_busq"></a>';
    echo '</section>';
} else {
    echo '<script type="text/javascript">window.location.href = "./../descargas/";</script>';
}

include "./../../web/abajo_carpeta_catalogo.php";
?>