<?php

include("./web/arriba.php");
?>


<header>
<title>WEBFiltros</title>
<meta name="description" content="Filtros diseñados para todo tipo de vehículos desde livianos y SUV hasta carga pesada, equipo fuera de carretera y maquinaria agrícola. ¡protege tus equipos con nuestros filtros!">


    <section class="header">
     
<div class="wrapper">
   
     <video src="./img/heder/Camion_WEB.mp4" class="slide1 imagen"  id="slide1" autoplay  muted   oncontextmenu="return false" onkeydown="return false"></video>
     <video src="./img/heder/caja.mp4" class="slide2 imagen"  id="slide2" autoplay  muted oncontextmenu="return false" onkeydown="return false"></video>
    <video src="./img/heder/www_LogoWEB.mp4" class="slide3" id="slide3"  autoplay  muted oncontextmenu="return false" onkeydown="return false"></video>
    </div>
 

  </header>
  <section class="home">
 <div>
 <?php
include("./index/texto_web.html");
?>
</div><div >
<?php
include("./index/nano.html");
include("./index/video_aire.php");?>
</div><div>
<?php
include("./index/texto_aire.html");?>
</div><div>
<?php
include("./index/combustible_video.html");?>
</div><div>
<?php
include("./index/combustible.html");?>
</div><div>
<?php
include("./index/aceite_video.html");?>
</div><div>
<?php
include("./index/aceite.html");?>
</div><div>
<?php
include("./index/fluidos_videos.html");?>
</div><div>
<?php
include("./index/fluidos.html");?>
</div><div>
<?php

include("./index/android.html");
?>
</div>
</section >



<?php
include("./index/alerta_emergente.php");
include("./web/abajo_d.php");
?>