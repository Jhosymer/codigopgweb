<?php
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_REQUEST['ver'];

    //Consulta para buscar el vehículo
    $seleccionado = $base_de_datos->prepare("SELECT * FROM aplicacion_vehiculo
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id',$id,PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $vehiculo_seleccionado = $seleccionado->fetch(PDO::FETCH_ASSOC);

    //Consulta para buscar la marca
    $seleccionado = $base_de_datos->prepare("SELECT marca FROM aplicacion_marca
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $vehiculo_seleccionado['id_marca'], PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $marca_seleccionada = $seleccionado->fetch(PDO::FETCH_ASSOC);                                        
?>

<title>Ver Vehiculo</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Vehiculos</p>
        </div>
        <div class="tex_tablas">
            <a href="./vehiculos.php" class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <table class="tabla_ver">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Marca Seleccionada:</th>
                    <td>
                        <?php
                            echo $marca_seleccionada['marca'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Modelo:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['modelo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Motor:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['motor'];
                        ?>
                    </td>
                </tr>
                <tr>
                <th>Cilindrada:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['cilindrada'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Año:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['ano'];
                        ?>
                    </td> 
                </tr>    
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['sincronizado'];
                        ?>
                    </td>
            </tr>
            </table>
        </div>
    </section>
</section>

<?php
    include('./../abajo_carpeta.html');
?>