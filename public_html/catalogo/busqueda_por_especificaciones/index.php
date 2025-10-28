<?php 
$loc = "./../../";
$title = "Catálogo de Productos web Busqueda Por especificaciones";
$description="Encuentra el producto perfecto para tu especificaciones o industria. Explora nuestro catálogo web por marca y especificaciones para obtener especificaciones, datos técnicos.";
include("./../../web/header.php");
include_once("./../../config/conexion.php");
?>
 <div class="container p-4 p-md-5 mb-2 mt-5" >

<?php
    include("./por_especificacion.php"); 

?>
</div>
<?php
   include("../../web/footer.php");
   ?>
   