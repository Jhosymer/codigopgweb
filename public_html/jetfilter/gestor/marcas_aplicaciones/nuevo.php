<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Nueva | Marcas - Aplicaciones";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

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

 <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Marca  Aplicación</h1>
        </div>

       <a href="./marcas_aplicaciones.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_marca.php" method="POST" class="formulario_aire" enctype="multipart/form-data">
               <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <input type="text"  class="form-control" id="marca" name="marca" required>
                        </td>
                    </tr>
                    <tr> 
                        <td class="b_td"> 
                           <input class="btn-icon" type="submit" value="Enviar" name="marcas">
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