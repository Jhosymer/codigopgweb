<?php 
    try{
      $url_arriba_carpeta = './../arriba_carpeta.php';
      if ( !file_exists( $url_arriba_carpeta ) ){
          throw new Exception ('No se encontro el archivo arriba_carpeta.php');
      }
      else {
          include_once($url_arriba_carpeta);
      }
   }
   catch(Exception $e){
         echo "
         <script>
            alert('No se encontro el archivo arriba_carpeta.php');
         </script>";
   }
?>
 <title>Nuevo Distribuidor - Ecuador</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Crear Nuevo Distribuidor - Ecuador</p>
        </div>
        <div class="tex_tablas">
         <a href="espec_distribuidor.php" class="boton">Atras</a>
         </div>
    </section>

    <?php 
         if( isset($_GET["campo"]) ){
      ?>
         <script>
            Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: '¡Faltaron campos por llenar!',
               timer: 2000,
            }) .then(() => {
                  window.location.replace("nuevo.php");
            })
         </script>
      <?php
         }
         if( isset($_GET["errorBase"]) )
         {
      ?>
         <script>
            Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: 'Hubo un problema con la base de datos',
               timer: 1250,
               }) .then(() => {
                window.location.replace("nuevo.php");
            })
        </script>
      <?php
         }
      ?>
   
    <secttion class="about_tabla_edi">
        <div class="tex_tablas">
      <form action="crear_distribuidor.php" method="POST" class="formulario_aire" enctype="multipart/form-data">
         <table class="tabla_edi">
            <tr>
               <th>Nombre:</th>
               <td>
                  <input type="text" id="nombre" name="nombre" required>
               </td>
            </tr>
            <tr>
               <th>Direccion:</th>
               <td>
                  <input type="text" id="direccion" name="direccion" required>
               </td>
            </tr>
            <tr>
               <th>Email:</th>
               <td>
                  <input type="email" id="email" name="email" required>
               </td>
            </tr>
            <tr>
               <th>Estado</th>
               <td>
                  <select id="estados_ecuador" name="estado" class="selectdis" required>
                     <option value="" disabled>--Seleccione--</option>
                     <option value="Anzoategui">Anzoategui</option>
                     <option value="Apure">Apure</option>
                     <option value="Aragua">Aragua</option>
                     <option value="Barinas">Barinas</option>
                     <option value="Bolivar">Bolivar</option>
                     <option value="Carabobo">Carabobo</option>
                     <option value="Cojedes">Cojedes</option>
                     <option value="Falcon">Falcon</option>
                     <option value="Guarico">Guarico</option>
                     <option value="Lara">Lara</option>
                     <option value="Merida">Merida</option>
                     <option value="Miranda">Miranda</option>
                     <option value="Monagas">Monagas</option>
                     <option value="Nueva_esparta">Nueva Esparta</option>
                     <option value="Portuguesa">Portuguesa</option>
                     <option value="Sucre">Sucre</option>
                     <option value="Tachira">Tachira</option>
                     <option value="Trujillo">Trujillo</option>
                     <option value="Zulia">Zulia</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Ciudad</th>
               <td>
                  <input type="text" id="ciudad" name="ciudad" required>
               </td>
            </tr>
            <tr>
               <th>TLF</th>
               <td>
                  <input type="text" id="telefono" name="telefono" required>
               </td>
            <tr> 
               <td class="b_td"> <input class="boton" type="submit" value="Enviar" name="distribuidor"></td>
               <td class="b_td">  <input class="boton" type="reset"></td>
            </tr>
         </table>
      </form>
      </div>
   </section>
</section>

<?php 
    include_once('./../abajo_carpeta.html');
?>