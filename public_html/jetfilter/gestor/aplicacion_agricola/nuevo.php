<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Nueva - Aplicacion Agricola";
     require_once("../index/header.php");
    include_once('../../../config/conexion.php');

    //Consulta Marcas de la Aplicación;
    $aplicacion_marca = $base_de_datos->prepare("SELECT id, marca FROM aplicacion_marca where ( deleted_at is null ) ORDER BY marca ASC");
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
            <h1 class="titulo">Crear  Aplicación Agrícola</h1>
        </div>

       <a href="./aplicacion_agricola.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_aplicacionagricola.php" method="POST" id="nuevo-formulario" class="formulario_aire" enctype="multipart/form-data">
                 <table class="table table-striped table-hover table-responsive dataTable">
                     <tr><th colspan="2" style="text-align: center;">Filtro</th></tr>
                    <tr>
                        <th>Código WEB:</th>
                        <td>
                            <input type="text" id="codigo_web" name="codigo_filtro" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        <?php
                            if( isset( $_SESSION["codigo_inexistente"] ) )
                            {
                                echo "<div>
                                    <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                    <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                </div>";
                                unset($_SESSION["codigo_inexistente"]);
                            }
                        ?>
                        </td>
                    </tr>
                    <tr><th colspan="2" style="text-align: center;">Aplicación</th></tr>
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <select name="marca" class="form-select" id="marca" required>
                            <option value="" disabled selected>Seleccionar Marca</option>
                                <?php foreach($resultado_marca as $resul){ ?>
                                    <option value="<?php echo $resul['id'];?>"><?php echo $resul['marca'];?></option>
                                <?php }?>
                                <option value="Otro">AGREGAR MARCA</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Vehículo:</th>
                        <td>
                            <select name="vehiculo" class="form-select" id="vehiculo" required>
                                <option value="" disabled selected>Seleccionar Vehículo</option>
                                <option value="Otro" >Agregar Vehículo</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Aplicación:</th>
                        <td>
                            <select name="aplicacion" class="form-select" id="marca" required>
                                <option value="" selected disabled>¿Cuál es la aplicación?</option>
                                <option value="Aceite primario">Aceite Primario</option>
                                <option value="Aceite secundario">Aceite Secundario</option>
                                <option value="Aire primario">Aire Primario</option>
                                <option value="Aire primario opcional">Aire Primario Opcional</option>
                                <option value="Aire secundario">Aire Secundario</option>
                                <option value="Aire secundario opcional">Aire Secundario Opcional</option>
                                <option value="Cabina">Cabina</option>
                                <option value="Combustible primario">Combustible Primario</option>
                                <option value="Combustible secundario">Combustible Secundario</option>
                                <option value="Hidraulico">Hidráulico</option>
                                <option value="Hidraulico primario">Hidráulico Primario</option>
                                <option value="Hidraulico secundario">Hidráulico Secundario</option>
                                <option value="Kit de Filtros">Kit de Filtros</option>
                                <option value="Otros 1">Otros 1</option>
                                <option value="Otros 2">Otros 2</option>
                                <option value="Refrigerante">Refrigerante</option>
                                <option value="Separador">Separador</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Detalle:</th>
                        <td>
                            <input type="text" class="form-control" id="detalle" name="detalle">
                        </td>
                    </tr>
                    <tr> 
                        <td class="b_td"> 
                            <input class="btn-icon me-4" type="submit" value="Enviar" name="nueva_aplicacion_agricola">
                        </td>
                        <td class="b_td">  
                            <input class="btn-icon me-4" type="reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- Formulario para Subir Marca -->
  <div class="col-12 col-md-6">
           <div class=" tex_tablas marca_nueva mb-5">
                    <form action="./subir_marca.php" method="POST" id="form-marca" class="formulario_aire">
                         <table class="table table-striped table-hover table-responsive dataTable">
                            <tr><th colspan="2" style="text-align: center;">Crear Marca</th></tr>
                            <tr>
                                <td><b>Marca: </b></td>
                                <td><input type="text" class="form-control" id="marca" name="marca" required></td>
                            </tr>        
                            <tr> 
                                <td class="b_td"> 
                                    <input class="btn-icon me-4" type="submit" value="Enviar" name="marca_nueva">
                                </td>
                                <td class="b_td">  
                                    <input class="btn-icon me-4" type="reset">
                                </td>
                            </tr> 
                        </table> 
                    </form>
                </div>
            <!-- Formulario para Subir Vehiculo -->
            <div class="tex_tablas marca_nueva">
                <form action="./subir_vehiculo.php" method="POST" id="form-vehiculo" class="formulario_aire">
                     <table class="table table-striped table-hover table-responsive dataTable">
                        <tr><th colspan="2" style="text-align: center;">Crear Vehículo</th></tr>
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
                            <th>Modelo: </th>
                            <td><input type="text" class="form-control"  id="modelo" name="modelo" required></td>
                        </tr>    
                        <tr>
                            <th>Motor: </th>
                            <td><input type="text" class="form-control" id="motor" name="motor" required></td>
                        </tr>  
                        <tr>
                            <th>Cilindrada: </th>
                            <td><input type="text" class="form-control" id="cilindrada" name="cilindrada" required></td>
                        </tr>  
                        <tr>
                            <th>Año: </th>
                            <td><input type="text" class="form-control" id="ano" name="ano" required></td>
                        </tr>  
                        <tr> 
                            <td class="b_td"> 
                                <input class="btn-icon me-4" type="submit" value="Enviar" name="vehiculo_nuevo">
                            </td>
                            <td class="b_td">  
                                <input class="btn-icon me-4" type="reset">
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

<script src="./../js/aplicacion/cambioMarca.js"></script> <!-- Evento Que se aplica cuando el select de marca cambie -->
<script src="./../js/aplicacion/cambioVehiculo.js"></script>  <!-- Evento Que se aplica cuando el select de vehículo cambie -->