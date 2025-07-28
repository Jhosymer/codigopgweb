<?php     
    if( !isset( $_REQUEST['id'] ) ){
        header("location: productos.php");
    }

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

    $seleccionado = $base_de_datos->prepare("SELECT nombre
                                                FROM productos 
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                             
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $prodctos []= $fila;
    } 

    foreach($prodctos as $pro){
        $nombre = $pro['nombre'];
    }

    $clases = array(
        'aireautomotriz'  => 'Aire Automotriz',
        'aireindustrial' => 'Aire Industrial',
        'combustiblelinea' => 'Combustible en Linea',
        'elemento' => 'Elemento',
        'panel' => 'Panel',
        'sellado' => 'Sellado',
    );

    include_once('./../alertas/alerta_error.php');
    alerta_error();
?>
    <title>Actualizar Datos del Producto</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar del Clasificación Producto</p>
            </div>
            <div class="tex_tablas">
                <a href="./productos.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="update.php" id="form-especificacion" method="POST" class="form_login">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="tabla_edi">
                        <input type="hidden" value="<?php echo $id ?>" name="id" >
                        <tr>
                            <th>Clasificación: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $nombre ?>" name="nombre" id="nombre" required/>
                            </td>
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="btnimg" class="boton" />
                            </td>
                        </tr>
                    </table> 
                </div>
            </form>
    </section>
</section>

    <?php
        include('./../abajo_carpeta.html')
    ?>