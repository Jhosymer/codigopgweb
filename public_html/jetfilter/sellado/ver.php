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
    $seleccionado = $base_de_datos->prepare("SELECT id_codigo, codigo, codigo_buscar, tipo, diametroext, diametroint, altura, diametroempext, diametroempint, espesoremp, valvulaal, valvulaad, detalle1, detalle2, sincronizado 
                                            FROM espec_sellado 
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $sellado []= $fila;
    }

    //Consulta para buscar las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                    FROM espec_sellado
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

<title>Ver Especificación de Sellado</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Sellado   </p>
        </div>
        <div class="tex_tablas">
            <a href='espec_sellado' class="boton"> Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($sellado as $sell){
            ?>
            <table class="tabla_ver">
           
                <tr>
                    <th>Código:</th>
                    <td>
                        <?php
                            echo $sell['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código Buscar:</th>
                    <td>
                        <?php
                            echo $sell['codigo_buscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $sell['tipo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diámetro Exterior:</th>
                    <td>
                        <?php
                            echo $sell['diametroext'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Diámetro Interior:</th>
                    <td>
                        <?php
                            echo $sell['diametroint'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Altura:</th>
                    <td>
                        <?php
                            echo $sell['altura'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Diámetro Emp Exterior:</th>
                   <td>
                        <?php
                            echo $sell['diametroempext'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Diámetro Emp Interior:</th>
                    <td>
                        <?php
                            echo $sell['diametroempint'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Espesores emp:</th>
                    <td>
                        <?php
                            echo $sell['espesoremp'];
                        ?>
                    </td>
                 </tr>
                 <tr>
                    <th>Valvula al:</th>
                    <td>
                        <?php
                            echo $sell['valvulaal'];
                        ?>
                    </td>
                </tr>    
                <tr>
                    <th>Valvula ad:</th>
                    <td>
                        <?php
                            echo $sell['valvulaad'];
                        ?>
                   </td>
                </tr>
                <tr>
                    <th>Detalle 1:</th>
                    <td>
                        <?php
                            echo $sell['detalle1'];
                        ?>
                    </td>
                 </tr>
                 <tr>
                    <th>Detalle 2:</th>
                    <td>
                        <?php
                            echo $sell['detalle2'];
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
                            echo $sell['sincronizado'];
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