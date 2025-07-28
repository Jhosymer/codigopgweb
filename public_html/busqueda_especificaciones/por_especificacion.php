<?php
   include("./../../conexion.php");
    
   $paginas = [10, 25, 50, 100];
?>

<link rel="stylesheet" href="./../css/estilos_links.css" >

<style>
   #tabla_aireautomotriz {
      display: none;
   }

   #tabla_aireindustrial {
      display: none;
   }

   #tabla_combustible {
      display: none;
   }

   #tabla_elemento {
      display: none;
   }

   #tabla_fluidos {
      display: none;
   }


   #tabla_panel {
      display: none;
   }

   #tabla_sellado {
      display: none;
   }
</style>


<title> Por especificaciones</title>
   <section class="detalle" id="detalle_especificaciones">
      <h1 class="title_busqueda">Catálogo de Productos por Especificaciones Técnicas</h1>
      <section class="about_aplicacion_op" > 
         <div>
            <div class="about_aplicacion_sel">
               <label id="tituloSelPrincipal" class="sub_title_busqueda">Linea:</label><br>
               <select id="claseFiltros" name="claseFiltros" class="select">
                  <option value="" disabled selected>--Seleccione--</option>
                  <option value="sellado">Sellado</option>
                  <option value="panel">Panel</option>
                  <option value="fluidos">Fluidos</option>
                  <option value="elemento">Elemento</option>
                  <option value="combustiblelinea">Combustible en Línea</option>
                  <option value="aireautomotriz">Aire Automotriz</option>
                  <option value="aireindustrial">Aire Industrial</option>
               </select>
            </div>
            <div class="about_aplicacion_sel" id="tipo_filtro">
                  <label class="sub_title_busqueda">Tipo:</label><br>
                  <select id="tipoFiltros" class="selTipoSellado select">
                  </select>
            </div>
            <div class="about_aplicacion_sel" id="rosca_filtro">
                  <label id="RoscaRosca" class="sub_title_busqueda">Rosca:</label><br>
                  <select id="roscaFiltro" class="selRoscaRosca select">
                  </select>
            </div>
         </div>
         <div id="tabla_especificaciones" >
            <div class="flex_mostra_espe">
               <div id="registros_div">
                  <div class="flex_mostra">
                     <div>
                        <label class="a">Mostrar:</label>
                     </div>
                     <div>
                        <select name="registros" id="registros_especificaciones">
                           <?php 
                              foreach($paginas as $pag){
                                    if($pag == $perPage){
                                       ?>
                                          <option value="<?php echo $perPage; ?>" selected><?php echo $perPage; ?></option>
                                       <?php
                                    }
                                    else{
                                       ?>
                                          <option value="<?php echo $pag; ?>"><?php echo $pag; ?></option>
                                       <?php
                                    }
                              }
                           ?>      
                        </select>
                     </div>
                  </div>
               </div>
               <div class="about_aplicacion_sel" id="campo_busqueda">
                     <label id="buscar_especificaciones"></label>
                     <input type="text" id="valorBuscar" name="valorBuscar" class="vBuscar" placeholder="Buscar">
               </div>
            </div>
            <div class="about_aplicacion_sel table-responsive" >
               <table class='busqueda_apli' >
                  <thead class='busqueda_apli about_sel_right'>
                     <tr class='busqueda_apli' id='tabla_aireautomotriz' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø ext (mm) 
                           <img id="imagen_descendente_externo" onclick="orden({orden_externo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_externo" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_externo" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø int (mm) 
                           <img id="imagen_descendente_interno" onclick="orden({orden_interno_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_interno" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_interno" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Altura (mm) 
                           <img id="imagen_descendente_altura" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_aireindustrial' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_aireindustrial" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_aireindustrial" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_aireindustrial" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø ext (mm) 
                           <img id="imagen_descendente_externo_aireindustrial" onclick="orden({orden_externo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_externo_aireindustrial" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_externo_aireindustrial" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø int (mm) 
                           <img id="imagen_descendente_interno_aireindustrial" onclick="orden({orden_interno_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_interno_aireindustrial" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_interno_aireindustrial" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Altura (mm) 
                           <img id="imagen_descendente_altura_aireindustrial" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura_aireindustrial" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura_aireindustrial" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_combustible' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_combustible" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_combustible" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_combustible" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø ext (mm) 
                           <img id="imagen_descendente_externo_combustible" onclick="orden({orden_externo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_externo_combustible" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_externo_combustible" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Altura (mm)
                           <img id="imagen_descendente_altura_combustible" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura_combustible" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura_combustible" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Líneas 
                           <img id="imagen_descendente_entrada_combustible" onclick="orden({orden_entrada_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_entrada_combustible" onclick="orden({orden_entrada_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_entrada_combustible" onclick="orden({orden_entrada_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_elemento' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_elemento" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_elemento" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_elemento" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø ext (mm) 
                           <img id="imagen_descendente_externo_elemento" onclick="orden({orden_externo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_externo_elemento" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_externo_elemento" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø int (mm) 
                           <img id="imagen_descendente_interno_elemento" onclick="orden({orden_interno_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_interno_elemento" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_interno_elemento" onclick="orden({orden_interno_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Altura (mm) 
                           <img id="imagen_descendente_altura_elemento" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura_elemento" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura_elemento" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_fluidos' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_fluidos" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_fluidos" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_fluidos" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Detalle 1 
                           <img id="imagen_descendente_detalle1_fluidos" onclick="orden({orden_detalle1_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_detalle1_fluidos" onclick="orden({orden_detalle1_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_detalle1_fluidos" onclick="orden({orden_detalle1_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Detalle 2
                           <img id="imagen_descendente_detalle2_fluidos" onclick="orden({orden_detalle2_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_detalle2_fluidos" onclick="orden({orden_detalle2_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_detalle2_fluidos" onclick="orden({orden_detalle2_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_panel' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_panel" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_panel" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_panel" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Largo (mm) 
                           <img id="imagen_descendente_largo_panel" onclick="orden({orden_largo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_largo_panel" onclick="orden({orden_largo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_largo_panel" onclick="orden({orden_largo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Ancho (mm) 
                           <img id="imagen_descendente_ancho_panel" onclick="orden({orden_ancho_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_ancho_panel" onclick="orden({orden_ancho_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_ancho_panel" onclick="orden({orden_ancho_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Altura (mm)
                           <img id="imagen_descendente_altura_panel" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura_panel" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura_panel" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">   
                        </td>
                     </tr>
                     <tr class='busqueda_apli' id='tabla_sellado' > 
                        <td class='busqueda_apli' > 
                           Código 
                           <img id="imagen_descendente_codigo_sellado" onclick="orden({orden_codigo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_codigo_sellado" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_codigo_sellado" onclick="orden({orden_codigo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Tipo / Rosca 
                           <img id="imagen_descendente_tipo_sellado" onclick="orden({orden_tipo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_tipo_sellado" onclick="orden({orden_tipo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_tipo_sellado" onclick="orden({orden_tipo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           ø ext (mm)
                           <img id="imagen_descendente_externo_sellado" onclick="orden({orden_externo_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_externo_sellado" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_externo_sellado" onclick="orden({orden_externo_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                         </td>
                        <td class='busqueda_apli' > 
                           Altura (mm) 
                           <img id="imagen_descendente_altura_sellado" onclick="orden({orden_altura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_altura_sellado" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_altura_sellado" onclick="orden({orden_altura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Empacadura (mm) 
                           <img id="imagen_descendente_empacadura_sellado" onclick="orden({orden_empacadura_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_empacadura_sellado" onclick="orden({orden_empacadura_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_empacadura_sellado" onclick="orden({orden_empacadura_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                        <td class='busqueda_apli' > 
                           Valvulas 
                           <img id="imagen_descendente_valvulas_sellado" onclick="orden({orden_valvulas_descendente: 1})"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                           <img id="imagen_ascendente_valvulas_sellado" onclick="orden({orden_valvulas_ascendente: 1})"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                           <img id="igual_valvulas_sellado" onclick="orden({orden_valvulas_ascendente: 1})"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                        </td>
                     </tr>
                  </thead>
                  <tbody id='resultados'>

                  </tbody>
               </table>
            </div>
            <div id="totalResultado_especificaciones">

            </div>
            <div id="navegacion_especificaciones" class="links linka">

            </div>
         </div>
      </section>
   </section>

<script>
    let paginaActual = 1;
    document.getElementById("valorBuscar").addEventListener("keyup", function(){
        getTabla(1);
    }, false);

    document.getElementById("registros_especificaciones").addEventListener("change", function(){
        getTabla(1);
    }, false);

   //Cuando se Cargue el DOM
   window.addEventListener("DOMContentLoaded", (event) => {
      document.getElementById('tipo_filtro').style.display = "none";
      document.getElementById('rosca_filtro').style.display = "none";
      document.getElementById('buscar_especificaciones').style.display = "none";
      document.getElementById('valorBuscar').style.display = "none";
      document.getElementById('resultados').style.display = "none";
      document.getElementById('registros_div').style.display = "none";

      clase_filtro = document.getElementById('claseFiltros');
      tipo_filtro = document.getElementById('tipoFiltros');
      rosca_filtro = document.getElementById('roscaFiltro');


      /*------------------CAMBIO EN LÍNEA-----------------*/ 
      clase_filtro.addEventListener("change", () => {
         var valorCambiado = document.getElementById('claseFiltros').value;
         document.getElementById("valorBuscar").value = "";
         
         /*----------------------------SI LA LINEA REQUIERE TIPO------------------------*/ 
         if( valorCambiado == "sellado" || valorCambiado == "elemento" || valorCambiado == "panel" || valorCambiado == "fluidos" ){

            formData = new FormData();
            formData.append('especificacion', valorCambiado);
            fetch('./../ajax_busquedas/ajax_clase.php', { 
               method: 'POST',
               body: formData,
            })
            .then( response => response.json() )
            .then( data => document.getElementById('tipoFiltros').innerHTML = data )
            .then( document.getElementById('tabla_especificaciones').style.display = "none" )
            .then( document.getElementById('tipo_filtro').style.display = "block" )
            .then( document.getElementById('tipo_filtro').value = "vacio" )
            .then( document.getElementById('rosca_filtro').style.display = "none" )
            .then( document.getElementById('rosca_filtro').style.display = "vacio" )
            .catch( error => alert(error) )
         }
         /*----------------------------SI LA LINEA NO REQUIERE TIPO------------------------*/ 
         else {
            roscaFiltro = document.getElementById('rosca_filtro');
            tipoFiltro = document.getElementById('tipo_filtro');
            
            tipoFiltro.style.display = "none";
            roscaFiltro.style.display = "none";
            roscaFiltro.value = "vacio";

            var especificacion = document.getElementById('claseFiltros').value;

            formData = new FormData();
            formData.append('especificacion', especificacion);
            fetch('./../ajax_busquedas/ajax_tabla.php', { 
                 method: 'POST',
                 body: formData,
            })
            .then( response => response.json() )
            .then( 
               data => {
                  document.getElementById('resultados').innerHTML = data.data;
                  document.getElementById('navegacion_especificaciones').innerHTML = data.paginacion;
               }, 
            )
            .then( () => {
               document.getElementById('resultados').style.display = "contents";
               document.getElementById('buscar_especificaciones').style.display = "block";
               document.getElementById('valorBuscar').style.display = "block";
               document.getElementById('registros_div').style.display = "block";
               if( especificacion == "combustiblelinea" ){
                  especificacion = "combustible";
               }
               cambiarTabla(especificacion);
            })
            .then( document.getElementById('tabla_especificaciones').style.display = "block" )
            .catch( error => alert(error) )
         }
      })
        

      /*------------------CAMBIO EN TIPO-----------------*/ 
      tipo_filtro.addEventListener("change", () => {
         document.getElementById("valorBuscar").value = "";
         var valorCambiado = document.getElementById('claseFiltros').value;
         var valorTipo = document.getElementById('tipoFiltros').value;
         
         /*----------------------------SI LA LINEA REQUIERE ROSCA------------------------*/ 
         if( valorCambiado == "sellado" ){

            formData = new FormData();
            formData.append('especificacion', valorCambiado);
            formData.append('tipo', valorTipo);
            fetch('./../ajax_busquedas/ajax_tipo.php', { 
               method: 'POST',
               body: formData,
            })
            .then( response => response.json() )
            .then( 
               data => {
                  document.getElementById('roscaFiltro').innerHTML = data;
               }, 
            )
            .then( () => {
               document.getElementById('rosca_filtro').style.display = "block";
            })
            .catch( error => alert(error) )  

         }
         /*----------------------------SI LA LINEA NO REQUIERE ROSCA------------------------*/ 
         else {
            document.getElementById('rosca_filtro').style.display = "none";

            var especificacion = document.getElementById('claseFiltros').value;
            var tipo = document.getElementById('tipoFiltros').value;

            $.ajax({
               data: {
                  'especificacion': especificacion,
                  'tipo': tipo,
               },
               url: './../ajax_busquedas/ajax_tabla.php',
               dataType: 'json',
               type: 'POST',
               success: function(response){
                  
                  document.getElementById('resultados').innerHTML = response.data;
                  document.getElementById('resultados').style.display = "contents";
                  document.getElementById('buscar_especificaciones').style.display = "block";
                  document.getElementById('valorBuscar').style.display = "block";
                  document.getElementById('registros_div').style.display = "block";
                  document.getElementById('tabla_especificaciones').style.display = "block";
                  if(response.totalFiltro != response.totalRegistros){
                     document.getElementById('totalResultado_especificaciones').innerHTML = '<p>Mostrando ' + response.totalFiltro + ' de ' + response.totalRegistros + ' registros</p>';
                  }
                  else {
                     document.getElementById('totalResultado_especificaciones').innerHTML = "";
                  }
                  document.getElementById('navegacion_especificaciones').innerHTML = response.paginacion;
                  cambiarTabla(especificacion);
               },
               error: function(error){
                  console.log(error.responseText);
               }
            })
         }
      })

      /*------------------CAMBIO EN ROSCA-----------------*/ 
      rosca_filtro.addEventListener('change', () => {
         var especificacion = document.getElementById('claseFiltros').value;
         var tipo = document.getElementById('tipoFiltros').value;
         var rosca = document.getElementById('roscaFiltro').value;

         $.ajax({
            data: {
               'especificacion': especificacion,
               'tipo': tipo,
               'rosca': rosca,
            },
            url: './../ajax_busquedas/ajax_tabla.php',
            dataType: 'json',
            type: 'POST',
            success: function(response){
               document.getElementById('resultados').innerHTML = response.data;
               document.getElementById('resultados').style.display = "contents";
               document.getElementById('buscar_especificaciones').style.display = "block";
               document.getElementById('valorBuscar').style.display = "block";
               document.getElementById('registros_div').style.display = "block";
               document.getElementById('tabla_especificaciones').style.display = "block";
               if(response.totalFiltro != response.totalRegistros){
                  document.getElementById('totalResultado_especificaciones').innerHTML = '<p>Mostrando ' + response.totalFiltro + ' de ' + response.totalRegistros + ' registros</p>';
               }
               else {
                  document.getElementById('totalResultado_especificaciones').innerHTML = "";
               }
               document.getElementById('navegacion_especificaciones').innerHTML = response.paginacion;
               cambiarTabla(especificacion);
            },
            error: function(error){
               console.log(error.responseText);
            }
         })
      })
   });

   function getTabla(pagina){
      var especificacion = document.getElementById('claseFiltros').value;
      let registros = document.getElementById("registros_especificaciones").value;
      let campo = document.getElementById("valorBuscar").value;

      formData = new FormData();
      formData.append('especificacion', especificacion);

      if( especificacion == 'elemento' || especificacion == 'panel' ||  especificacion == 'sellado' ){
         var tipo = document.getElementById('tipoFiltros').value;
         formData.append('tipo', tipo);
      }
      if( especificacion == 'sellado' ){
         var rosca = document.getElementById('roscaFiltro').value;
         formData.append('rosca', rosca);
      }
      formData.append('registros', registros);
      formData.append('campo', campo);
      formData.append('pagina', pagina);

      campo_ordenar = ( typeof campo_ordenar !== 'undefined' ) ? campo_ordenar : 'codigo';
      manera_orden = ( typeof manera_orden !== 'undefined' ) ? manera_orden : 'ASC';

      formData.append('campo_ordenar', campo_ordenar);
      formData.append('manera_orden', manera_orden);
 
      fetch('./../ajax_busquedas/ajax_tabla.php', { 
         method: 'POST',
         body: formData,
      })
      .then( response => response.json() )
      .then( 
         data => {
            document.getElementById('resultados').innerHTML = data.data;
            document.getElementById('resultados').style.display = "contents";
            document.getElementById('buscar_especificaciones').style.display = "block";
            document.getElementById('valorBuscar').style.display = "block";
            document.getElementById('registros_div').style.display = "block";
            if( data.totalFiltro != data.totalRegistros ){
               document.getElementById('totalResultado_especificaciones').innerHTML = '<p>Mostrando ' + data.totalFiltro + ' de ' + data.totalRegistros + ' registros</p>';
            }
            else {
               document.getElementById('totalResultado_especificaciones').innerHTML = "";
            }
            document.getElementById('navegacion_especificaciones').innerHTML = data.paginacion;
            cambiarTabla(especificacion);
         }, 
      )
      .catch( (error) => console.log(error) ) ;
   }

   function getFiltro(codigo){
      $('#detalle_especificaciones').css("display","none");
      $('#detalle').css("display","none");
      $.ajax({
         data: {
            'busquedaEspecificaciones': 1,
            'codigo': codigo,
         },
         url: "./../ajax_busquedas/filtro_seleccionado.php",
         type: "POST",
         dataType: "json",
         success: function (response){
            $('#detalle_producto').css("display","flex");
            $('#volver').css("display","none");
            document.getElementById('filtro_titulo').innerHTML = response.titulo;
            document.getElementById('filtro_carrusel').innerHTML = response.carrusel;
            document.getElementById('filtro_especificaciones').innerHTML = response.especificaciones;
            document.getElementById('filtro_aplicacion').innerHTML = response.aplicacion;
            document.getElementById('filtro_equivalencia').innerHTML = response.equivalencia;
         },
         error: function (error){
            console.log(error.responseText);
         }
      });
   }

   function volver(){
      $('#detalle_especificaciones').css("display","block");
      $('#detalle_producto').css("display","none");
      history.pushState(null, "", `./../busqueda_especificaciones/por_especificacion.php`);
   }

   
   function cambiarTabla(linea_seleccionada){
      lineas = ['aireautomotriz', 'aireindustrial', 'combustible', 'elemento', 'panel', 'sellado', 'fluidos'];

      lineas.forEach( function(linea){
         if( linea == linea_seleccionada ){
            document.getElementById('tabla_'+linea).style.display = "contents";
         }
         else {
            document.getElementById('tabla_'+linea).style.display = "none";
         }
      });
   }

   function orden({orden_codigo_ascendente = 0, orden_codigo_descendente = 0, orden_externo_ascendente = 0, orden_externo_descendente = 0, orden_interno_ascendente = 0, orden_interno_descendente = 0, orden_altura_ascendente = 0, orden_altura_descendente = 0, orden_entrada_ascendente = 0, orden_entrada_descendente = 0, orden_largo_descendente = 0, orden_ancho_descendente = 0, orden_largo_ascendente = 0, orden_ancho_ascendente = 0, orden_tipo_ascendente = 0, orden_tipo_descendente = 0, orden_valvulas_ascendente = 0, orden_valvulas_descendente = 0, orden_empacadura_ascendente = 0, orden_empacadura_descendente = 0, orden_detalle1_ascendente = 0, orden_detalle1_descendente = 0, orden_detalle2_ascendente = 0, orden_detalle2_descendente = 0}){
      var especificacion = document.getElementById('claseFiltros').value;
      if( especificacion == 'aireautomotriz' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none';
               document.getElementById('igual_codigo').style.display = 'none';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'inline-block';
               document.getElementById('igual_codigo').style.display = 'none';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_externo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none'
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'none';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'ASC';
         }
         else if( orden_externo_descendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none';
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'inline-block';
               document.getElementById('igual_externo').style.display = 'none';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'DESC';
         }
         else if( orden_interno_ascendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none'
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'none';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'ASC';
         }
         else if( orden_interno_descendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none'
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'inline-block';
               document.getElementById('igual_interno').style.display = 'none';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none'
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura').style.display = 'none';
               document.getElementById('igual_altura').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo').style.display = 'none'
               document.getElementById('igual_codigo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo').style.display = 'none';
               document.getElementById('imagen_ascendente_externo').style.display = 'none';
               document.getElementById('igual_externo').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno').style.display = 'none';
               document.getElementById('imagen_ascendente_interno').style.display = 'none';
               document.getElementById('igual_interno').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura').style.display = 'none';
               document.getElementById('imagen_ascendente_altura').style.display = 'inline-block';
               document.getElementById('igual_altura').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'aireindustrial' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('igual_codigo_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'inline-block';
               document.getElementById('igual_codigo_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_externo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none'
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'ASC';
         }
         else if( orden_externo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'inline-block';
               document.getElementById('igual_externo_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'DESC';
         }
         else if( orden_interno_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none'
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'ASC';
         }
         else if( orden_interno_descendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none'
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'inline-block';
               document.getElementById('igual_interno_aireindustrial').style.display = 'none';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none'
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('igual_altura_aireindustrial').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_aireindustrial').style.display = 'none'
               document.getElementById('igual_codigo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_aireindustrial').style.display = 'none';
               document.getElementById('igual_externo_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_aireindustrial').style.display = 'none';
               document.getElementById('igual_interno_aireindustrial').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_aireindustrial').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_aireindustrial').style.display = 'inline-block';
               document.getElementById('igual_altura_aireindustrial').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'combustiblelinea' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none';
               document.getElementById('igual_codigo_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'inline-block';
               document.getElementById('igual_codigo_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_externo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none'
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'diametroext';
               manera_orden = 'ASC';
         }
         else if( orden_externo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none';
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'inline-block';
               document.getElementById('igual_externo_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'diametroext';
               manera_orden = 'DESC';
         }
         else if( orden_entrada_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none'
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'entrada';
               manera_orden = 'ASC';
         }
         else if( orden_entrada_descendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none'
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'inline-block';
               document.getElementById('igual_entrada_combustible').style.display = 'none';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'inline-block';
               campo_ordenar = 'entrada';
               manera_orden = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none'
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'none';
               document.getElementById('igual_altura_combustible').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_combustible').style.display = 'none'
               document.getElementById('igual_codigo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_combustible').style.display = 'none';
               document.getElementById('igual_externo_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_entrada_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_entrada_combustible').style.display = 'none';
               document.getElementById('igual_entrada_combustible').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_combustible').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_combustible').style.display = 'inline-block';
               document.getElementById('igual_altura_combustible').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'elemento' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none';
               document.getElementById('igual_codigo_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'inline-block';
               document.getElementById('igual_codigo_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_externo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none'
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'ASC';
         }
         else if( orden_externo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none';
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'inline-block';
               document.getElementById('igual_externo_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'diametroext1';
               manera_orden = 'DESC';
         }
         else if( orden_interno_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none'
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'ASC';
         }
         else if( orden_interno_descendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none'
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'inline-block';
               document.getElementById('igual_interno_elemento').style.display = 'none';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'inline-block';
               campo_ordenar = 'diametroint1';
               manera_orden = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none'
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'none';
               document.getElementById('igual_altura_elemento').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_elemento').style.display = 'none'
               document.getElementById('igual_codigo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_elemento').style.display = 'none';
               document.getElementById('igual_externo_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_interno_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_interno_elemento').style.display = 'none';
               document.getElementById('igual_interno_elemento').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_elemento').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_elemento').style.display = 'inline-block';
               document.getElementById('igual_altura_elemento').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'panel' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none';
               document.getElementById('igual_codigo_panel').style.display = 'none';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'inline-block';
               document.getElementById('igual_codigo_panel').style.display = 'none';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_largo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none'
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'none';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'largo';
               manera_orden = 'ASC';
         }
         else if( orden_largo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none';
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'inline-block';
               document.getElementById('igual_largo_panel').style.display = 'none';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'largo';
               manera_orden = 'DESC';
         }
         else if( orden_ancho_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none'
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'none';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'ancho';
               manera_orden = 'ASC';
         }
         else if( orden_ancho_descendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none'
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'inline-block';
               document.getElementById('igual_ancho_panel').style.display = 'none';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'inline-block';
               campo_ordenar = 'ancho';
               manera_orden = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none'
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'none';
               document.getElementById('igual_altura_panel').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_panel').style.display = 'none'
               document.getElementById('igual_codigo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_largo_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_largo_panel').style.display = 'none';
               document.getElementById('igual_largo_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_ancho_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_ancho_panel').style.display = 'none';
               document.getElementById('igual_ancho_panel').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_panel').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_panel').style.display = 'inline-block';
               document.getElementById('igual_altura_panel').style.display = 'none';
               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'sellado' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none';
               document.getElementById('igual_codigo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'inline-block';
               document.getElementById('igual_codigo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_externo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'diametroext';
               manera_orden = 'ASC';
         }
         else if( orden_externo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none';
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'inline-block';
               document.getElementById('igual_externo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'diametroext';
               manera_orden = 'DESC';
         }
         else if( orden_tipo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'tipo';
               manera_orden = 'ASC';

               segundo_campo = true;
               campo_ordenar2 = 'diametroint';
               manera_orden2 = 'ASC';
         }
         else if( orden_tipo_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'inline-block';
               document.getElementById('igual_tipo_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'tipo';
               manera_orden = 'DESC';

               segundo_campo = true;
               campo_ordenar2 = 'diametroint';
               manera_orden2 = 'DESC';
         }
         else if( orden_altura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'altura';
               manera_orden = 'ASC';
         }
         else if( orden_altura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'inline-block';
               document.getElementById('igual_altura_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'altura';
               manera_orden = 'DESC';
         }
         else if( orden_empacadura_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'diametroempint';
               manera_orden = 'ASC';
         }
         else if( orden_empacadura_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'inline-block';
               document.getElementById('igual_empacadura_sellado').style.display = 'none';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'inline-block';

               campo_ordenar = 'diametroempext';
               manera_orden = 'DESC';
         }
         else if( orden_valvulas_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'none';
               document.getElementById('igual_valvulas_sellado').style.display = 'none';

               campo_ordenar = 'valvulaal';
               manera_orden = 'ASC';
         }
         else if( orden_valvulas_descendente == 1){
               document.getElementById('imagen_descendente_codigo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_sellado').style.display = 'none'
               document.getElementById('igual_codigo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_externo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_externo_sellado').style.display = 'none';
               document.getElementById('igual_externo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_tipo_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_tipo_sellado').style.display = 'none';
               document.getElementById('igual_tipo_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_altura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_altura_sellado').style.display = 'none';
               document.getElementById('igual_altura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_empacadura_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_empacadura_sellado').style.display = 'none';
               document.getElementById('igual_empacadura_sellado').style.display = 'inline-block';

               document.getElementById('imagen_descendente_valvulas_sellado').style.display = 'none';
               document.getElementById('imagen_ascendente_valvulas_sellado').style.display = 'inline-block';
               document.getElementById('igual_valvulas_sellado').style.display = 'none';

               campo_ordenar = 'valvulaal';
               manera_orden = 'DESC';
         }
      }
      else if( especificacion == 'fluidos' ){
         if( orden_codigo_ascendente == 1){
               document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'inline-block';
               document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'none';
               document.getElementById('igual_codigo_fluidos').style.display = 'none';

               document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'none';
               document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'none';
               document.getElementById('igual_detalle1_fluidos').style.display = 'inline-block';

               document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'none';
               document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'none';
               document.getElementById('igual_detalle2_fluidos').style.display = 'inline-block';

               campo_ordenar = 'codigo';
               manera_orden = 'ASC';
         }
         else if( orden_codigo_descendente == 1 ){
               document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'none';
               document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'inline-block';
               document.getElementById('igual_codigo_fluidos').style.display = 'none';

               document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'none';
               document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'none';
               document.getElementById('igual_detalle1_fluidos').style.display = 'inline-block';

               document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'none';
               document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'none';
               document.getElementById('igual_detalle2_fluidos').style.display = 'inline-block';

               campo_ordenar = 'codigo';
               manera_orden = 'DESC';
         }
         else if( orden_detalle1_ascendente == 1 ){
            document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'none';
            document.getElementById('igual_codigo_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'inline-block';
            document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('igual_detalle1_fluidos').style.display = 'none';

            document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('igual_detalle2_fluidos').style.display = 'inline-block';

            campo_ordenar = 'detalle1';
            manera_orden = 'ASC';
         }
         else if( orden_detalle1_descendente == 1 ){
            document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'none';
            document.getElementById('igual_codigo_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'inline-block';
            document.getElementById('igual_detalle1_fluidos').style.display = 'none';

            document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('igual_detalle2_fluidos').style.display = 'inline-block';

            campo_ordenar = 'detalle1';
            manera_orden = 'DESC';
         }
         else if( orden_detalle2_ascendente == 1 ){
            document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'none';
            document.getElementById('igual_codigo_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('igual_detalle1_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'inline-block';
            document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('igual_detalle2_fluidos').style.display = 'none';

            campo_ordenar = 'detalle2';
            manera_orden = 'ASC';
         }
         else if( orden_detalle2_descendente == 1 ){
            document.getElementById('imagen_descendente_codigo_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_codigo_fluidos').style.display = 'none';
            document.getElementById('igual_codigo_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle1_fluidos').style.display = 'none';
            document.getElementById('igual_detalle1_fluidos').style.display = 'inline-block';

            document.getElementById('imagen_descendente_detalle2_fluidos').style.display = 'none';
            document.getElementById('imagen_ascendente_detalle2_fluidos').style.display = 'inline-block';
            document.getElementById('igual_detalle2_fluidos').style.display = 'none';

            campo_ordenar = 'detalle2';
            manera_orden = 'DESC';
         }
      }

      var especificacion = document.getElementById('claseFiltros').value;
      let registros = document.getElementById("registros_especificaciones").value;
      let campo = document.getElementById("valorBuscar").value;

      formData = new FormData();
      formData.append('especificacion', especificacion);
      formData.append('registros', registros);

      formData.append('campo_ordenar', campo_ordenar);
      formData.append('manera_orden', manera_orden);

      if( typeof segundo_campo !== "undefined" ){
         if( segundo_campo = true ){
            formData.append('campo_ordenar2', campo_ordenar2);
            formData.append('manera_orden2', manera_orden2);
            segundo_campo = false;
         }
      }

      formData.append('campo', campo);
      formData.append('pagina', 1);

      if( especificacion == 'elemento' || especificacion == 'fluidos' || especificacion == 'panel' ||  especificacion == 'sellado' ){
         var tipo = document.getElementById('tipoFiltros').value;
         formData.append('tipo', tipo);
      }
      if( especificacion == 'sellado' ){
         var rosca = document.getElementById('roscaFiltro').value;
         formData.append('rosca', rosca);
      }



      fetch('./../ajax_busquedas/ajax_tabla.php', { 
         method: 'POST',
         body: formData,
      })
      .then( response => response.json() )
      .then( 
         data => {
            document.getElementById('resultados').innerHTML = data.data;
            document.getElementById('resultados').style.display = "contents";
            document.getElementById('buscar_especificaciones').style.display = "block";
            document.getElementById('valorBuscar').style.display = "block";
            document.getElementById('registros_div').style.display = "block";
            if( data.totalFiltro != data.totalRegistros ){
               document.getElementById('totalResultado_especificaciones').innerHTML = '<p>Mostrando ' + data.totalFiltro + ' de ' + data.totalRegistros + ' registros</p>';
            }
            else {
               document.getElementById('totalResultado_especificaciones').innerHTML = "";
            }
            document.getElementById('navegacion_especificaciones').innerHTML = data.paginacion;
         }
      );
   }
</script>