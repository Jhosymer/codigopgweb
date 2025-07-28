<?php
    try{
        $url_arriba_carpeta = './../../arriba_carpeta_segundoNivel.php';
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
        $url_base_datos = './../../conexion/conexion.php';
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
<title>Ver Distribuidora</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Distribuidora</p>
        </div>
        <div class="tex_tablas">
            <a href='./espec_distribuidor.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($distribuidora as $dis){
            ?>
            <table class="tabla_ver">
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
    </section>
</section>
<?php
    include('./../../abajo_carpeta_segundoNivel.html');
?>