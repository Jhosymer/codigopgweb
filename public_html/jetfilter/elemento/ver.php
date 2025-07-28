<?php
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
    $seleccionado = $base_de_datos->prepare("SELECT id, codigo, codigo_buscar, tipo, diametroext1, diametroint1, diametroint2, altura, detalle1, detalle2, sincronizado
                                            FROM espec_elemento 
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $elemento []= $fila;
    }

    //Consulta para buscar las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_elemento 
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

<title>Ver Especificación de Elemento</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Elemento</p>
        </div>
        <div class="tex_tablas">
            <a href='./espec_elemento.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($elemento as $elem){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $elem['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo:</th>
                    <td>
                        <?php
                            echo $elem['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Codigo buscar:</th>
                    <td>
                        <?php
                            echo $elem['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $elem['tipo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diametro Exterior 1:</th>
                    <td>
                        <?php
                            echo $elem['diametroext1'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Diametro Interior 1:</th>
                    <td>
                        <?php
                            echo $elem['diametroint1'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Diametro Interior 2:</th>
                   <td>
                        <?php
                            echo $elem['diametroint2'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Altura:</th>
                    <td>
                        <?php
                            echo $elem['altura'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Detalle 1:</th>
                    <td>
                        <?php
                            echo $elem['detalle1'];
                        ?>
                    </td>
                 </tr>
                 <tr>
                    <th>Detalle 2:</th>
                    <td>
                        <?php
                            echo $elem['detalle2'];
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
                            echo $elem['sincronizado'];
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