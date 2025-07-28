<link rel="stylesheet" href="./../css/estilo_body_apli.css">
<script src='./../js/busqueda_filtros/cambio_aplicacion.js'></script>
<script src='./../js/busqueda_filtros/cambio_marca.js'></script>
<script src='./../js/busqueda_filtros/aplicacion2.js'></script>
<script src='./../js/busqueda_filtros/aplicacion3.js'></script>
<script src='./../js/busqueda_filtros/getData.js'></script>
<script src='./../js/busqueda_filtros/getRegistro.js'></script>
<script src='./../js/busqueda_filtros/volver_registros.js'></script> 

<?php
   include_once("./../web/arriba_carpeta.php");
?>

<title>Por Aplicaciones</title>

<div class="aplicacion_producto">
   <?php include_once("./por_aplicacion.php"); ?>
</div>

<?php   
   include_once("./../web/abajo_carpeta.php");    
   if( isset( $_GET['aplic'] ) && ( $_GET['aplic'] != "") ){
      $aplic = htmlspecialchars( $_GET['aplic'] );
   }
   if( isset( $_GET['marca'] ) && ( $_GET['marca'] != "") ){
      $marca = htmlspecialchars( $_GET['marca'] );
   }
   if( isset( $_GET['vehic'] ) && ( $_GET['vehic'] != "") ){
      $vehiculo = htmlspecialchars( $_GET['vehic'] );
   }
   if( isset( $_GET['codigo'] ) && ( $_GET['codigo'] != "" ) ){
      $codigo = htmlspecialchars( $_GET['codigo'] );
   }
   if( isset( $_GET['codigoVehiculo'] ) && ( $_GET['codigoVehiculo'] != "" ) ){
      $codigoVehiculo = htmlspecialchars( $_GET['codigoVehiculo'] );
   }

   if( isset( $_GET['aplic'] ) && $_GET['aplic'] != null && isset( $_GET['marca'] ) && $_GET['marca'] != null && isset( $_GET['vehic'] ) && ( $_GET['vehic'] != null ) ){
      ?>
         <script>
            aplic = <?php echo $aplic ?>;
            marca = <?php echo $marca ?>;
            vehiculo = <?php echo $vehiculo ?>;
            document.getElementById('lista1').value = aplic;
            $('#lista1').css("display","block");
            $('#lista2').css("display","block");
            $('#label_lista2').css("display","block");

            getAplicacion3(aplic, marca, vehiculo);
         </script>
      <?php
   }
   else if( isset( $_GET['aplic'] ) && $_GET['aplic'] != null && isset( $_GET['marca'] ) && $_GET['marca'] != null ){
      ?>
         <script>
            aplic = <?php echo $aplic ?>;
            marca = <?php echo $marca ?>;
            document.getElementById('lista1').value = aplic;
            $('#lista1').css("display","block");

            getAplicacion2(aplic, marca);
         </script>
      <?php
   }
   else if( isset($_GET['aplic']) && ( $_GET['aplic'] != null )){
      ?>
         <script>
            document.getElementById('lista1').value = <?php echo $_GET['aplic']; ?>;
            var valorCambiado = "id="+document.getElementById('lista1').value;
            $.ajax({
               data: valorCambiado,
               url: './../ajax_busquedas/ajax_aplicacion.php',
               type: 'POST',
               success: function(response){
                  $('#lista2').css("display", "block");
                  $('#label_lista2').css("display", "block");
                  document.getElementById('lista2').innerHTML = response;
               },
               error: function(){
                  alert("Error");
               }
            });
         </script>
      <?php
   }
   else if( isset( $_GET['codigo'] ) && ( $_GET['codigo'] != null ) && isset( $_GET['codigoVehiculo'] ) && ( $_GET['codigoVehiculo'] != null )){
      ?>
         <script>
            $('#detalle').css("display", "none");
            getFiltro('<?php echo $codigo; ?>', '<?php echo $codigoVehiculo; ?>');
         </script>
      <?php
   }
   else if( isset( $_GET['codigo'] ) && ( $_GET['codigo'] != null )){
      ?>
         <script>
            $('#detalle').css("display", "none");
            getFiltro('<?php echo $codigo; ?>');
         </script>
      <?php
   }

  