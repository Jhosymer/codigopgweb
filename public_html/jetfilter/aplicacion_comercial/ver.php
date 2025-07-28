<?php

    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');


    $id = $_REQUEST['ver'];

    //Busca los detalles de la aplicación
    $seleccionado = $base_de_datos->prepare("SELECT id_vehiculo, id_marca, aplicacion, id_codigo, codigo, detalle, sincronizado
                                                    FROM aplicacion 
                                                    WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    $aplicacion_comercial = $seleccionado->fetch(PDO::FETCH_ASSOC);

    $id_vehiculo = $aplicacion_comercial['id_vehiculo'];
    $id_marca = $aplicacion_comercial['id_marca'];

    //Consulta para buscar el vehiculo de la aplicación
    $vehiculo = $base_de_datos->prepare("SELECT modelo, motor FROM aplicacion_vehiculo WHERE id = :id_vehiculo");
    $vehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
    $vehiculo->execute();
    $row_vehiculo = $vehiculo->fetch(PDO::FETCH_ASSOC);

    //Consulta para buscar la marca de la aplicación
    $marca = $base_de_datos->prepare("SELECT marca FROM aplicacion_marca WHERE id = :id_marca");
    $marca->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
    $marca->execute();
    $row_marca = $marca->fetch(PDO::FETCH_ASSOC);

?>
<title>Ver Aplicación Comercial</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Aplicación Comercial</p>
        </div>
        <div class="tex_tablas">
            <a href="./aplicacion_comercial.php" class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <table class="tabla_ver">
                <tr>
                    <th>Tipo:</th>
                    <td>
                        Comercial
                    </td>
                </tr>
                <tr>
                    <th>Vehiculo:</th>
                    <td>
                        <?php
                            echo $row_vehiculo['modelo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Motor:</th>
                    <td>
                        <?php
                            echo $row_vehiculo['motor'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Marca:</th>
                    <td>
                        <?php
                            echo $row_marca['marca'];
                        ?>
                    </td>
                </tr>
                <tr>
                <th>Aplicación:</th>
                    <td>
                        <?php
                            echo $aplicacion_comercial['aplicacion'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código:</th>
                    <td>
                        <?php
                            echo $aplicacion_comercial['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Detalle:</th>
                    <td>
                        <?php
                            echo $aplicacion_comercial['detalle'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $aplicacion_comercial['sincronizado'];
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