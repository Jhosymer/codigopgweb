<?php 
$loc = "../";
$title = "Distribuidores Autorizados a Nivel Nacional";
$description="Listado completo e información de contacto de los distribuidores de productos WEB a nivel nacional";
include("../web/header.php");?>

<div class="container p-4 p-md-5 mb-2 mt-5" >
  
<h1 class="titulo_bold rojoweb text-uppercase">
                Distribuidores Autorizados a Nivel Nacional
</h1>

    <div class="row ">
        <!-- Primera columna -->
        <div class="col-12 col-md-12 col-lg-5">
            
            <?php include("./mapa_vnz.php"); ?>

           
        </div>

        <!-- Segunda columna -->
        <div class="col-12 col-md-12 col-lg-7">
            <div class="row mt-5" id="grid_distribuidor"></div>
        </div>

        
    </div>

    <div id="paginacion" class="links linka "></div>



   
    
</div>
<?php
  include("../web/footer.php");
        
?>