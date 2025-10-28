<?php 
$loc = "./../../";
$title = "Catálogo de Productos web Busqueda Por Codigo";
$description="Busca productos por su código de referencia. Encuentra detalles técnicos, especificaciones de nuestros productos de forma rápida y sencilla. ¡Encuentra el tuyo ahora!";
include("./../../web/header.php");
include_once("./../../config/conexion.php");
?>
 <div class="container p-4 p-md-5 mb-2 mt-5" >

<?php
    include("./buscar_producto.php"); 

?>
</div>
<script src="./../../js/formulario_requerimiento.js"></script>
<?php
  include("../../web/footer.php");
        
?>