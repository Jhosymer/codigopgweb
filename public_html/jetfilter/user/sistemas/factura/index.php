

<?php 
$id = "";
$id_users = $_SESSION['id'];


if (isset($_GET['id_ver'])) {
    $id=$_GET['id_ver'];
 echo '<h1 class="titulo text-center"> Factura </h1>'; 
    include('ver_factura.php');

} else  {
    
echo '<h1 class="titulo text-center"> Facturas </h1>'; 
include('tabla_facturas.php');
}
?>
