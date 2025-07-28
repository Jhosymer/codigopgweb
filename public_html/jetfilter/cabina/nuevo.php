<?php
   //Se incluyen los archivos necesarios
   include_once('./../arriba_carpeta.php');
   include_once('./../conexion/conexion.php');

   //Se incluyen las alertas a verificar
   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_codigo_existe.php');

   //Si hubo algún error al hacer una edición del filtro o al eliminar la imagen
   alerta_error();
   //Si el código de filtro que se intento subir ya existe
   alerta_codigo_existe();

   
   /* Se incluye una funcion que busca todos las categorias de la clase */
   include_once('./../componentes/consultaCategoriasDeUnProducto.php');
   //Primer Parametro el Nombre de la Clase
   //Segundo Parametro la variable de conexion a la base de datos
   $categorias = categoriaDeUnProducto('Cabina', $base_de_datos);
?>

   <title>Nuevo Producto de Cabina</title>
   <section class="about_tabla_espe">
      <section class="about-if_tabla_esp">
         <div class="tex_tablas">
            <p>Crear Producto y Especificaciones de Cabina</p>
         </div>
         <div class="tex_tablas">
            <a href="./espec_cabina.php" class="boton">Atras</a>
         </div>
      </section>

      <section class="es_tabla">
         <div class="tex_tablas">
            <form action="crear_cabina.php" method="POST" class="formulario_aire" enctype="multipart/form-data">
               <table class="tabla_edi">
                  <tr>
                     <th>Código:</th>
                     <td>
                        <input type="text" id="codigo" name="codigo" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Categoría:</th>
                     <td>
                        <select name="categoria" id="categoria" class="selectdis" >
                           <option value="" selected >¿Qué Categoría es?</option>
                           <?php
                              foreach( $categorias as $categoria ){
                                 ?>
                                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria']  . ' -- ' . $categoria['nombre']; ?></option>
                                 <?php
                              }
                           ?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Tipo:</th>
                     <td>
                        <select name="tipo" id="tipo" class="selectdis" required>
                           <option value="N/D" selected>N/D</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Filtración: (Opcional)</th>
                     <td>
                        <select name="filtracion" id="filtracion" class="selectdis" >
                           <option value="" selected >¿Cuál es la filtración?</option>
                           <option value="Estándar">Estándar</option>
                           <option value="Carbón Activo">Carbón Activo</option>
                           
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Largo:</th>
                     <td>
                        <input type="number" id="largo" name="largo" min="0" step=".01" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Ancho:</th>
                     <td>
                        <input type="number" id="ancho" name="ancho" min="0" step=".01" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Altura:</th>
                     <td>
                        <input type="number" id="altura" name="altura" min="0" step=".01" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Unidades de Empaque: (Opcional)</th>
                     <td>
                        <input type="number" id="und_empaque" name="und_empaque" min="0" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 1: (Opcional)</th>
                     <td>
                        <input type="text" id="detalle1" name="detalle1" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 2: (Opcional)</th>
                     <td>
                        <input type="text" id="detalle2" name="detalle2" >
                     </td>
                  </tr>

                  <tr> 
                     <td class="b_td">
                        <input class="boton" type="submit" value="Enviar" name="cabina">
                     </td>
                     <td class="b_td">
                        <input class="boton" type="reset">
                     </td>
                  </tr>
               </table>
            </div>
            <?php
               include_once('./../componentes/galeria_nuevo.php'); //Galeria que permite agregar nueva imagen
            ?>
         </form>
      </section>
   </section>

<?php 
    include_once('./../abajo_carpeta.html');
?>

<script src="./../js/comprobar_imagen.js"></script>  <!-- Función que comprueba que la imagen es del tamaño adecuado -->
   <script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
   <script src="./../js/funciones/cambiar_categoria.js" ></script> <!-- Detecta que hubo un cambio de categoria y trae los tipos de esa categoria -->
   <script src="./../js/funciones/evento_tecla_empaque.js" ></script> <!-- Detecta que se pulso una tecla en unidades de empaque -->
   <script src="./../js/funciones/filterInteger.js"></script> <!-- Funcion para verificar que el valor ingresado es entero -->
   <script src="./../js/funciones/filter.js"></script> <!-- Funcion para verificar que el valor ingresado es entero positivo -->