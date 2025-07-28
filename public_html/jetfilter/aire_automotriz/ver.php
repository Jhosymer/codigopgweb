<?php
    //Si no existe id te redirigirá a otra ventana
    if( !isset($_REQUEST['id']) ){
        header('location: espec_aireautomotriz.php');
    }
    else {
        $id = $_REQUEST['id'];
        $id_codigo= $_REQUEST['id_codigo'];
    }

    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    //Consulta para buscar los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT e_a.id, e_a.codigo, e_a.codigo_buscar, e_a.tipo, f_c.filtracion, e_a.diametroext1, e_a.diametroext2, e_a.diametroint1, e_a.diametroint2, e_a.altura, f_c.und_empaque, e_a.detalle1, e_a.detalle2, e_a.sincronizado FROM espec_aireautomotriz as e_a 
                                            JOIN filtro_codificacion as f_c ON f_c.id = e_a.id_codigo 
                                            WHERE e_a.id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $aire_automotriz []= $fila;
    }

    //Consulta para buscar las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_aireautomotriz 
                                                    WHERE id = :id") or die('Error al eliminar'); 
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);
 //Consulta codigo de barra de filtro

    $sql = "SELECT * FROM filtro_codificacion  WHERE id = :id_codigo";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $barra = $seleccionado->fetch();
    $codigo_barra = $barra['codigo_barra'];
?>

<title>Ver Especificación de Aire Automotriz</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Aire Automotriz</p>
        </div>
        <div class="tex_tablas">
            <a href='./espec_aireautomotriz.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($aire_automotriz as $aire){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $aire['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo:</th>
                    <td>
                        <?php
                            echo $aire['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo Buscar:</th>
                    <td>
                        <?php
                            echo $aire['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $aire['tipo'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Filtración:</th>
                    <td>
                        <?php
                            echo $aire['filtracion'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diametro Exterior 1:</th>
                    <td>
                        <?php
                            echo $aire['diametroext1'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Diametro Exterior 2:</th>
                    <td>
                        <?php
                            echo $aire['diametroext2'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diametro Interior 1:</th>
                    <td>
                        <?php
                            echo $aire['diametroint1'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Diametro Interior 2:</th>
                   <td>
                        <?php
                            echo $aire['diametroint2'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Altura:</th>
                    <td>
                        <?php
                            echo $aire['altura'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Unidades de Empaque:</th>
                    <td>
                        <?php
                            echo $aire['und_empaque'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Detalle 1:</th>
                    <td>
                        <?php
                            echo $aire['detalle1'];
                        ?>
                    </td>
                 </tr>
                 <tr>
                    <th>Detalle 2:</th>
                    <td>
                        <?php
                            echo $aire['detalle2'];
                        ?>
                    </td>
                </tr> 
                 <tr>
                    <th>Codigo de Barra:</th>
                    <td>
                        <?php
                            echo $codigo_barra;
                        ?>
                   </td>
                </tr>   
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $aire['sincronizado'];
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