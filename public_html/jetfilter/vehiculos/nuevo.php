<?php 
   include_once('./../arriba_carpeta.php');
   include_once('./../conexion/conexion.php');

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

   <title>Nuevo Vehiculo</title>
   <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Crear Vehiculo</p>
            </div>
            <div class="tex_tablas">
                <a href="./vehiculos.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <!-- Formulario Para Crear Vehículo -->
                <form action="crear_vehiculo.php" method="POST" class="formulario_aire" >
                    <table class="tabla_edi">
                        <tr>
                            <th>Marca:</th>
                            <td>
                                <select name="marca" class="selectdis" id="marca" required>
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
                                <input type="text" id="modelo" name="modelo" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Motor:</th>
                            <td>
                                <input type="text" id="motor" name="motor" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Cilindrada:</th>
                            <td>
                                <input type="text" id="cilindrada" name="cilindrada" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Año:</th>
                            <td>
                                <input type="text" id="ano" name="ano" required>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td"> 
                                <input class="boton" type="submit" value="Enviar" name="vehiculos">
                            </td>
                            <td class="b_td">  
                                <input class="boton" type="reset">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
            <!-- Fin del Formulario Para Crear Vehículo -->
        </section>
   </section>

<?php 
    include_once('./../abajo_carpeta.html');
?>