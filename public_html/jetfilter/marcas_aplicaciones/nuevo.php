<?php 
   include_once('./../arriba_carpeta.php');
   include_once('./../conexion/conexion.php');

   $aplicacion_marca = $base_de_datos->prepare("SELECT id,marca FROM aplicacion_marca");
   $aplicacion_marca->execute();
   while ($fila = $aplicacion_marca->fetch(PDO::FETCH_ASSOC)) {
      $resultado_marca []= $fila;
   }   
    
   //Alertas a Comprobar
   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_nuevo.php');
   include_once('./../alertas/alerta_ya_existe.php');
   alerta_error();  
   alerta_nuevo();
   alerta_ya_existe();
?>

   <title>Nueva Marca</title>
   <section class="about_tabla_espe">
      <section class="about-if_tabla_esp">
         <div class="tex_tablas">
            <p>Crear Marca</p>
         </div>
         <div class="tex_tablas">
            <a href="./marcas_aplicaciones.php" class="boton">Atras</a>
         </div>
      </section>

      <secttion class="es_tabla">
         <div class="tex_tablas">
            <form action="crear_marca.php" method="POST" class="formulario_aire" enctype="multipart/form-data">
               <table class="tabla_edi">
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <input type="text" id="marca" name="marca" required>
                        </td>
                    </tr>
                    <tr> 
                        <td class="b_td"> 
                           <input class="boton" type="submit" value="Enviar" name="marcas">
                        </td>
                        <td class="b_td">  
                           <input class="boton" type="reset">
                        </td>
                    </tr>
               </table>
            </form>
        </div>
      </section>
   </section>

<?php 
    include_once('./../abajo_carpeta.html');
?>