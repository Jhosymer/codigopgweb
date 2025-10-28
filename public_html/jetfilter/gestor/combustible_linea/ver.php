<?php
$loc = "../../../";
     $locj = "../../";
     $title = "Ver - Combustible en Linea";
     require_once("../index/header.php");
    //Si no existe id te redirigirá a otra ventana
    if( !isset($_REQUEST['id']) ){
        header('location: espec_combustiblelinea.php');
    }
    else {
        $id = $_REQUEST['id'];
        $id_codigo= $_REQUEST['id_codigo'];
    }

    //Se incluyen los archivos necesarios
 
    include_once('../../../config/conexion.php');

    //Consulta para buscar los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT e_c.id, e_c.codigo, e_c.codigo_buscar, e_c.tipo, f_c.filtracion, e_c.diametroext, e_c.altura, e_c.entrada, e_c.salida, f_c.und_empaque, f_c.codigo_barra, e_c.detalle1, e_c.detalle2, e_c.sincronizado FROM espec_combustiblelinea as e_c 
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

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Especificación de Combustible en Linea</h1>
        </div>
        <a href="./espec_combustiblelinea.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
            <?php
                foreach($combustible_linea as $combustible){
            ?>
            <div class="card h-100 mb-5">
            <div class="card-header card-header-web">
                <h3 class="Panton-Bold mb-0 ms-2"> <?php echo $combustible['codigo']; ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6"> <!-- Ocupa toda la fila en pantallas pequeñas y la mitad en pantallas medianas y grandes -->
             <table class="table table-striped table-hover table-responsive dataTable">
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
                    <div class="col-12 col-md-6">
    
    <?php 
        include_once('./../componentes/galeria_ver.php');
    ?>

               </div>
                </div>
            </div>
        </div>
   
</div>
<?php
    include('../index/footer.php');
?>