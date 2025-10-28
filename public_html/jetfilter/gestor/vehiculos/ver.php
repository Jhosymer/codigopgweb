<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Ver | Vehiculos - Aplicaciones";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    $id = $_REQUEST['ver'];

    //Consulta para buscar el vehículo
    $seleccionado = $base_de_datos->prepare("SELECT * FROM aplicacion_vehiculo
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id',$id,PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $vehiculo_seleccionado = $seleccionado->fetch(PDO::FETCH_ASSOC);

    //Consulta para buscar la marca
    $seleccionado = $base_de_datos->prepare("SELECT marca FROM aplicacion_marca
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $vehiculo_seleccionado['id_marca'], PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $marca_seleccionada = $seleccionado->fetch(PDO::FETCH_ASSOC);                                        
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver Vehiculos - Aplicaciones</h1>
        </div>
        <a href="./vehiculos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
     <div class="card h-100 mb-5">
            <table class="table table-striped table-hover table-responsive dataTable">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Marca:</th>
                    <td>
                        <?php
                            echo $marca_seleccionada['marca'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Modelo:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['modelo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Motor:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['motor'];
                        ?>
                    </td>
                </tr>
                <tr>
                <th>Cilindrada:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['cilindrada'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Año:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['ano'];
                        ?>
                    </td> 
                </tr>    
                <tr>
                    <th>Sincronizado:</th>
                    <td>
                        <?php
                            echo $vehiculo_seleccionado['sincronizado'];
                        ?>
                    </td>
            </tr>
            </table>
   </div>
</div>

<?php
    include('../index/footer.php');
?>