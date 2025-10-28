<?php
    $loc = "../../../";
     $locj = "../../";
     $title = " Nueva | Vehiculos - Aplicaciones ";


    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

   /* Consulta para buscar todas las marcas */
    $aplicacion_marca = $base_de_datos->prepare("SELECT id, marca FROM aplicacion_marca  where ( deleted_at is null ) ORDER BY marca ASC");
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
            <h1 class="titulo">Crear  Vehiculos Aplicación</h1>
        </div>

       <a href="./vehiculos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
                <!-- Formulario Para Crear Vehículo -->
                <form action="crear_vehiculo.php" method="POST" class="formulario_aire" >
                     <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                        <tr>
                            <th>Marca:</th>
                            <td>
                                <select name="marca" class="form-select" id="marca" required>
                                    <?php 
                                        foreach($resultado_marca as $resul){ 
                                            ?>
                                                <option value="<?php echo $resul['id'];?>"><?php echo $resul['marca'];?></option>
                                            <?php 
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Modelo:</th>
                            <td>
                                <input type="text" class="form-control" id="modelo" name="modelo" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Motor:</th>
                            <td>
                                <input type="text" class="form-control" id="motor" name="motor" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Cilindrada:</th>
                            <td>
                                <input type="text" class="form-control" id="cilindrada" name="cilindrada" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Año:</th>
                            <td>
                                <input type="text" class="form-control" id="ano" name="ano" required>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td"> 
                                <input class="btn-icon"  type="submit" value="Enviar" name="vehiculos">
                            </td>
                            <td class="b_td">  
                                <input class="btn-icon"  type="reset">
                            </td>
                        </tr>
                    </table>
            </form>
            <!-- Fin del Formulario Para Crear Vehículo -->
     </div>
        </div>
   
</div>

<?php 
    include_once('../index/footer.php');
?>