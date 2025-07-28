<?php 
session_start();

if (isset($_GET['CATALOGO'])) {
    $_SESSION['catalogo_pedido'] = $_GET['CATALOGO'];
}

header("Location: ./../catalogo_pdf/"); 
exit(); // Asegúrate de usar exit después de redirigir
?>