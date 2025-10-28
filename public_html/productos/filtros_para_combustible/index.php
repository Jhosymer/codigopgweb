<?php 
$loc = "../../";
$title = "LOS FILTROS PARA COMBUSTIBLE:";
$description="Protege tu motor con los filtros de combustible WEB. Especialistas en diésel de inyección, nuestros filtros eliminan impurezas y agua para prevenir daños en inyectores y prolongar la vida útil de tu motor";
include("./../../web/header.php");
 include_once("./../../config/conexion.php");

?>

<div class="container-fluid p-0">
  <img src="<?php echo $loc?>img/lineas/b_combustible.png" class="header_img" alt="Banner de la página">
</div>



<div class="container p-4 p-md-5 mb-2 mt-5">
 
 
        
            <h1 class="titulo_bold rojoweb">LOS FILTROS PARA COMBUSTIBLE</h1>

            <div class="row">
    <div class="col-12 col-md-8 ">
      <p class="parrafo mt-5"> Son indispensables para los motores tanto diésel como gasolina, cuyos elementos de inyección son muy sensibles y más aún los de diésel de inyección electrónica. WEB es especialista en filtros para combustible diésel. Los Filtros WEB protegen su motor porque separan las impurezas y el agua de los combustibles, lo cual impide la contaminación y el daño de inyectores, extendiendo los tiempos de manteniendo de los mismos.</p>
    </div>
    <div class="col-12 col-md-4 d-flex justify-content-center align-items-center">
        <img src="<?php echo $loc?>img/lineas/combustible.png?t=<?php echo $rann?>" class="img-fluid" alt="Filtros para combustible" oncontextmenu="return false" onkeydown="return false"> 
    </div>
</div>
      

            <p class="parrafo mt-5">  Los filtros WEB para motores diésel con inyectores electrónicos, poseen un medio de filtración capaz de retener partículas de hasta 2 micrones, el cual es especial para este tipo de motores. Tenga en cuenta que somos lo que comemos, igualmente nuestros motores, por el aire y por el combustible es por donde entran al motor los contaminantes más dañinos, coloca filtros WEB y verás que tu motor te durara y podrás amortizar tu inversión sin lamentar daños innecesarios en tu motor</p>

 

           <p class="d-inline-flex gap-1 mt-3">
            <button class="btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              Categorías
            </button>
          </p>

          <div class="collapse mb-5 mt-3 " id="collapseExample">
            <div class="card card-body">
        

             <?php include("combustible.php"); ?>
          
            </div>
          </div>

          <div class="mt-5 text-center">
            <img src="<?php echo $loc?>img/anatomia/GASOLINA.png?t=<?php echo $rann?>" class="img-fluid w-75" alt="" oncontextmenu="return false" onkeydown="return false">
          </div>


</div>







    
<?php
  include("../../web/footer.php");
        
?>