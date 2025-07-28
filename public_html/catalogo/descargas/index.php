<?php
include("./../../web/arriba_carpeta_catalogo.php");
$horaActual = date("h:i:s");
//header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada 
//header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos 
//header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
//header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 
?>

<title>descargas Catálogo de Productos web</title>
<section class="container_text about_infor_descarga">

    <br>
    <h1 class="title_descarg">Descargas</h1>
    <span class="infor_descarga">Catálogo de productos WEB en formato pdf. </span>
   <div>
   <embed src="./../../jetfilter/PDF/Web_Catalogo.pdf?t=<?php echo $horaActual?>" type="application/pdf" >
   <br><br>
   </div>

</section>

<!-- <section class="container_text about_infor_descarga">

    <br>
    <h3 class="title_descarg">Descargas</h3>
    <p class="infor_descarga">Descarga de todas las versiones del catálogo de productos en formato pdf. </p>
    <section class="about_descarga_co">
        <div class="about_descarg_f">
            <h3 class="h3descarga">  
                <a href="tipo_descarga.php?CATALOGO=ESPECIFICACIONES" class="h3des">CATÁLOGO DE ESPECIFICACIONES</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">  
                <a href="tipo_descarga.php?CATALOGO=ESPECIFICACIONES">Ver Mas </a>
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes"> 
                <a href="./../../jetfilter/PDF/especificaciones.pdf" download> descargar</a> 
            </p> 
            <br><br>
        </div>

        <div class="about_descarg_f">
            <h3 class="h3descarga">
                <a href="tipo_descarga.php?CATALOGO=EQUIVALENCIAS" class="h3des">CATÁLOGO DE EQUIVALENCIAS</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">
                <a href="tipo_descarga.php?CATALOGO=EQUIVALENCIAS"> Ver Mas</a> 
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes">
                <a href="./../../jetfilter/PDF/equivalencias.pdf"download> descargar </a> 
            </p>  
            <br><br>
        </div>

        <div class="about_descarg_f">
            <h3 class="h3descarga">
                <a href="tipo_descarga.php?CATALOGO=fuerade" class="h3des">CATÁLOGO DE VEHÍCULOS FUERA DE CARRETERA</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">
                <a href="tipo_descarga.php?CATALOGO=fuerade"> Ver Mas </a>
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes"> 
                <a href="./../../jetfilter/PDF/fuera_de_carretera.pdf" download> descargar</a> 
            </p>  
            <br><br>
        </div>

        <div class="about_descarg_f">
            <h3 class="h3descarga">
                <a href="tipo_descarga.php?CATALOGO=AGRICOLA" class="h3des"> CATÁLOGO DE VEHÍCULOS AGRÍCOLAS</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">
                <a href="tipo_descarga.php?CATALOGO=AGRICOLA"> ver mas</a> 
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes"> 
                <a href="./../../jetfilter/PDF/vehiculos_agricolas.pdf" download>descargar</a>  
            </p>  
            <br><br>
        </div>

        <div class="about_descarg_f">
            <h3 class="h3descarga">
                <a href="tipo_descarga.php?CATALOGO=COMERCIAL" class="h3des"> CATÁLOGO DE VEHÍCULOS COMERCIALES</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">  
                <a href="tipo_descarga.php?CATALOGO=COMERCIAL"> Ver Mas </a>
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes">
                <a href="./../../jetfilter/PDF/vehiculos_comerciales.pdf" download>descargar</a> 
            </p>  
            <br><br>
        </div>

        <div class="about_descarg_f">
            <h3 class="h3descarga"> 
                <a href="tipo_descarga.php?CATALOGO=PASAJERO" class="h3des"> CATÁLOGO DE VEHÍCULOS DE PASAJEROS</a>
            </h3> 
            <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">
                <a href="tipo_descarga.php?CATALOGO=PASAJERO"> Ver Mas</a> 
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes"> 
                <a href="./../../jetfilter/PDF/vehiculos_pasajeros.pdf" download> descargar </a>
            </p>  
            <br><br>
        </div>


    <div class="about_descarg_f">
        <h3 class="h3descarga">
            <a href="tipo_descarga.php?CATALOGO=COMPLETO" class="h3des">CATÁLOGO COMPLETO</a>
        </h3> 
        <p class="if_if_des">
                <img src="./../../img/svg/visto.svg" class="imgdes">
                <a href="tipo_descarga.php?CATALOGO=COMPLETO"> Ver Mas </a>
            </p>  
            <p class="if_if_des">
                <img src="./../../img/svg/descarga.svg" class="imgdes"> 
                <a href="./../../jetfilter/PDF/catalogo_completo.pdf" download> descargar</a> 
            </p>  
            <br><br>
        </div>
          
        </a>
        <br><br>
    </div>
</section>

</section>-->


<?php include "./../../web/abajo_carpeta_catalogo.php";
?>