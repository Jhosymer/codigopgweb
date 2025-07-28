<?php  
    if( !isset($_REQUEST['id']) ){
        header("location: vehiculos.php");
    }

    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

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

    <title>Actualizar Datos de Vehiculos</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Datos de Vehiculos</p>
            </div>
            <div class="tex_tablas">
                <a href="./vehiculos.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <!-- Inicio del Formulario para actualizar los vehículos -->
                <form action="update.php" method="POST" class="form_login" >
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="sincronizado" value="<?php echo $sincronizado; ?>">
                    <table class="tabla_edi">
                        <input type="hidden" value="<?php echo $id ?>" name="id">
                        <tr>
                            <th>Marca:</th>
                            <td>
                                <select name="marca" class="selectdis" id="marca" required>
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
                                <input class="edi_inp" type="text" value="<?php echo $modelo ?>" name="modelo" id="modelo" required/>
                            </td>
                        </tr>
                        <tr>
                            <th>Motor: </th>
                            <td>
                                <input  class="edi_inp" type="text" value="<?php echo $motor ?>" name="motor" id="motor" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Cilindrada: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $cilindrada ?>" name="cilindrada" id="cilindrada" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Año: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $ano ?>" name="ano" id="ano" required/>
                            </td>  
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="btnimg" class="boton" />
                            </td>
                        </tr>
                    </table> 
                </div>
            </div>  
        </form>
        <!-- Fin del Formulario para actualizar los vehículos -->
    </section>
</section>

    <?php
        include('./../abajo_carpeta.html')
    ?>