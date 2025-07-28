<?php
$contraseña = "";
$usuario = "root";
$nombreBaseDeDatos = "web3";
$rutaServidor = "localhost";

$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>


