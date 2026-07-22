<?php 
$id = "";
$id_users = $_SESSION['id'];

if (isset($_GET['id_ver'])) {
    $id = $_GET['id_ver'];
    echo '<h1 class="titulo text-center">Comprobante de Pago </h1>'; 
    include('ver_pago.php');
} else {
    echo '<h1 class="titulo text-center"> Historial de Pagos </h1>'; 
    include('tabla_pagos.php');
}
?>