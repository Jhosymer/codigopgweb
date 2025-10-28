<?php 
     $loc = "../../../../";
     $locj = "../../../";
     $title = "Ver - Distribuidores Detalles";
    try{
        $url_arriba_carpeta = './../../index/header.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('Sucedio un Error');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('Sucedio un Error');
        </script>";
    }

    try{
        $url_base_datos = './../../../../config/conexion.php';
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

$seleccionado = $base_de_datos->prepare("SELECT *
                                        FROM distribuidoras
                                        WHERE id = :id") or die('Error al ver');
$seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
$seleccionado->setFetchMode(PDO::FETCH_ASSOC);
$seleccionado->execute();
while ($fila = $seleccionado->fetch()) {
    $distribuidora []= $fila;
}

?>

          
 <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Ver - Distribuidores Detalles</h1>
        </div>
        <a href="./espec_distribuidor.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
     <div class="card h-100 mb-5">
            
            <?php
                foreach($distribuidora as $dis){
            ?>
            <table class="table table-striped table-hover table-responsive dataTable">
                <tr>
                    <th>Id:</th>
                    <td>
                        <?php
                            echo $dis['id'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td>
                        <?php
                            echo $dis['nombre'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Correo:</th>
                    <td>
                        <?php
                            echo $dis['email'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Correo 2:</th>
                    <td>
                        <?php
                            if( $dis['email2'] != null ){
                                echo $dis['email2'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>País:</th>
                    <td>
                        <?php
                            echo $dis['pais'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Telefono:</th>
                    <td>
                        <?php
                            echo $dis['telefono'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Telefono 2:</th>
                    <td>
                        <?php
                            if( $dis['telefono2'] != null ){
                                echo $dis['telefono2'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Calificación:</th>
                    <td>
                        <?php
                            echo $dis['calificacion'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Facebook:</th>
                    <td>
                        <?php
                            if( $dis['facebook'] != null ){
                                echo $dis['facebook'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Twitter:</th>
                    <td>
                        <?php
                            if( $dis['twitter'] != null ){
                                echo $dis['twitter'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Instagram:</th>
                    <td>
                        <?php
                            if( $dis['instagram'] != null ){
                                echo $dis['instagram'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
                        ?>
                    </td>
                </tr>
                 <tr>
                    <th>Video Instagram:</th>
                    <td>
                        <?php
                            if( $dis['video_instagram'] != null ){
                                echo $dis['video_instagram'];
                            }
                            else {
                                ?>
                                    No Tiene
                                <?php 
                            }
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
    include('./../../index/footer.php');
?>