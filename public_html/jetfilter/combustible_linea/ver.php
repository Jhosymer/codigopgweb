<?php
    //Si no existe id te redirigirá a otra ventana
    if( !isset($_REQUEST['id']) ){
        header('location: espec_combustiblelinea.php');
    }
    else {
        $id = $_REQUEST['id'];
        $id_codigo= $_REQUEST['id_codigo'];
    }

    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    //Consulta para buscar los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT e_c.id, e_c.codigo, e_c.codigo_buscar, e_c.tipo, f_c.filtracion, e_c.diametroext, e_c.altura, e_c.entrada, e_c.salida, f_c.und_empaque, e_c.detalle1, e_c.detalle2, e_c.sincronizado FROM espec_combustiblelinea as e_c 
                                        JOIN filtro_codificacion as f_c ON f_c.id = e_c.id_codigo 
                                        WHERE e_c.id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $combustible_linea []= $fila;
    }

    //Consulta para buscar las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_combustiblelinea
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

<title>Ver Especificación de Combustible en Linea</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Combustible en Linea</p>
        </div>
        <div class="tex_tablas">
            <a href='./espec_combustiblelinea.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($combustible_linea as $combustible){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $combustible['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código:</th>
                    <td>
                        <?php
                            echo $combustible['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código Buscar:</th>
                    <td>
                        <?php
                            echo $combustible['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $combustible['tipo'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Filtración:</th>
                    <td>
                        <?php
                            if( $combustible['filtracion'] != null && $combustible['filtracion'] != '' ){
                                echo $combustible['filtracion'];
                            }
                            else {
                                echo "No definido";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diámetro Exterior:</th>
                    <td>
                        <?php
                            echo $combustible['diametroext'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Altura</th>
                    <td>
                        <?php
                            echo $combustible['altura'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Entrada</th>
                    <td>
                        <?php
                            echo $combustible['entrada'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Salida</th>
                   <td>
                        <?php
                            echo $combustible['salida'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Unidades de Empaque:</th>
                    <td>
                        <?php
                            if( $combustible['und_empaque'] != null && $combustible['und_empaque'] != '' ){
                                echo $combustible['und_empaque'];
                            }
                            else {
                                echo "No definido";
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Detalle 1:</th>
                    <td>
                        <?php
                            echo $combustible['detalle1'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Detalle 2:</th>
                    <td>
                        <?php
                            echo $combustible['detalle2'];
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
                            echo $combustible['sincronizado'];
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