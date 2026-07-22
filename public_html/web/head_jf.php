<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
date_default_timezone_set('America/Caracas');
    $rann = date('H:i:s');
  // Obtener la ruta actual
$current_page = $_SERVER['REQUEST_URI'];  
?>
<!DOCTYPE html>
<html lang="es">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-C8N7YZ8KES"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-C8N7YZ8KES');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NQTTXK3H');</script>
<!-- End Google Tag Manager -->
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
      
   
    <?php if (strpos($current_page, '/jetfilter/login/') !== false ||
    strpos($current_page, '/jetfilter/login/index.php') !== false ) {
    echo '<link rel="stylesheet" href="'.$loc.'css/jetfilter_estilos.css?t='.$rann.'">';
    }

    if ($current_page === '/jetfilter/' || $current_page === '/jetfilter/index.php' ) {
        echo '<link rel="stylesheet" href="'.$loc.'css/estilosjetfilter_index.css?t='.$rann.'">';
         echo '<link rel="stylesheet" href="'.$loc.'css/estitos_ltiempo.css?t='.$rann.'">';
    }

        $rutas_login = [
    '/jetfilter/login/',
    '/jetfilter/login/index.php',
    '/jetfilter/login/?fallo=true',
    '/jetfilter/login/?fallo=captcha' // Nueva condición agregada
];
?>
    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/bootstrap/css/bootstrap.min.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/boxicons/css/boxicons.min.css?t=<?php echo $rann?>">

  
    
</head>
 <body>
 <header>
     <nav class="menu">

        <?php if (in_array($current_page, $rutas_login)) { ?>

            <a href="./../"><img src="<?php echo $locj; ?>jetfilter/img/logojbn.png" class="logo" alt="logo"> </a>

        <?php } else { ?> 
            
            <a href="<?php echo $loc; ?>"> 
                <img src="<?php echo $locj; ?>jetfilter/img/logojbn.png" class="logo" alt="logo"> 
            </a>
            
             <div class="menu__hamburguer">
                <img src="<?php echo $loc; ?>img/assets/menu.svg" class="menu__img">
            </div>
            
             <section class="menu__container">
           

            <ul class="menu__links">
            
                <li class="menu__item ">
                        <a href="<?php echo $loc; ?>" class="menu__link">Inicio</a>
                    </li>
                    
                    <li class="menu__item">
                        <a href="#misionvision" class="menu__link">Misión / Visión</a>
                    </li>

                    <li class="menu__item">
                        <a href="#rese_histotorica" class="menu__link"> Reseña Histórica</a>
                    </li>

                    <li class="menu__item">
                        <a href="#calidad" class="menu__link">Políticas de Calidad</a>
                    </li>
                    <li class="menu__item">
                        <a href="#seguridad" class="menu__link">Política de Seguridad</a>
                    </li>
                    

                    <li class="menu__item">
                        <a href="./login/" class="menu__link">Gestión</a>
                    </li>
                    
                </ul>
                
            </section> <?php } ?>
        
    </nav>
</header>

<main>