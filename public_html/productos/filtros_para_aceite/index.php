<?php 
$loc = "../../";
$title = "LOS FILTROS PARA ACEITE";
$description="Protege tu motor con los filtros de aceite WEB. Eliminan partículas dañinas, prolongan la vida del aceite y aseguran un rendimiento óptimo del motor.";
include("./../../web/header.php");
 include_once("./../../config/conexion.php");

?>


    
<div class="container-fluid p-0">
  <img src="<?php echo $loc?>img/lineas/b_aceite.png" class="header_img" alt="Banner de la página">
</div>
   


   

<div class="container p-4 p-md-5 mb-2 mt-5">
 
 
        
            <h1 class="titulo_bold rojoweb">LOS FILTROS PARA ACEITE</h1>


     <p class="parrafo mt-5">Una mala combustión por problemas en la admisión de aire o de combustible van a causar que el aceite se degrade y se contamine mucho más rápido, esto es muy grave porque impide al aceite hacer su trabajo, por eso la importancia de los filtros de combustible y de aire y la entonación de los motores. Los FILTROS PARA ACEITE WEB están diseñados para controlar la cantidad de partículas dañinas que llegan al aceite por una buena combustión y también aquellas partículas propias del desgaste del motor, como son limadura de aceros, bronce, aluminio o cualquier otro metal o plástico que pueda desprenderse con el uso.</p>

            <img src="<?php echo $loc?>img/lineas/aceite.png?t=<?php echo $rann?>" class="img_fil_aceite" alt="Filtros de aceite" oncontextmenu="return false" onkeydown="return false">  

         
      
             
     <p class="parrafo mt-5">Los motores a diésel, aun cuando hoy día son dotados de turbos e inyectores electrónicos y computadoras, siempre deben ser lubricados con aceites adecuados a su tecnología y verificar el contenido de azufre del diésel que se use, para garantizar que el aceite dure las horas correspondientes y el aceite sea capaz de soportar los ácidos que se forman, igualmente la mejor combustión de un motor diésel forma hollín, por eso rápidamente se torna negro a diferencia del de gasolina, ese hollín también es retenido por el FILTRO PARA ACEITE WEB, para que siempre la concentración sea la adecuada.</p>


  
            
            <p class="parrafo mt-5 "> Por todo lo explicado de cada uno de los tipos de Filtros que usa un motor, podemos hacer la comparación con nuestro cuerpo, la sangre es el aceite del motor, el aire que necesita el organismo también se necesita limpio y pasa por el filtro de aire que son las fosas nasales y los alveolos pulmonares, la comida que es el combustible se digiere en la cámara de combustión que es nuestro estómago y se filtra en los intestinos que son el filtro de combustible de nuestro cuerpo, por último están nuestros riñones que son como el filtro de aceite. Ya sabemos lo que sucede cuando lo que respiramos es aire contaminado o comemos alimentos inadecuados, los riñones no son capaces de dar a la sangre la energía de una buena combustión y los pulmones no son capaces de dar un aire libre de impurezas por lo tanto finalmente los riñones fallan y el cuerpo muere, igualmente pasa en nuestro motor si no usamos bien los filtros, el diésel y el aceite. </p>
          
       

           <p class="d-inline-flex gap-1 mt-3">
            <button class="btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
              Categorías
            </button>
          </p>

          <div class="collapse mb-5 mt-3 " id="collapseExample">
            <div class="card card-body">
        

             <?php include("aceite.php"); ?>
          
            </div>
          </div>

          <div class="mt-5 text-center">
            <img src="<?php echo $loc?>img/anatomia/ACEITE.png?t=<?php echo $rann?>" class="img-fluid w-75" alt="" oncontextmenu="return false" onkeydown="return false">
          </div>


</div>







    
<?php
  include("../../web/footer.php");
        
?>