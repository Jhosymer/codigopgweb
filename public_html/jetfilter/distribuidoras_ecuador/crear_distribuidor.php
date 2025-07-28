<?php 
    try {
        include_once('./../conexion/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: nuevo.php?errorBase=true");
    }

    if(isset($_POST['distribuidor'])){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $email = $_POST['email'];
        $estado = $_POST['estado'];
        $ciudad = $_POST['ciudad'];
        $telefono = $_POST['telefono'];

        $base_de_datos->query("INSERT INTO distribuidoras (nombre, email, direccion, pais, estado, ciudad, telefono) VALUES ('$nombre', '$email', '$direccion', 'Ecuador', '$estado', '$ciudad', '$telefono')");
        header("location: espec_distribuidor.php?nuevo=true");
    }