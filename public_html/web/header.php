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
<!-- web pana-->
<?php
if ($current_page == '/' || $current_page == '/index.php') {
    echo '<script async>(function(w, d) { var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.setAttribute("src", "https://app.bluecaribu.com/conversion/integration/028f33c865d6ee44ea34e96ce9ca571f"); h.appendChild(s); })(window, document);</script>
';
}
?>


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <meta name="description" content="<?php echo $description; ?>">

    <link rel="icon" href="<?php echo $loc; ?>img/logo/web.ico">
  
    
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilos_menu.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/busqueda_rapida.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilos.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/heder_estilos.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilo_nano.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilos_video.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/mapa_vnz.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilo_app.css?t=<?php echo $rann?>">
<?php /* <link rel="stylesheet" href="<?php echo $loc; ?>css/alerta_img.css?t=<?php echo $rann?>">*/?>
    <link rel="stylesheet" href="<?php echo $loc; ?>css/estilo_clas_producto.css?t=<?php echo $rann?>">
     <link rel="stylesheet" href="<?php echo $loc; ?>css/estilo_carusel_fichat.css?t=<?php echo $rann?>">
  <?php if (strpos($current_page, '/catalogo/busqueda_por_codigo/') !== false ||
    strpos($current_page, '/catalogo/busqueda_por_aplicacion/') !== false ||
    strpos($current_page, '/catalogo/busqueda_por_especificaciones/') !== false ) {
    echo '<link rel="stylesheet" href="'.$loc.'css/estio_boddy_no-grid-layout.css?t='.$rann.'">';
}

?>



    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/bootstrap/css/bootstrap.min.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="<?php echo $loc; ?>vendor/boxicons/css/boxicons.min.css?t=<?php echo $rann?>">


    <link rel="stylesheet" href="<?php echo $loc; ?>css/splide.min.css?t=<?php echo $rann?>">

    <link rel="stylesheet" href="<?php echo $loc; ?>css/robotchat.css">

   
<script type="text/javascript" src="<?php echo $loc; ?>jetfilter/gestor/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $loc; ?>jetfilter/gestor/js/sweetAlerta.js"></script>

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NQTTXK3H"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  <header>
  
                    <nav class="menu">
        <a href="<?php echo $loc; ?>"> <img src="<?php echo $loc; ?>img/logo/web2.png" class="logo" alt="logo"> </a>
        <div class="bus_none">
        <?php include_once($loc . "web/busqueda.php"); ?>

    </div>
    
        <div class="menu__hamburguer">
            <img src="<?php echo $loc; ?>img/assets/menu.svg" class="menu__img">
        </div>
       

         <section class="menu__container">
           

            <ul class="menu__links">
                <li class="menu__item menu__item--show">
                    <a href="#" class="menu__link">Productos</a>
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="<?php echo $loc; ?>productos/filtros_para_aceite/" class="menu__link menu__link--inside">Filtro para Aceite </a>
                        </li>
                        <li class="menu__inside">
                            <a href="<?php echo $loc; ?>productos/filtros_para_aire/" class="menu__link menu__link--inside">Filtro para Aire</a>
                        </li>
                        <li class="menu__inside">
                            <a href="<?php echo $loc; ?>productos/filtros_para_combustible/" class="menu__link menu__link--inside">Filtro para Combustible </a>
                        </li>
                        <li class="menu__inside">
                            <a href="<?php echo $loc; ?>productos/fluidos/" class="menu__link menu__link--inside">Fluidos</a>
                        </li>
                    
                    </ul>
                </li>

                <li class="menu__item ">
                    <a href="<?php echo $loc; ?>catalogo/" class="menu__link">Catálogos</a>
                </li>
    
                



                <li class="menu__item ">
                    <a href="<?php echo $loc; ?>distribuidores/" class="menu__link">Distribuidores</a>
                </li>
                
                <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Nosotros</a>
                    
                                    <ul class="menu__nesting">
                                        <li class="menu__inside">
                                            <a href="<?php echo $loc; ?>jetfilter/" class="menu__link  menu__linka  menu__link--inside">Jet-Filter</a>
                                        </li>
                                      

                                        <li class="menu__inside">
                                            <a href="<?php echo $loc; ?>manual_corporativo/" class="menu__link menu__link--inside">Manual Corporativo</a>
                                        </li>
                                        <li class="menu__inside">
                                            <a href="<?php echo $loc; ?>politica/" class="menu__link menu__link--inside">Política de Privacidad</a>
                                        </li>

                                        

                                    </ul>
                </li>

                
                 <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Soporte</a>
                    
                                    <ul class="menu__nesting">

                                      <li class="menu__inside">
                                           <a href="<?php echo $loc; ?>soporte/instrucciones/" class="menu__link menu__link--inside">Instrucciones</a>
                                        </li>
                                         <li class="menu__inside">
                                 <a href="<?php echo $loc; ?>soporte/Filtro_Elemento_Colapsado/" class="menu__link  menu__linka  menu__link--inside">Filtro Colapsado</a>
                            </li>
                            <li class="menu__inside">
                                <a href="<?php echo $loc; ?>soporte/Luz_de_Presion_de_Aceite/" class="menu__link menu__link--inside">Luz de Presión de Aceite</a>
                            </li>
                           
                            <li class="menu__inside">
                                <a href="<?php echo $loc; ?>soporte/perdida_alimentacion_bomba_aceite/" class="menu__link menu__link--inside">Bomba de Aceite</a>
                            </li>

                              
                            <li class="menu__inside">
                                <a href="<?php echo $loc; ?>soporte/Presion_aceite/" class="menu__link menu__link--inside">Presión de Aceite</a>
                            </li>

                             <li class="menu__inside">
                                <a href="<?php echo $loc; ?>soporte/Politica_privacidad_WebCat/" class="menu__link menu__link--inside">Política de privacidad de WebCat</a>
                            </li>
                                    </ul>
                </li>

                


                  
                    
                    <li class="menu__item buscar_g">
                        <?php include($loc ."web/busqueda.php"); ?>
                    </li>
                  
            </div>
        
            </ul>
                
            </div>
            </div>
            
        </section>
    </li >
        </section>
        
       
    </nav>

</header>

<main>
