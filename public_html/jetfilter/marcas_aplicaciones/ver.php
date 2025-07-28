<?php

    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_POST['ver'];

    //Consulta para conseguir los datos de la marca
    $seleccionado = $base_de_datos->prepare("SELECT * FROM aplicacion_marca
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $marca_seleccionado = $seleccionado->fetch(PDO::FETCH_ASSOC);                                    
?>

<title>Ver Marca</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Marcas</p>
        </div>
        <div class="tex_tablas">
            <a href="./marcas_aplicaciones.php" class="boton">Atras</a>
        </div>
    </section>

    <secttion class="es_tabla">
        <div class="tex_tablas">
                <table class="tabla_ver">
                    <tr>
                        <th>Id:</th>
                        <td>
                            <?php
                                echo $marca_seleccionado['id'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <?php
                                echo $marca_seleccionado['marca'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Sincronizado:</th>
                        <td>
                            <?php
                                echo $marca_seleccionado['sincronizado'];
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