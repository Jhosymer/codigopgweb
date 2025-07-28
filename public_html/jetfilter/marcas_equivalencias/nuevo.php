<?php 
   include_once('./../arriba_carpeta.php');
   include_once('./../conexion/conexion.php');

   $aplicacion_marca = $base_de_datos->prepare("SELECT id,marca, mostrar FROM equivalencia_marca");
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

   <title>Nueva Marca - Equivalencia</title>
   <section class="about_tabla_espe">
      <section class="about-if_tabla_esp">
         <div class="tex_tablas">
            <p>Crear Marca - Equivalencia</p>
         </div>
         <div class="tex_tablas">
            <a href="./marcas_equivalencias.php" class="boton">Atras</a>
         </div>
      </section>

      <secttion class="es_tabla">
         <div class="tex_tablas">
            <form action="crear_marca_eq.php" method="POST" class="formulario_aire" enctype="multipart/form-data">
               <table class="tabla_edi">
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <input type="text" id="marca" name="marca" required>
                        </td>
                    </tr>
                    <tr>
                       <th>Mostrar:</th>
                  <td>
                     <select id="mostrar" name="mostrar"  class= "selectdis" required>
                        <option value="1" >SI</option>
                        <option value="0" >NO</option>
                     </select>
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