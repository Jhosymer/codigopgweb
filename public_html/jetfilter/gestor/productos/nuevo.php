<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Nueva | Clasificación Producto";
   try{
      $url_arriba_carpeta = '../index/header.php';
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

   include_once('./../alertas/alerta_error.php');
   alerta_error();

?>

    <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Clasificación Producto</h1>
        </div>

       <a href="./productos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
                <form action="./crear.php" method="POST" class="formulario_aire">
                    <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                        <tr>
                            <th>Clasificación:</th>
                            <td>
                                <input type="producto" class="form-control" id="producto" name="producto" required>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td">
                                <input class="btn-icon" type="submit" value="Enviar" name="boton">
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
    include_once('../index/footer.php');
?>