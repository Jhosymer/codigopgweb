<?php
session_start() or die('Error iniciando gestor de variables de sesión');  
if( !isset($_SESSION['email']) ){
    header("Location: " . $locj . "index.php");
}
if(isset($_SESSION['tiempo']) ) {

    $inactivo = 3600;

    $vida_session = time() - $_SESSION['tiempo'];

        if($vida_session > $inactivo)
        {
            session_unset();
            session_destroy();              
            header("Location: " . $locj . "login/");

            exit();
        }

}
$_SESSION['tiempo'] = time();
$rann = date('H:i:s');

include("permisos_user.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>">

    <link rel="icon" href="<?php echo $loc; ?>img/logo/web.ico">
  

     <link rel="stylesheet" href="<?php echo $loc; ?>css/estilos_menu.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilos.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilo_figura.css?t=<?php echo $rann?>">
    
    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/bootstrap/css/bootstrap.min.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/boxicons/css/boxicons.min.css?t=<?php echo $rann?>">
     <?php 

    $current_page = $_SERVER['REQUEST_URI'];

if ($current_page === '/jetfilter/gestor/especificaciones.php') {
    echo '<script type="text/javascript" src="./js/jquery.ez-plus.js"></script>
          <script src="./js/sweetAlerta.js"></script>';
} 
else if (strpos($current_page, '/jetfilter/gestor/distribuidoras_venezuela/detalle/') !== false || 
         strpos($current_page, '/jetfilter/gestor/distribuidoras_venezuela/estado/') !== false) {
    // Esto se ejecutará para cualquier archivo dentro de la carpeta detalle
    echo '<script type="text/javascript" src="./../../js/jquery.min.js"></script>
          <script type="text/javascript" src="./../../js/jquery.ez-plus.js"></script>
          <script src="./../../js/sweetAlerta.js"></script>';
} else if (strpos($current_page, '/jetfilter/gestor/pedido/') !== false || 
          strpos($current_page, '/jetfilter/gestor/soporte/') !== false ) {
    echo '<link href="./../../../css/css_vende/styles.css" rel="stylesheet">
     <script src="./../js/sweetAlerta.js"></script>';
} 
else {
    echo '<script type="text/javascript" src="../js/jquery.min.js"></script>
          <script type="text/javascript" src="../js/jquery.ez-plus.js"></script>
          <script src="./../js/sweetAlerta.js"></script>';
}

?>
    
   
	
	
	<link rel="stylesheet" href="<?php echo $loc ?>css/css_vende/dataTables.bootstrap51.css">

</head>
 <body>
   <header>
  
        <nav class="menu">
           <a href="<?php echo $locj ?>gestor"> <img src="<?php echo $loc; ?>jetfilter/img/logojbn.png" class="logo" alt="logo"> </a>
      
    
          <div class="menu__hamburguer">
            <img src="<?php echo $loc; ?>img/assets/menu.svg" class="menu__img">
          </div>
       

         <section class="menu__container">
           

            <ul class="menu__links">
            
                <li class="menu__item ">
                    <a href="<?php echo $locj; ?>gestor" class="menu__link">Inicio</a>
                </li>

                <?php 
                if (in_array(7, $permisos_usuario)) { ?>

                <li class="menu__item ">
                    <a href="<?php echo $locj; ?>gestor/especificaciones.php" class="menu__link">Catálogo</a>
                </li>
                 
                <?php
                } if (in_array(2, $permisos_usuario)) { ?>

                <li class="menu__item  menu__item--show">
                    <a href="#" class="menu__link"> Distribuidores</a>
    
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="<?php echo $locj; ?>gestor/distribuidoras_venezuela/detalle/espec_distribuidor.php" class="menu__link  menu__linka  menu__link--inside">Detalle</a>
                        </li>
                        <li class="menu__inside">
                            <a href="<?php echo $locj; ?>gestor/distribuidoras_venezuela/estado/estados_distribuidores.php" class="menu__link menu__link--inside">Zona de distribución </a>
                        </li>
                       
                    </ul>
                </li>
                <?php
                } if (in_array(3, $permisos_usuario)) { ?>
                <li class="menu__item ">
                    <a href="<?php echo $locj; ?>gestor/pedido/" class="menu__link">Pedidos</a>
                </li>
                 <?php
                 } if (in_array(4, $permisos_usuario)) { ?>
                <li class="menu__item ">
                    <a href="<?php echo $locj; ?>gestor/soporte/" class="menu__link">Ticket Soporte</a>
                </li>
                        <?php
            } if (in_array(5, $permisos_usuario) || in_array(6, $permisos_usuario)) { ?>

                 <li class="menu__item  menu__item--show">
                    <a href="#" class="menu__link"> Usuarios</a>
    
                    <ul class="menu__nesting">
                         <?php
                        if (in_array(5, $permisos_usuario)) { ?>

                        <li class="menu__inside">
                            <a href="<?php echo $locj; ?>gestor/usuario/" class="menu__link  menu__linka  menu__link--inside">Clientes</a>
                        </li>
                        <?php
                       } if (in_array(6, $permisos_usuario)) { ?>
                        <li class="menu__inside">
                            <a href="<?php echo $locj; ?>gestor/usuario_asministradores/" class="menu__link menu__link--inside">Administradores </a>
                        </li>
                        <?php
                         } ?>
                    </ul>
                </li>  
                <?php
                  } ?>

                <li class="menu__item ">
                    <a href="<?php echo $locj; ?>cerrar_sesion.php" class="menu__link"><i class='bx bx-log-out me-2'></i><?php echo $_SESSION['name']; ?> </a>
                </li>
                

        
            </ul>
                
          
            
        </section>
    
        
       
    </nav>

</header>

<main>
