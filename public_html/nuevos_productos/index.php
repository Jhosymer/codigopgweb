<?php
include("./../web/arriba_carpeta.php");
//header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada 
//header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos 
//header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
//header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 
?> 
 <style> .idioma {align-items: center;} </style>
<title>Nuevo Productos de la Marca web</title>

 <section class="lineas_prod">
 <div >
<img src="./../img/lineas/b_productos.jpg" class="header_img" alt="" />

</div >

<div class="fond_nuevo_p">
<div class="container_text margen_carrusel">


<?php
include("slider_productos.php");
?>
  

</div>
</div>

</section >

<?php
 include ("./../web/abajo_carpeta.php");
?>

