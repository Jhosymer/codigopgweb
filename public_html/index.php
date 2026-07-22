<?php 
$loc = "./";
$title = "WEBFiltros";
$description="Filtros diseñados para todo tipo de vehículos desde livianos y SUV hasta carga pesada, equipo fuera de carretera y maquinaria agrícola. ¡protege tus equipos con nuestros filtros!";
include("./web/header.php");

include("index/banner_videos.php");?>

        <div class="container p-4 p-md-5 mb-2" >
      
          <?php
          include("./index/texto_web.html");
         
          ?>

        </div>
          
      
        <?php 
            include("./index/nano.html");
            include("./index/video_aire.php");
            include("./index/aire.html");
            include("./index/combustible.html");
            include("./index/aceite.html");
            include("./index/fluidos.html");
            include("./index/app.html");
           // include("./arbol_navidad.php");
           // include("./robot_chat.php");
           // include("./index/alerta_emergente.php");

  include("./web/footer.php");


        
?>

