<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Ver | Tipos";
    try{
        $url_arriba_carpeta = '../index/header.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('No se encontro el archivo arriba_carpeta.php');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('No se encontro el archivo arriba_carpeta.php');
        </script>";
    }

    try{
        $url_base_datos = '../../../config/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('No encontró la base de datos');
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
    }
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

$id = $_REQUEST['id'];

$seleccionado = $base_de_datos->prepare("SELECT id, tipo, categoria_id
                                        FROM tipos
                                        WHERE id = :id") or die('Error al ver');
$seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $tipo []= $fila;
}

$id_categoria = $tipo[0]['categoria_id'];

$seleccionado = $base_de_datos->prepare("SELECT categoria
                                        FROM categorias
                                        WHERE id = :id") or die('Error al ver');
$seleccionado->bindParam(':id', $id_categoria, PDO::PARAM_INT);
$seleccionado->execute();
$categoria = $seleccionado->fetch( PDO::FETCH_ASSOC );
$categoria = $categoria['categoria'];

?>

          
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver Tipos</h1>
        </div>
        <a href="./tipo.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
     <div class="card h-100 mb-5">
            <?php

                foreach($tipo as $tip){
            ?>
            <table class="table table-striped table-hover table-responsive dataTable">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $tip['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>clase:</th>
                    <td>
                        <?php
                            $categoria = ucfirst( $categoria );
                            echo $categoria;
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Tipo:</th>
                    <td>
                        <?php
                            echo $tip['tipo'];
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