<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Ver -Equivalencias";
     require_once("./../index/header.php");
    include_once('../../../config/conexion.php');


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


<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver Equivalencia</h1>
        </div>
        <a href="./equivalencias.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
     <div class="card h-100 mb-5">

            <?php
                foreach($equivalencia_seleccionada as $equivalencia){
            ?>
            <table class="table table-striped table-hover table-responsive dataTable">
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
</div>

<?php
    include('../index/footer.php');
?>