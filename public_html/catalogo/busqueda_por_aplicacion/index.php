<?php 
$loc = "./../../";
$title = "Catálogo de Productos web Busqueda Por Aplicaciones";
$description="Encuentra el producto perfecto para tu aplicación o industria. Explora nuestro catálogo web por marca y aplicación para obtener especificaciones, datos técnicos.";
include("./../../web/header.php");
include_once("./../../config/conexion.php");
?>
 <div class="container p-4 p-md-5 mb-2 mt-5" >

<?php
    include("./por_aplicacion.php"); 

?>
</div>
<?php
  
        
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
               url: './../../ajax_busquedas/ajax_aplicacion.php',
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

   

   include("../../web/footer.php");
   ?>
   