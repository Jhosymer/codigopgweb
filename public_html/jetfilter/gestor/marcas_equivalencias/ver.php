<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Ver | Marcas - Equivalencia";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    $id = $_POST['ver'];

    //Consulta para conseguir los datos de la marca
    $seleccionado = $base_de_datos->prepare("SELECT * FROM equivalencia_marca
                                            WHERE id = :id") or die('Error al ver');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                  
    $seleccionado->execute();
    $marca_seleccionado = $seleccionado->fetch(PDO::FETCH_ASSOC);                                    
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver Aplicación Liviano</h1>
        </div>
        <a href="./marcas_equivalencias.php"  class="btn-icon me-4" >Atrás</a>
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
                                echo $marca_seleccionado['id'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Marca:</th>
                        <td>
                            <?php
                                echo $marca_seleccionado['marca'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                       <th>Mostrar:</th>
                        <td>
                            <?php
                                echo ($marca_seleccionado['mostrar'] == 1) ? "SI" : "NO";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Sincronizado:</th>
                        <td>
                            <?php
                                echo $marca_seleccionado['sincronizado'];
                            ?>
                        </td>
                    </tr>
                </table>
   </div>
</div>

<?php
    include('../index/footer.php');
?>