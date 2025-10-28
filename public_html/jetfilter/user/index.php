<?php
// Asegúrate de que esta línea es la primera en el archivo, sin nada antes.
session_start();

// Si no hay sesión de 'email', no hay usuario logueado
if( !isset($_SESSION['email']) ){
    header("location: ./../index.html");
    exit();
}

// Cierre de sesión por inactividad
if(isset($_SESSION['tiempo'])) {
    $inactivo = 3600;
    $vida_session = time() - $_SESSION['tiempo'];
    if($vida_session > $inactivo) {
        session_unset();
        session_destroy();
        header("Location: ./../login/");
        exit();
    }
}
$_SESSION['tiempo'] = time();

?>
<!DOCTYPE html>
<html lang="es">

<?php
 //session_start();
 include("./../config/conex.php");
require_once("./index/head.php");
include ("./sistemas/combos/alert_actualiza.php");

$pag = isset($_GET['pag']) ? $_GET['pag'] : '';
$id_users=$_SESSION['id'];
?>

<body>
	   <div class="wrapper_md">

	     <?php
            require_once("./index/menu.php");
            ?>


		<div class="main">

		<?php
            require_once("./index/nav.php");
         ?>



			<main class="content">

         <?php
	



alerta_actualizado(); 
if ($pag !== 'pedido' && (isset($_SESSION['id_pedido']) || isset($_SESSION['idGenerado']) || isset($_GET['id']))) {
	$pag ='pedido';
   alerta_pedido_abierto();
   // echo '<script>alert("¡La condición de la alerta se cumplió!");</script>'; 

}
           if (!isset($_GET['pag']) or $pag=='' ){
			
			echo '<h1 class=" titulo text-center">Bienvenidos '. $_SESSION['name'].'</h1>';
		
			require_once("./sistemas/bienvenido.php");
		   } else{

			if($pag =='pedido')require_once("sistemas/pedidos/index.php");
			if($pag =='soporte')require_once("sistemas/soporte/index.php");
		
						
			

		   }


           ?>
			
			</main> 

			<?php
            require_once("./index/footer.php");
            ?>


		</div>
	</div>

	<script src="./../../js/js_vende/app.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

	<?php
    require_once("./index/script.php");
     ?>
</body>

</html>