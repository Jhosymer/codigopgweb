<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Vehiculos - Aplicaciones";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    if( !isset($_REQUEST['id']) ){
        header("location: vehiculos.php");
    }

    

    $id = $_REQUEST['id'];

    //Consulta para cargar todos los vehículos
    $seleccionado = $base_de_datos->prepare("SELECT * FROM aplicacion_vehiculo
                                                WHERE id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                            
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $vehiculos []= $fila;
    }    

    //Consulta para cargar todas las marcas
    $aplicacion_marca = $base_de_datos->prepare("SELECT id, marca FROM aplicacion_marca   where ( deleted_at is null ) ORDER BY marca ASC");
    $aplicacion_marca->execute();
    while ($fila = $aplicacion_marca->fetch(PDO::FETCH_ASSOC)) {
        $resultado_marca []= $fila;
    }   

    //Los datos del vehículo se colocan en variables 
    foreach($vehiculos as $veh){
        $modelo = $veh['modelo'];
        $motor = $veh['motor'];
        $marca = $veh['id_marca'];
        $cilindrada = $veh['cilindrada'];
        $ano = $veh['ano'];
        $sincronizado = $veh['sincronizado'];
    }

    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();
?>

    
   <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Vehiculos - Aplicaciones</h1>
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
                <!-- Inicio del Formulario para actualizar los vehículos -->
                <form action="update.php" method="POST" class="form_login" >
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="sincronizado" value="<?php echo $sincronizado; ?>">
                    <table class="table table-striped table-hover table-responsive dataTable">
                        <input type="hidden" value="<?php echo $id ?>" name="id">
                        <tr>
                            <th>Marca:</th>
                            <td>
                                <select name="marca" class="form-select" id="marca" required>
                                    <?php 
                                        foreach($resultado_marca as $resul){ 
                                            if( $resul['id'] == $marca ){
                                                ?>
                                                    <option value="<?php echo $resul['id'];?>" selected><?php echo $resul['marca'];?></option>
                                                <?php 
                                            }
                                            else {
                                                ?>
                                                    <option value="<?php echo $resul['id'];?>"><?php echo $resul['marca'];?></option>
                                                <?php 
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th> Modelo: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $modelo ?>" name="modelo" id="modelo" required/>
                            </td>
                        </tr>
                        <tr>
                            <th>Motor: </th>
                            <td>
                                <input  class="form-control" type="text" value="<?php echo $motor ?>" name="motor" id="motor" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Cilindrada: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $cilindrada ?>" name="cilindrada" id="cilindrada" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Año: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $ano ?>" name="ano" id="ano" required/>
                            </td>  
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="btnimg" class="btn-icon" />
                            </td>
                        </tr>
                    </table> 
             
        </form>
                           </div>
                 </div>
                
</div>

<?php 
     include('../index/footer.php');
?>