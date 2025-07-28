<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
 
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
    date_default_timezone_set('America/Caracas');
    $rann = date('H:i:s');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width"/>
    <link rel="icon" href="./../../img/icono/web.ico">

    <link rel="stylesheet" href="./../../css/estilos_distribuidor.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_aplicacion.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_links.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_ficha_producto.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilo_clas_producto.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/idioma.css?t=<?php echo $rann?>">
     <link rel="stylesheet" href="./../../css/estilo_des_logo.css?t=<?php echo $rann?>">
     <link rel="stylesheet" href="./../../css/modal.css?t=<?php echo $rann?>">
     <link rel="stylesheet" href="./../../css/carusel_lineas.css?t=<?php echo $rann?>">
    
    <link rel="stylesheet" href="./../../css/estilos_catalogo_descargas.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_menu.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_para_lineas.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilo_nano.css?t=<?php echo $rann?>">

    <link rel="stylesheet" href="./../../css/mapa_vnz.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/busqueda_rapida.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilo_abajo.css?t=<?php echo $rann?>">
    <link rel="stylesheet" href="./../../css/estilos_t_filtro.css?t=<?php echo $rann?>">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js?t=<?php echo $rann?>"></script>
    <script type="text/javascript" src='./../../js/main.js?t=<?php echo $rann?>'></script>
    <script type="text/javascript" src='./../../js/jquery.min.js?t=<?php echo $rann?>'></script>
	
    
</head>

<body>
      

    <nav class="menu_carpeta">
        <a href="./../../"> <img src="./../../img/logo/web.png" class="logo"> </a>
        <div class="bus_none">
            <?php include_once("./../../index/busqueda_carpeta_catalogo.php"); ?>
        </div>
    
        <div class="menu__hamburguer">
            <img src="./../../assets/menu.svg" class="menu__img">
        </div>
       

         <section class="menu__container">
           

          <ul class="menu__links">
                <li class="menu__item menu__item--show">
                    <a href="#" class="menu__link">Productos<img src="./../../assets/arrow.svg" class="menu__arrow" alt="arrow_menu"></a>
                   
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="./../../productos/para_aceite.php" class="menu__link menu__link--inside" >Filtro para Aceite </a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../productos/para_aire.php" class="menu__link menu__link--inside">Filtro para Aire</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../productos/para_combustible.php" class="menu__link menu__link--inside">Filtro para Combustible </a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../productos/para_fluidos.php" class="menu__link menu__link--inside">Fluidos</a>
                        </li>
                    
                    </ul>
                  
                </li>
    
                <li class="menu__item  menu__item--show">
                    <a href="#" class="menu__link"> Catálogos <img src="./../../assets/arrow.svg" class="menu__arrow" alt="arrow_menu"></a>
    
                    <ul class="menu__nesting">
                        <li class="menu__inside">
                            <a href="./../../busqueda_aplicacion/aplicaciones.php" class="menu__link  menu__linka  menu__link--inside">Por aplicaciones</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../busqueda_codigo/porcodigo.php" class="menu__link menu__link--inside"> Por código</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../busqueda_especificaciones/especificaciones.php" class="menu__link menu__link--inside"> Por especificaciones</a>
                        </li>
                        <li class="menu__inside">
                            <a href="./../../catalogo/descargas/" class="menu__link menu__link--inside"> Descargas</a>
                        </li>
                    </ul>
                </li>



                <li class="menu__item ">
                    <a href="./../../distribuidores/" class="menu__link">   Distribuidores  </a>
                </li>

                <li class="menu__item ">
                    <a href="./../../nuevos_productos/" class="menu__link">Nuevos Productos</a>
                </li>

                    <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Nosotros <img src="./../../assets/arrow.svg" class="menu__arrow"></a>
                    
                                    <ul class="menu__nesting">
                                        <li class="menu__inside">
                                            <a href="./../../jetfilter/" class="menu__link  menu__linka  menu__link--inside">Jet-Filter</a>
                                        </li>
                                      

                                        <li class="menu__inside">
                                            <a href="./../../manual_corporativo/" class="menu__link menu__link--inside">Manual Corporativo</a>
                                        </li>
                                        <li class="menu__inside">
                                            <a href="./../../politica/" class="menu__link menu__link--inside">Política de Privacidad</a>
                                        </li>

                                        

                                    </ul>
                </li>

                
                 <li class="menu__item  menu__item--show">
                                    <a href="#" class="menu__link">Soporte<img src="./../../assets/arrow.svg" class="menu__arrow"></a>
                    
                                    <ul class="menu__nesting">

                                      <li class="menu__inside">
                                           <a href="./../../soporte/instrucciones/" class="menu__link menu__link--inside">Instrucciones</a>
                                        </li>
                                         <li class="menu__inside">
                                 <a href="./../../soporte/Filtro_Elemento_Colapsado/" class="menu__link  menu__linka  menu__link--inside">Filtro Colapsado</a>
                            </li>
                            <li class="menu__inside">
                                <a href="./../../soporte/Luz_de_Presion_de_Aceite/" class="menu__link menu__link--inside">Luz de Presión de Aceite</a>
                            </li>
                           
                            <li class="menu__inside">
                                <a href="./../../soporte/perdida_alimentacion_bomba_aceite/" class="menu__link menu__link--inside">Bomba de Aceite</a>
                            </li>

                              
                            <li class="menu__inside">
                                <a href="./../../soporte/Presion_aceite/" class="menu__link menu__link--inside">Presión de Aceite</a>
                            </li>

                             <li class="menu__inside">
                                <a href="./../../soporte/Politica_privacidad_WebCat/" class="menu__link menu__link--inside">Política de privacidad de WebCat</a>
                            </li>
                                    </ul>
                </li>

                    <li class="menu__item buscar_g">
                        <?php include("./../../web/busqueda_carpeta_catalogo.php"); ?>
                    </li>
                  
            </div>
        
            </ul>
                
            </div>
            </div>
            
        </section>
    </li >
        </section>
        
       
    </nav>
    
