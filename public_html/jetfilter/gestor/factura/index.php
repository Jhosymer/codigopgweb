<?php 
     $loc = "../../../";
     $locj = "./../../";
     $title = "Facturas";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');
    include_once('./../alertas/alerta_actualizado.php');
      alerta_actualizado('Factura actualizada');

    $id = "";
?>
<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>

<h1 class="titulo text-center"> Facturas </h1>

<?php
include('tabla_facturas.php');

?>
  </div>
<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>

<script src="<?php echo $loc; ?>js/js_vende/menutables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/calculoporprecios.js"></script>


<?php   
  
    include("../index/footer.php");

    ?>

    