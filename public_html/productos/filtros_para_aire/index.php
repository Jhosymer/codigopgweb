<?php 
$loc = "../../";
$title = "LOS FILTROS PARA AIRE";
$description="Garantiza la mejor combustión en tu motor con los filtros de aire WEB. Protegen de la suciedad, mejoran el rendimiento y optimizan el consumo de combustible.";
include("./../../web/header.php");
include_once("./../../config/conexion.php");
?>

  <div class="container-fluid p-0">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="<?php echo $loc?>img/heder/b_aire.jpg" class="d-block w-100" alt="Filtros para Aceite">
        </div>
        
        <div class="carousel-item">
          <img src="<?php echo $loc?>img/heder/b_aire3.jpg" class="d-block w-100" alt="Filtros para Aire">
        </div>
      </div>
    </div>
  </div>

<div class="container p-4 p-md-5 mb-2 mt-5">
  <h1 class="titulo_bold rojoweb">LOS FILTROS PARA AIRE</h1>
 

<div class="container mt-5">
  <div class="row">
    <div class="col-12 col-md-7">
      <p class="parrafo"> Aproximadamente por cada 60 litros de combustible que consume su motor aspira 480.000 litros de aire. Los Filtros WEB capturan el 99,9% de partículas contaminantes de ese aire evitando daños graves a su motor.</p>

      <p class="parrafo mt-5">Una concentración típica de polvo atmosférico en un área urbana es de 87 microgramos por metro cúbico o sea 0.087 g por cada 1000 litros de aire, quiere decir que con cada tanque de combustible que usted consume, entrarían al motor 0.087*480 = 41 gramos de polvo si no hubiera filtro de aire.</p>
      
      
    </div>
    <div class="col-12 col-md-5">
      <img src="<?php echo $loc?>img/lineas/aire.png?t=<?php echo $rann?>" class="img-fluid py-4" alt="Filtros de aire" oncontextmenu="return false" onkeydown="return false">
    </div>
  </div>
</div>
 <p class="parrafo">Si gastamos aproximadamente un tanque de combustible cada 15 días, en un año son 24 tanques de gasolina por lo tanto entrarían al motor 41*24 = 984 gramos de polvo, o sea, casi un Kg de polvo, esto lo evita con el filtro de aire WEB.</p>

   <p class="parrafo mt-5">Por eso hay que cambiar ese filtro mínimo una vez por año en un vehículo particular. Si usted es un trabajador del volante puede ayudarse con este cálculo para estimar su caso, ya que, si cada tanque de combustible de 60 litros en gasolina nos permite aproximadamente 500 Km, un vehículo particular puede recorrer unos 12.000 Km al año, para una persona que modestamente usa el vehículo. En los vehículos diésel la relación es mayor aún.</p>
  
  <p class="d-inline-flex gap-1 mt-3">
    <button class="btn-icon" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
      Categorías
    </button>
  </p>

  <div class="collapse mb-5 mt-3 " id="collapseExample">
    <div class="card card-body">
      <?php include("aire.php"); ?>
    </div>
  </div>

  <div class="mt-5 text-center">
    <img src="<?php echo $loc?>img/anatomia/AIRE.png?t=<?php echo $rann?>" class="img-fluid w-75" alt="" oncontextmenu="return false" onkeydown="return false">
  </div>
</div>

<?php
include("../../web/footer.php");
?>