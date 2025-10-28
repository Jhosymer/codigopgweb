<?php 
$loc = "../";
$title = "Catalogo Online";
$description="Catálogo Online WEB | Herramienta practica para agilizar su búsqueda dentro de nuestro amplio catálogo de productos";
include("../web/header.php");?>


<div class="no-grid-layout container p-4 p-md-5 mb-2 mt-5" >


<h1 class="titulo_bold rojoweb text-uppercase"> Catálogo Online de Productos Marca WEB </h1>

<p class="parrafo_md mt-3">Busqueda de productos por aplicaciones, código y especificaciones tecnicas. </p>
<div class="fond_nuevo_p">
<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4 mt-5 mb-5">
    <div class="col">
      <div class="card card-custom h-100 shadow-sm border-0">
       <a href="./busqueda_por_aplicacion/" class="btn btn-custom btn-light-custom d-flex flex-column align-items-center justify-content-center h-100 py-4 text-decoration-none">
          <i class="bx bxs-car icon-custom mb-3"></i>
          <h5 class="card-title-custom fw-bold Roboto-Bold">Por Aplicación</h5>
          <small class="text-muted text-center">Búsqueda de Productos WEB por Aplicaciones</small>
        </a>
      </div>
    </div>

    <div class="col">
      <div class="card card-custom h-100 shadow-sm border-0">
        <a href="./busqueda_por_codigo/" class="btn btn-custom btn-light-custom d-flex flex-column align-items-center justify-content-center h-100 py-4 text-decoration-none">
          <i class="bx bxs-barcode icon-custom mb-3"></i>
          <h5 class="card-title-custom fw-bold  Roboto-Bold">Por Código</h5>
          <small class="text-muted text-center">Búsqueda de Productos WEB por Código</small>
        </a>
      </div>
    </div>

    <div class="col">
      <div class="card card-custom h-100 shadow-sm border-0">
        <a href="./busqueda_por_especificaciones/" class="btn btn-custom btn-light-custom d-flex flex-column align-items-center justify-content-center h-100 py-4 text-decoration-none">
          <i class="bx bxs-cog icon-custom mb-3"></i>
          <h5 class="card-title-custom fw-bold Roboto-Bold">Por Especificaciones</h5>
          <small class="text-muted text-center">Búsqueda de Productos WEB por Especificaciones Técnicas</small>
        </a>
      </div>
    </div>

    <div class="col">
      <div class="card card-custom h-100 shadow-sm border-0">
        <a href="./descargas/" class="btn btn-custom btn-light-custom d-flex flex-column align-items-center justify-content-center h-100 py-4 text-decoration-none">
          <i class="bx bxs-download icon-custom mb-3"></i>
          <h5 class="card-title-custom fw-bold Roboto-Bold">Descargas</h5>
          <small class="text-muted text-center">Descarga los Catálogos en formato PDF</small>
        </a>
      </div>
    </div>
</div>
  
  <?php
  
  include("slider_productos.php");
        
?>

</div>
<?php
  include("../web/footer.php");
        
?>

        