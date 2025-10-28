<?php 
     $loc = "../../../../";
     $locj = "../../../";
     $title = "Distribuidor Nuevo";
    try{
      $url_arriba_carpeta = './../../index/header.php';
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
   
    
 <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Distribuidor Nuevo</h1>
        </div>

       <a href="espec_distribuidor.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
      <form action="./crear_distribuidor.php" method="POST" class="formulario_aire">
         <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
            <tr>
               <th>Nombre:</th>
               <td>
                  <input type="text" class="form-control" id="nombre" name="nombre" required>
               </td>
            </tr>
            <tr>
               <th>Email:</th>
               <td>
                  <input type="email" class="form-control" id="email" name="email" required>
               </td>
            </tr>
            <tr>
               <th>Email 2:</th>
               <td>
                  <input type="email" class="form-control" name="email2" id="email2">
               </td>
            </tr>
            <tr>
               <th>TLF 1</th>
               <td>
                  <input type="text" class="form-control" id="telefono" name="telefono" required>
               </td>
            </tr> 
            <tr>
               <th>TLF 2</th>
               <td>
                  <input type="text" class="form-control" id="telefono_2" name="telefono_2" >
               </td>
            </tr>
            <tr>
               <th>Calificación</th>
               <td>
                  <select name="calificacion" id="calificacion" class="form-select">
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
                  <input type="text" class="form-control" id="facebook" name="facebook" >
               </td>
            </tr>
            <tr>
               <th>Twitter</th>
               <td>
                  <input type="text" class="form-control" id="twitter" name="twitter" >
               </td>
            </tr>
            <tr>
               <th>Instagram</th>
               <td>
                  <input type="text" class="form-control" id="instagram" name="instagram" >
               </td>
            </tr>
            <tr>
               <th>Video Instagram</th>
               <td>
                  <input type="text" class="form-control" id="video_instagram" name="video_instagram" >
               </td>
            </tr>

             <tr>
               <th>Google Maps</th>
               <td>
                  <input type="text" class="form-control" id="direcmaps" name="direcmaps" >
               </td>
            </tr>
            <tr> 
               <td class="b_td"> 
                  <input class="btn-icon" type="submit" value="Enviar" name="distribuidor">
               </td>
               <td class="b_td">  
                  <input class="btn-icon" type="reset">
               </td>
            </tr>
         </table>
      </form>
     </div>
        </div>
   
</div>

<?php 
    include_once('./../../index/footer.php');
?>