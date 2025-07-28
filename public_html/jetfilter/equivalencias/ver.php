<?php
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');


    $id = $_REQUEST['id'];

    //Consulta para seleccionar datos de la equivalencia
    $equivalencia = $base_de_datos->prepare("SELECT * FROM filtro_equivalencia WHERE id = :id") or die('Error al ver');
    $equivalencia->bindParam(':id', $id, PDO::PARAM_INT);
    $equivalencia->setFetchMode(PDO::FETCH_ASSOC);
    $equivalencia->execute();
    while ( $fila = $equivalencia->fetch() ) {
        $equivalencia_seleccionada []= $fila;
    }

?>
<title>Ver Equivalencia</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Equivalencias</p>
        </div>
        <div class="tex_tablas">
            <a href="./equivalencias.php" class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($equivalencia_seleccionada as $equivalencia){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>ID Código:</th>
                    <td>
                        <?php
                            echo $equivalencia['id_codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código:</th>
                    <td>
                        <?php
                            echo $equivalencia['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código Busqueda:</th>
                    <td>
                        <?php
                            echo $equivalencia['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Marca:</th>
                    <td>
                        <?php
                            echo $equivalencia['marca'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código de la Equivalencia:</th>
                    <td>
                        <?php
                            echo $equivalencia['codigo_marca'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Código de Busqueda de la Equivalencia:</th>
                    <td>
                        <?php
                            echo $equivalencia['codigo_marca_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Sincronizado:</th>
                   <td>
                        <?php
                            echo $equivalencia['sincronizado'];
                        ?>
                    </td>
                </tr>
            </table>
            <?php
                }
            ?>
        </div>
    </section>
</section>

<?php
    include('./../abajo_carpeta.html');
?>