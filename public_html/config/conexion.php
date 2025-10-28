<?php
$contraseña = "123abc*1";
$usuario = "webfiltr_admin";
$nombreBaseDeDatos = "webfiltr_webfiltros";
$rutaServidor = "localhost";

$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);