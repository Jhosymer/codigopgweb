<?php 
$loc = "../../";
$title = "LOS REFRIGERANTES WEB";
$description="Los Refrigerantes WEB protección total para el motor de tu vehículo. Previene el sobrecalentamiento, corrosión y garantiza la temperatura ideal para un rendimiento óptimo.";
include("./../../web/header.php");
 include_once("./../../config/conexion.php");

?>

<div class="container-fluid p-0">
  <img src="<?php echo $loc?>img/lineas/b_fluidos.png" class="header_img" alt="Banner de la página">
</div>



<div class="container p-4 p-md-5 mb-2 mt-5">
 
 
        
            <h1 class="titulo_bold rojoweb">LOS REFRIGERANTES WEB</h1>

           
      <p class="parrafo mt-5">Resguardan todo el sistema de enfriamiento del vehículo, asegurando una excelente liberación del calor generado por el motor, está formulado para evitar la cavitación, ebullición prematura, congelamiento, la formación de espuma y por supuesto el sobrecalentamiento. Además, con el refrigerante WEB, obtienes el 100% de protección anticorrosiva sobre partes metálicas como el acero, aluminio, cobre, estaño y otros, evitando incrustaciones u oxidación, adicionalmente sus componentes cuidan los sellos y mangueras evitando roturas en partes metálicas y poliméricas. Un motor es como nuestro cuerpo si nos baja la temperatura está mal y si nos sube también está mal, la temperatura de operación de un motor, así como la temperatura nuestra es de 37º C, la del motor debe ser 85º C ni más ni menos, eso garantiza que todo en el motor funcione como deber ser. Los FILTROS WEB para refrigerante usados en los motores dotados con ellos, pueden y deben usarse con el REFRIGERANTE WEB, así es posible extender aún más la vida del REFRIGERANTE en esos casos.</p>

      <h2 class="rojoweb mt-5 mb-5">¡Ni Frío, Ni Caliente ... la temperatura que tu vehículo necesita!</h2>
   
           <p class="d-inline-flex gap-1 mt-3">
            <button class="btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              Categorías
            </button>
          </p>

          <div class="collapse mb-5 mt-3 " id="collapseExample">
            <div class="card card-body">
        

             <?php include("fluidos.php"); ?>
          
            </div>
          </div>

          <div class="mt-5 text-center">
            <img src="<?php echo $loc?>img/anatomia/fluidos.png?t=<?php echo $rann?>" class="img-fluid w-75" alt="" oncontextmenu="return false" onkeydown="return false">
          </div>


</div>







    
<?php
  include("../../web/footer.php");
        
?>