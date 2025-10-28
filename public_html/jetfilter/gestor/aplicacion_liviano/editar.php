<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar - Aplicación Liviano";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    //Id de la aplicación a editar
    $id = $_REQUEST['id'];

    //Consulta de datos de la aplicación
    $busqueda = $base_de_datos->prepare("SELECT a.codigo, a.id_marca, a.id_vehiculo, a_v.modelo, a_v.motor, a.aplicacion, a.detalle FROM aplicacion as a
                                                                        JOIN aplicacion_vehiculo as a_v ON  a_v.id = a.id_vehiculo
                                                                        WHERE a.id = :id") or die('Error al eliminar');
    $busqueda->bindParam(':id',$id,PDO::PARAM_INT);
    $busqueda->setFetchMode(PDO::FETCH_ASSOC); 
    $busqueda->execute(); 
    while ($fila = $busqueda->fetch()) {
        $filtro_seleccionado []= $fila;
    } 

    //Consulta De Todas las Marcas de la Aplicación
    $aplicacion_marca = $base_de_datos->prepare("SELECT id, marca FROM aplicacion_marca where ( deleted_at is null ) ORDER BY marca ASC");
    $aplicacion_marca->execute();
    while ($fila = $aplicacion_marca->fetch(PDO::FETCH_ASSOC)) {
        $resultado_marca []= $fila;
    }   

    //Consulta de todas las aplicaciones de Liviano
    $seleccionado = $base_de_datos->prepare("SELECT aplicacion FROM aplicacion 
                                                WHERE ( deleted_at is null ) and ( id_tipo = 4 )
                                                GROUP BY aplicacion
                                                ORDER BY aplicacion");
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $aplicaciones_existentes []= $fila;
    }

    //Datos del Filtro se separan del arreglo y se colocan en variables individuales
    $nombre_vehiculo = [];
    foreach($filtro_seleccionado as $filtro){
        $codigo = $filtro['codigo'];
        $marca = $filtro['id_marca'];
        $vehiculo = $filtro['id_vehiculo'];
        $aplicacion = $filtro['aplicacion'];
        $detalle = $filtro['detalle'];
    }

    //Consulta para datos de todos los vehiculos de la marca
    $aplicacion_vehiculo = $base_de_datos->prepare("SELECT id, modelo, motor FROM aplicacion_vehiculo
                                                                    WHERE id_marca = :id_marca
                                                                    ORDER BY modelo ASC");
    $aplicacion_vehiculo->bindParam(':id_marca', $marca, PDO::PARAM_INT);
    $aplicacion_vehiculo->execute();
    while ($fila = $aplicacion_vehiculo->fetch(PDO::FETCH_ASSOC)) {
        $resultado_vehiculo []= $fila;
    } 

    //Consulta de los datos del vehículo de la aplicación que está actualmente seleccionada
    $vehiculo_seleccionado = $base_de_datos->prepare("SELECT id, modelo, motor FROM aplicacion_vehiculo
                                                                    WHERE id = :id
                                                                    ORDER BY modelo ASC");
    $vehiculo_seleccionado->bindParam(':id', $vehiculo, PDO::PARAM_INT);
    $vehiculo_seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $vehiculo_seleccionado->execute();
    while ($fila = $vehiculo_seleccionado->fetch() ) {
        $vehiculo_select []= $fila;
    }

    //Alertas
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    //Llamado de las alertas, en caso de cumplirse. Se mostrarán
    alerta_nuevo();
    alerta_error();
    alerta_ya_existe();
?>


           
         <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Aplicación Liviano</h1>
        </div>

       <a href="./aplicacion_liviano.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

    <div class="card h-100 mb-5">
        <div class="card-body">
           <div class="row">
               <div class="col-12 col-md-6">
            <form action="update.php" method="POST" class="form_login" id="editar-formulario">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
               <table class="table table-striped table-hover table-responsive dataTable">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                        <tr><th colspan="2" style="text-align: center;">Filtro</th></tr>
                        <tr>
                            <th>Código WEB: </th>
                            <td>  
                                <input class="form-select" type="text" value="<?php echo $codigo ?>" id="codigo_web" name="codigo" id="codigo" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <?php
                                    //En caso, de codigo inexistente se mostrará una alerta
                                    if( isset($_SESSION["codigo_inexistente"]) )
                                    {
                                        echo "<div>
                                            <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                            <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                        </div>";
                                        //Borra la variable de session, para que se quite en caso de recargar
                                        unset($_SESSION["codigo_inexistente"]);
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr><th colspan="2" style="text-align: center;">Aplicación Liviano</th></tr>
                        <tr>
                            <th>Marca: </th>
                            <td>
                                <select name="marca" class="form-select" id="marca">
                                    <?php 
                                        //Compara todas las marcas con la que tiene la aplicación
                                        foreach($resultado_marca as $resul){ 
                                            //Cuando Haya coincidencia esa marca se seleccionara automaticamente
                                            if($resul['id'] == $marca){
                                    ?>
                                            <option value="<?php echo $resul['id'];?>" selected><?php echo $resul['marca'];?></option>
                                        <?php 
                                            } 
                                            //En caso de no haber, se colocará en el select. Pero, no estara como selected
                                            else {
                                        ?>
                                            <option value="<?php echo $resul['id'];?>"><?php echo $resul['marca'];?></option>
                                    <?php 
                                            }
                                        }
                                    ?>
                                    <!-- Opción para agregar nueva marca -->
                                    <option value="Otro">AGREGAR MARCA</option>
                                </select>
                            </td> 
                        </tr>
                        <tr>
                            <th>Vehículo: </th>
                            <td>
                                <select name="vehiculo" id="vehiculo" class="form-select">
                                    <?php 
                                        //Compara todas los vehículos con la que tiene la aplicación
                                        foreach($resultado_vehiculo as $resul_vehiculo){
                                            //Cuando Haya coincidencia ese vehículo se seleccionara automaticamente
                                            if( $vehiculo ==  $resul_vehiculo['id']){
                                                ?>
                                                    <option value="<?php echo $vehiculo; ?>" selected><?php echo $vehiculo_select[0]['modelo'] . " --- " . $vehiculo_select[0]['motor']; ?></option>
                                                <?php
                                            }
                                            //En caso de no haber, se colocará en el select. Pero, no estara como selected
                                            else {
                                                ?>
                                                    <option value="<?php echo $resul_vehiculo['id']; ?>">
                                                        <?php echo $resul_vehiculo['modelo'] . " --- " . $resul_vehiculo['motor']; ?>
                                                    </option>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <option value="Otro">Agregar Vehículo</option>
                                </select>
                            </td> 
                        </tr>
                        <tr>
                            <th>Aplicación: </th>
                            <td>
                                <select name="aplicacion" class="form-select" id="marca">
                                    <?php 
                                        //También hace la comparación con todas las aplicaciones
                                        foreach($aplicaciones_existentes as $apl){
                                            if($apl['aplicacion'] == $aplicacion){
                                            ?>
                                                <option value="<?php echo $apl['aplicacion'];?>" selected><?php echo $apl['aplicacion'];?></option>
                                            <?php
                                            }
                                            ?>
                                                <option value="<?php echo $apl['aplicacion'];?>"><?php echo $apl['aplicacion'];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </td>  
                        </tr>
                        <tr>
                            <th>Detalle: </th>
                            <td>
                                <input class="form-select" type="text" value="<?php echo $detalle ?>" name="detalle" id="detalle" />
                            </td>  
                        </tr>
                        <tr ><td class="b_td"><input type="submit" value="Editar" id="aplicacion_comercial" name="aplicacion_comercial" class="btn-icon me-4" /></td></tr>
                    </table> 
        </form>

      </div>

        <!-- Formulario para Subir Marca -->
                <div class="col-12 col-md-6">
                            <div class="tex_tablas marca_nueva  mb-5">
                                <form action="./subir_marca_editar.php" method="POST" id="form-marca" class="formulario_aire">
                                    <input type="hidden" name="id" value="<?php echo $id ?>" >
                                    <table class="table table-striped table-hover table-responsive dataTable">
                                        <tr><th colspan="2" style="text-align: center;">Crear Marca</th></tr>
                                        <tr>
                                            <th>Marca: </th>
                                            <td><input type="text" class="form-select" id="marca" name="marca"></td>
                                        </tr>         
                                        <tr> 
                                            <td class="b_td"> <input class="btn-icon me-4" type="submit" value="Enviar" name="marca_nueva_editar"></td>
                                            <td class="b_td">  <input class="btn-icon me-4" type="reset"></td>
                                        </tr> 
                                    </table> 
                                </form>
                            </div>

                            <!-- Formulario para Subir nuevos vehículos -->
                            <div class="tex_tablas marca_nueva">
                                <form action="./subir_vehiculo_editar.php" method="POST" id="form-vehiculo" class="formulario_aire">
                                    <input type="hidden" name="id" value="<?php echo $id ?>" >
                                    <table class="table table-striped table-hover table-responsive dataTable">
                                        <tr><th colspan="2" style="text-align: center;">Crear Vehiculo</th></tr>
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
                                            <td><input type="text" class="form-select" id="modelo" name="modelo" required></td>
                                        </tr>    
                                        <tr>
                                            <th>Motor:</th>
                                            <td><input type="text"  class="form-select" id="motor" name="motor" required></td>
                                        </tr>  
                                        <tr>
                                            <th>Cilindrada:</th>
                                            <td><input type="text" class="form-select" id="cilindrada" name="cilindrada" required></td>
                                        </tr>  
                                        <tr>
                                            <th>Año:</th>
                                            <td><input type="text" class="form-select" id="ano" name="ano" required></td>
                                        </tr>  
                                        <tr> 
                                            <td class="b_td"> 
                                                <input class="btn-icon me-4" type="submit" value="Enviar" name="vehiculo_nuevo_editar">
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