<?php
    try{
        $url_arriba_carpeta = './../arriba_carpeta.php';
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
        $url_base_datos = './../conexion/conexion.php';
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

$codigo = $_POST['ver'];

$seleccionado = $base_de_datos->prepare("SELECT * FROM especificaciones 
                                        WHERE codigo = $codigo") or die('Error al ver');
$seleccionado->execute();
while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
    $especificacion []= $fila;
}

$seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                FROM especificaciones 
                                                WHERE codigo = $codigo") or die('Error al eliminar'); 
$seleccionado_imagen->execute();
$imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);

?>
<title>Ver Especificación de Especificación</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Especificación de Especificación</p>
        </div>
        <div class="tex_tablas">
            <a onclick="atras();" class="boton">Atras</a>
        </div>
    </section>

    <secttion class="es_tabla">
        <div class="tex_tablas">
            <?php
                foreach($especificacion as $espec){
            ?>
            <table class="tabla_ver">
                <tr>
                    <th>Codigo:</th>
                    <td>
                        <?php
                            echo $espec['codigo'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Nombre:</th>
                    <td>
                        <?php
                            echo $espec['Itemname'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Itemcode:</th>
                    <td>
                        <?php
                            echo $espec['itemcode'];
                        ?>
                    </td>
                </tr>
                <tr>
                   <th>Codigo Buscar:</th>
                    <td>
                        <?php
                            echo $espec['codigobuscar'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 1:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp1'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 1:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc1'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 2:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp2'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 2:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc2'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 3:</th>
                    <td>
                        <?php
                            echo $espec['U_escp3'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 3:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc3'];
                        ?>
                    </td>
                </tr>    
                <tr>
                    <th>Especificación 4:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp4'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 4:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc4'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 5:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp5'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 5:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc5'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 6:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp6'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 6:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc6'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 7:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp7'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 7:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc7'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 8:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp8'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 8:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc8'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 9:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp4'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 9:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc9'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 10:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp10'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Descripción 10:</th>
                    <td>
                        <?php
                            echo $espec['U_Desc10'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Especificación 4:</th>
                    <td>
                        <?php
                            echo $espec['U_Esp4'];
                        ?>
                    </td> 
                </tr>
                <tr>
                    <th>Linea:</th>
                    <td>
                        <?php
                            echo $espec['linea'];
                        ?>
                    </td>
                </tr>

            </table>
        <?php
            }
        ?>
</div>
 
<div class="galeria"></br>
    
   
    <div class="contenedor-imagenes">
        <?php 
                for($i = 0; $i < 4; $i++){
                    if( $imagenes[$i] != "" ){
                        if( file_exists("./../../images/fichas-filtros/web/$imagenes[$i].jpg") ){
                            ?>
                                <div class="imag">
                                    <img src="./../../images/fichas-filtros/web/<?php echo $imagenes[$i] . ".jpg"; ?>" class="imagen" data="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg"></img>
                                </div>
                            <?php 
                        }
                        else {
                            ?>
                                <div class="imag">
                                    <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen"></img>
                                </div>
                            <?php 
                        }
                    }
                    else{
                        ?>
                            <div class="imag">
                                <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen"></img>
                            </div>
                        <?php 
                    }
                }
            ?>
    </div>
</div>


</section>
</section>
<?php
    include('./../abajo_carpeta.html');
?>