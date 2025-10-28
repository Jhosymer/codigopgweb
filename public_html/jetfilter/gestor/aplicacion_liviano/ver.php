<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Ver - Aplicación Liviano";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');


    $id = $_REQUEST['ver'];

    //Busca los detalles de la aplicación
    $seleccionado = $base_de_datos->prepare("SELECT id_vehiculo, id_marca, aplicacion, id_codigo, codigo, detalle, sincronizado
                                                    FROM aplicacion 
                                                    WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    $aplicacion_liviano = $seleccionado->fetch(PDO::FETCH_ASSOC);

    $id_vehiculo = $aplicacion_liviano['id_vehiculo'];
    $id_marca = $aplicacion_liviano['id_marca'];

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
 <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver Aplicación Liviano</h1>
        </div>
        <a href="./aplicacion_liviano.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
     <div class="card h-100 mb-5">
            <table class="table table-striped table-hover table-responsive dataTable">
                <tr>
                    <th>Tipo:</th>
                    <td>
                        Liviano
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
                            echo $aplicacion_liviano['aplicacion'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Código:</th>
                    <td>
                        <?php
                            echo $aplicacion_liviano['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Detalle:</th>
                    <td>
                        <?php
                            echo $aplicacion_liviano['detalle'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $aplicacion_liviano['sincronizado'];
                        ?>
                    </td>
                </tr>
            </table>
                       </div>
</div>

<?php
    include('../index/footer.php');
?>