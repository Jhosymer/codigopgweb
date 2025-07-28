<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
 
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
    date_default_timezone_set('America/Caracas');
    $rann = date('H:i:s');

?>
<!DOCTYPE html>
<html lang="es">
<script async>(function(w, d) { var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.setAttribute("src", "https://app.bluecaribu.com/conversion/integration/028f33c865d6ee44ea34e96ce9ca571f"); h.appendChild(s); })(window, document);</script>
<head>
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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width"/>
    
    <link rel="icon" href="img/icono/web.ico">
  
   
    <link rel="stylesheet" href="css/estilos_menu.css?t=<?php echo $rann?>">
    
    <link rel="stylesheet" href="css/estilos.css?t=<?php echo $rann?>">
    
    <link rel="stylesheet" href="css/busqueda_rapida.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/estilo_abajo.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/estilos_video.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/estilo_nano.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/estilos_para_lineas.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/alerta_img.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="css/estilo_app.css?t=<?php echo $rann?>">
    
 


    
    <link href="css/heder_estilos.css?t=<?php echo $rann?>" rel="stylesheet" type="text/css" />


    <link rel="stylesheet" href="./css/normalize_min.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./css/estilos_t_filtro.css?t=<?php echo $rann?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src='./../js/main.js'></script>
    <script type="text/javascript" src='./../js/jquery.min.js'></script>
    

</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NQTTXK3H"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
  
  
                    <nav class="menu ">
        <a href="./"> <img src="img/logo/web.png" class="logo" alt="logo"> </a>
        <div class="bus_none">
        <?php include_once("./index/busqueda.php"); ?>

    </div>
    
        <div class="menu__hamburguer">
            <img src="./assets/menu.svg" class="menu__img">
        </div>
       

         <section class="menu__container">
           

            <ul class="menu__links">
                <li class="menu__item menu__item--show">
                    <a href="#" class="menu__link">Productos<img src="assets/arrow.svg" class="menu__arrow" alt="arrow_menu"></a>
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="./productos/para_aceite.php" class="menu__link menu__link--inside">Filtro para Aceite </a>
                        </li>
                        <li class="menu__inside">
                            <a href="./productos/para_aire.php" class="menu__link menu__link--inside">Filtro para Aire</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./productos/para_combustible.php" class="menu__link menu__link--inside">Filtro para Combustible </a>
                        </li>
                        <li class="menu__inside">
                            <a href="./productos/para_fluidos.php" class="menu__link menu__link--inside">Fluidos</a>
                        </li>
                    
                    </ul>
                </li>
    
                <li class="menu__item  menu__item--show">
                    <a href="#" class="menu__link"> Catálogos <img src="assets/arrow.svg" class="menu__arrow" alt="arrow_menu"></a>
    
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="./busqueda_aplicacion/aplicaciones.php" class="menu__link  menu__linka  menu__link--inside">Por aplicaciones</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./busqueda_codigo/porcodigo.php" class="menu__link menu__link--inside"> Por código</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./busqueda_especificaciones/especificaciones.php" class="menu__link menu__link--inside"> Por especificaciones</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./catalogo/descargas/" class="menu__link menu__link--inside"> Descargas</a>
                        </li>
                    </ul>
                </li>



                <li class="menu__item ">
                    <a href="./distribuidores/" class="menu__link">Distribuidores</a>
                </li>
                <li class="menu__item ">
                    <a href="./../nuevos_productos/" class="menu__link">Nuevos Productos</a>
                </li>
                
                <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Nosotros <img src="./assets/arrow.svg" class="menu__arrow"></a>
                    
                                    <ul class="menu__nesting">
                                        <li class="menu__inside">
                                            <a href="./jetfilter/" class="menu__link  menu__linka  menu__link--inside">Jet-Filter</a>
                                        </li>
                                      

                                        <li class="menu__inside">
                                            <a href="./manual_corporativo/" class="menu__link menu__link--inside">Manual Corporativo</a>
                                        </li>
                                        <li class="menu__inside">
                                            <a href="./politica/" class="menu__link menu__link--inside">Política de Privacidad</a>
                                        </li>

                                        

                                    </ul>
                </li>

                
                 <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Soporte<img src="./assets/arrow.svg" class="menu__arrow"></a>
                    
                                    <ul class="menu__nesting">

                                      <li class="menu__inside">
                                           <a href="./soporte/instrucciones/" class="menu__link menu__link--inside">Instrucciones</a>
                                        </li>
                                         <li class="menu__inside">
                                 <a href="./soporte/Filtro_Elemento_Colapsado/" class="menu__link  menu__linka  menu__link--inside">Filtro Colapsado</a>
                            </li>
                            <li class="menu__inside">
                                <a href="./soporte/Luz_de_Presion_de_Aceite/" class="menu__link menu__link--inside">Luz de Presión de Aceite</a>
                            </li>
                           
                            <li class="menu__inside">
                                <a href="./soporte/perdida_alimentacion_bomba_aceite/" class="menu__link menu__link--inside">Bomba de Aceite</a>
                            </li>

                              
                            <li class="menu__inside">
                                <a href="./soporte/Presion_aceite/" class="menu__link menu__link--inside">Presión de Aceite</a>
                            </li>

                             <li class="menu__inside">
                                <a href="./soporte/Politica_privacidad_WebCat/" class="menu__link menu__link--inside">Política de privacidad de WebCat</a>
                            </li>
                                    </ul>
                </li>

                


                  
                    
                    <li class="menu__item buscar_g">
                        <?php include("./web/busqueda.php"); ?>
                    </li>
                  
            </div>
        
            </ul>
                
            </div>
            </div>
            
        </section>
    </li >
        </section>
        
       
    </nav>
