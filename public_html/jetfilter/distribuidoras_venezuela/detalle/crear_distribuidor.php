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

    if( isset( $_POST['distribuidor'] ) ){
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT COUNT(*) as numero_seis FROM distribuidoras WHERE calificacion = 6";
        $cantidad_seis = $base_de_datos->prepare($sql);
        $cantidad_seis->setFetchMode( PDO::FETCH_ASSOC );
        $cantidad_seis->execute();
        while( $fila = $cantidad_seis->fetch() ) {
            $cantidad []= $fila;
        }  
        $seis = $cantidad[0]['numero_seis'];

        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $email2 = ( $_POST['email2'] != '') ? $_POST['email2'] : null;
        $telefono = $_POST['telefono'];
        $telefono2 = ( $_POST['telefono_2'] != '') ? $_POST['telefono2'] : null;
        $calificacion = ( $seis >= 4 && $_POST['calificacion'] == 6) ? 5 : $_POST['calificacion'];
        $facebook = $_POST['facebook'];
        $twitter = $_POST['twitter'];
        $instagram = $_POST['instagram'];
        $video_instagram = $_POST['video_instagram'];
        $direcmaps = $_POST['direcmaps'];
        
        

        $argumentos = [$nombre, $email, $telefono, $telefono2, $calificacion, $facebook, $twitter, $instagram, $video_instagram, $direcmaps];
        $seleccionado = $base_de_datos->prepare("INSERT INTO distribuidoras (nombre, email, pais, telefono, telefono2, calificacion, facebook, instagram, twitter, video_instagram, direcmaps ) VALUES (?, ?, 'Venezuela', ?, ?, ?, ?, ?, ?,?,?)");
        $seleccionado->execute($argumentos);
        
        if( $seis >= 4 && $_POST['calificacion'] == 6){
            $_SESSION['limite'] = true;
        }
        else {
            $_SESSION['nuevo'] = true;
        }
        header("location: ./espec_distribuidor.php");
    }