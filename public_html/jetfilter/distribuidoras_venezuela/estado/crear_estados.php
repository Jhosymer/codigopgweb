<?php 
    try {
        session_start();
        include_once('./../../conexion/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: ./nuevo.php?errorBase=true");
    }

    if( isset( $_POST['enviar_estado'] ) ){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id_distribuidor = $_POST['distribuidora_id'];
        $estado = $_POST['estado'];
        $ciudad = $_POST['ciudad'];
        $direccion = $_POST['direccion'];

        if( $estado != "Nacional" ){
            $argumentos = [$id_distribuidor, $estado, $ciudad, $direccion];
            $seleccionado = $base_de_datos->prepare("INSERT INTO distribuidora_estado (id_distribuidora, estado, ciudad, direccion, principal) VALUES (?, ?, ?, ?, 1)");
            $seleccionado->execute($argumentos);
        }
        else {
            $argumentos = [$id_distribuidor, $estado, $ciudad, $direccion];
            $seleccionado = $base_de_datos->prepare("INSERT INTO distribuidora_estado (id_distribuidora, estado, ciudad, direccion, principal) VALUES (?, ?, ?, ?, 2)");
            $seleccionado->execute($argumentos);
        }
        
        $_SESSION['nuevo'] = true;
        header("location: ./estados_distribuidores.php");
    }