<?php 
$loc = "./../../";
$title = "Catálogo de Productos web pdf";
$description="Descarga el catálogo de Productos de la marca  WEB y explora nuestra línea de filtros para vehículos y maquinaria pesada. Encuentra la mejor protección y rendimiento en nuestros filtros de aire, aceite y combustible.";
include("./../../web/header.php");
include_once("./../../config/conexion.php");
?>
<div class="container p-4 p-md-5 mb-2 mt-5">
    <h1 class="titulo_bold rojoweb text-uppercase">Descargas</h1>
    <p class="mt-3"><em>Catálogo de productos WEB en formato pdf.</em></p>
    
    <div class="d-flex justify-content-center mt-4">
        <iframe 
            src="./../../jetfilter/gestor/PDF/Web_Catalogo.pdf#view=fitH&pagemode=thumbs&page=1&?t=<?php echo $rann?>" 
            style="width: 100%; height: 80vh; border: none;"
            allowfullscreen>
        </iframe>
    </div>
</div>
<?php
    include("../../web/footer.php");
?>