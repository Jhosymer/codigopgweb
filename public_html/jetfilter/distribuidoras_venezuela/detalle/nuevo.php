<?php 
    try{
      $url_arriba_carpeta = './../../arriba_carpeta_segundoNivel.php';
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
 <title>Nuevo Distribuidor - Venezuela</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Crear Nuevo Distribuidor - Venezuela</p>
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
   
    <section class="about_tabla_edi">
        <div class="tex_tablas">
      <form action="./crear_distribuidor.php" method="POST" class="formulario_aire">
         <table class="tabla_edi">
            <tr>
               <th>Nombre:</th>
               <td>
                  <input type="text" id="nombre" name="nombre" required>
               </td>
            </tr>
            <tr>
               <th>Email:</th>
               <td>
                  <input type="email" id="email" name="email" required>
               </td>
            </tr>
            <tr>
               <th>Email 2:</th>
               <td>
                  <input type="email" name="email2">
               </td>
            </tr>
            <tr>
               <th>TLF 1</th>
               <td>
                  <input type="text" id="telefono" name="telefono" required>
               </td>
            </tr> 
            <tr>
               <th>TLF 2</th>
               <td>
                  <input type="text" id="telefono" name="telefono_2" >
               </td>
            </tr>
            <tr>
               <th>Calificación</th>
               <td>
                  <select name="calificacion" id="calificacion" class="selectdis">
                     <option value="1">1 Estrella</option>
                     <option value="2">2 Estrellas</option>
                     <option value="3">3 Estrellas</option>
                     <option value="4">4 Estrellas</option>
                     <option value="5">5 Estrellas</option>
                     <option value="6">6 Estrellas</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Facebook</th>
               <td>
                  <input type="text" id="facebook" name="facebook" >
               </td>
            </tr>
            <tr>
               <th>Twitter</th>
               <td>
                  <input type="text" id="twitter" name="twitter" >
               </td>
            </tr>
            <tr>
               <th>Instagram</th>
               <td>
                  <input type="text" id="instagram" name="instagram" >
               </td>
            </tr>
            <tr>
               <th>Video Instagram</th>
               <td>
                  <input type="text" id="video_instagram" name="video_instagram" >
               </td>
            </tr>

             <tr>
               <th>Google Maps</th>
               <td>
                  <input type="text" id="direcmaps" name="direcmaps" >
               </td>
            </tr>
            <tr> 
               <td class="b_td"> 
                  <input class="boton" type="submit" value="Enviar" name="distribuidor">
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
    include_once('./../../abajo_carpeta_segundoNivel.html');
?>