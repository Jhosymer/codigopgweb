<?php
    //Si no existe id te redirigirá a otra ventana
    if( !isset($_REQUEST['id']) ){
        header('location: espec_cabina.php');
    }
    else {
        $id = $_REQUEST['id'];
    }

    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_REQUEST['id'];

    //Consulta para buscar los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT id, codigo, codigo_buscar, tipo, largo, ancho, altura, detalle1, detalle2, sincronizado
                                            FROM espec_cabina
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $cabina []= $fila;
    }

    //Consulta para buscar las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_cabina
                                                    WHERE id = :id") or die('Error al eliminar'); 
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);
?>

<title>Ver Especificación de Cabina</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Cabina</p>
        </div>
        <div class="tex_tablas">
            <a href='./espec_cabina.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php

                foreach($cabina as $pan){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $pan['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo:</th>
                    <td>
                        <?php
                            echo $pan['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo buscar:</th>
                    <td>
                        <?php
                            echo $pan['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $pan['tipo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diametro Exterior 1:</th>
                    <td>
                        <?php
                            echo $pan['largo'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Diametro Exterior 2:</th>
                    <td>
                        <?php
                            echo $pan['ancho'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Altura:</th>
                    <td>
                        <?php
                            echo $pan['altura'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Detalle 1:</th>
                    <td>
                        <?php
                            echo $pan['detalle1'];
                        ?>
                    </td>
                 </tr>
                 <tr>
                    <th>Detalle 2:</th>
                    <td>
                        <?php
                            echo $pan['detalle2'];
                        ?>
                    </td>
                </tr>    
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $pan['sincronizado'];
                        ?>
                   </td>
                </tr>
            </table>
            <?php
                }
            ?>
    </div>
    
    <?php 
        include_once('./../componentes/galeria_ver.php');
    ?>

    </section>
</section>
<?php
    include('./../abajo_carpeta.html');
?>