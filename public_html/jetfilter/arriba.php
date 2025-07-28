<?php 
    session_start() or die('Error iniciando gestor de variables de sesión');  
    if( !isset($_SESSION['email']) ){
      header("location: ./index.php");
    }
    if(isset($_SESSION['tiempo']) ) {

        $inactivo = 3600;

        $vida_session = time() - $_SESSION['tiempo'];

            if($vida_session > $inactivo)
            {
                session_unset();
                session_destroy();              
                header("Location: gestion.php");

                exit();
            }

    }
    $_SESSION['tiempo'] = time();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="./img/icon/jf.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/estilos_perfil.css">
    <link rel="stylesheet" href="./css/estilos_gestion.css">
    <link rel="stylesheet" href="./css/estilo_figura.css">
    <link rel="stylesheet" href="./../css/estilos_tabla.css">

    
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.ez-plus.js"></script>
    <script src="./js/sweetAlerta.js"></script>
</head>
<body>
    
    <nav class="nav container" id ="menu_s">
        <div class="nav__logo">
           <a href="./sesion.php"> <img src="./img/logo/logojf2.png" class="img_log"></a>
        </div>
        <ul class="nav__link nav__link--menu">
          <li class="nav__items ">
              <a href="./sesion.php" class="nav__links">Inicio</a>
          </li>
          <li class="nav__items">
                <a href="especificaciones.php" class="nav__links">Catálogo</a>
            </li>
            <li class="nav__items">
                <a href="./distribuidoras_venezuela/distribuidores_venezuela.php" class="nav__links">Distribuidores</a>
            </li>
            <li class="nav__items">
                <a href="#" class="nav__links">
                    <?php echo $_SESSION['name']; ?>
                </a>
            </li>
            <li class="nav__items">
                <a href="cerrar_sesion.php" class="nav__icons"><img src="./img/svg/log-out.svg"></a>
            </li>
        </ul>
    </nav>