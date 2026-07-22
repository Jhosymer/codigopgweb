
<?php
$loc = "./../";
$locj = "./../";

$title = "Jet Filter | Fabricantes de la marca WEB";
$description="Jet-Filter, C.A., Conglomerado industrial de corpoindustrias Galpones E-5 y E-19, Tinaquillo 2209, Cojedes, Tinaquillo, Cojedes";
include("./../web/head_jf.php");
?>
<div class="container-fluid p-0">
  <img src="<?php echo $loc?>img/heder/jetfilter.png?t=<?php echo $rann?>" class="header_img" alt="Banner de la página">
</div>

<div class="container p-4 p-md-5 mb-2 mt-5">
    <h1 class="titulo_bold text-center">JET FILTER C.A, FABRICANTES DE LA MARCA WEB.</h1>
    <h2 class="subtito_md text-center rojoweb"> Protegemos tu vehículo y maquinaria desde 1965</h2>

            <div class="d-flex justify-content-end align-items-center position-relative mt-5 contenedor-personalizado mb-5">
                <a href="./../index.php" class="btn-icon position-absolute top-0 end-0 mt-5 me-5">
                    <b>Ir a Webfiltros</b>
                </a>
                <img src="<?php echo $loc?>img/lineas/Filtros_grupo.png?t=<?php echo $rann?>" class="img-fluid" id="misionvision" alt="Banner de la página" style="max-width: 80%; position: absolute; bottom: 0; right: 0;">
            </div>


</div>
<?php
include("./index/misionvision.php");
include("./index/rese_histotorica.php");
    ?>
<div class="container ">
        <div class="container my-5  mt-10 mb-5">
            <div class="row justify-content-center mt-5">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="ratio ratio-16x9">
                        <video src="<?php echo $loc?>img/heder/JetFilter.mp4?t=<?php echo $rann?>" controls="" class="video_home" poster="<?php echo $loc?>img/lineas/composicion_filtros.png?t=<?php echo $rann?>" oncontextmenu="return false" onkeydown="return false"></video>
                    </div>
                </div>
            </div>
        </div>
  </div>
<div class="div_img_fondo_iz mb-5" >
    <div class="container ">
    <?php
  
    include("./index/Morfologia.php");

      include("./index/linea_tiempo.php");
      



      

    ?>
 </div>
 </div>
<?php
include("./../web/footer_jf.php");
?>





