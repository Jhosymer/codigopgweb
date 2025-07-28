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
<title>Ver Tipo</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Ver Tipo</p>
        </div>
        <div class="tex_tablas">
            <a href='./tipo.php' class="boton">Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <?php

                foreach($tipo as $tip){
            ?>
            <table class="tabla_ver">
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
    </section>
</section>
<?php
    include('./../abajo_carpeta.html');
?>