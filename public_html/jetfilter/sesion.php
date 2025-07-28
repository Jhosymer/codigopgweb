<?php
    include_once('./conexion/conexion.php'); 
    include_once('./arriba.php');
?>

 <title>Inicio</title>

 <section class="hero__contain contain">
    <h1 class="title">Bienvenido <?php echo $_SESSION['name']; ?></h1>
    <a href="./../index.php" target="_blank" class="ctan">ir a Webfiltros</a>
</section>

<?php 
    include_once('abajo.html')
?>