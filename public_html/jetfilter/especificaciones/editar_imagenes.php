<?php 
    if( !isset($_POST['editar_imagenes']) ){
        header("location: espec_aireautomotriz.php");
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

    $id = $_POST['editar_imagenes'];
    $page = $_POST['page'];
    $registros = $_POST['registros'];

    $seleccionado_imagen = $base_de_datos->query("SELECT imagen, imagen1, imagen2, imagen3 FROM especificaciones WHERE codigo = $id") or die('Error al eliminar'); 
    $imagenes = $seleccionado_imagen->fetch();
?>
<title>Actualizar Imagenes de Productos de Especiifcaciones</title>
<section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Imagenes de Productos de Especiifcaciones</p>
            </div>
            <div class="tex_tablas">
                <a onclick="atras();" class="boton">Atras</a>
            </div>
        </section>

    <secttion class="editar_pro">

    <form action="update_imagenes.php" method="POST" class="form_login" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <input type="hidden" name="registros" value="<?php echo $registros; ?>">
        <div class="galeria"></br>
            <div class="contenedor-imagenes">
            <?php 
                for($i = 0; $i < 4; $i++){
                        if( $imagenes[$i] != "" ){
                            if( file_exists("./../../images/fichas-filtros/web/$imagenes[$i].jpg") ){
                                ?>
                                    <div class="imag">
                                        <img src="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" class="imagen" data="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg"></img>
                                        <input type="file" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                        <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                            <span class="iborrainputfile">Seleccionar archivo</span>
                                        </label>
                                    </div>
                                <?php 
                            }
                            else {
                                ?>
                                    <div class="imag">
                                        <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen"></img>
                                        <input type="file" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                        <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                            <span class="iborrainputfile">Seleccionar archivo</span>
                                        </label>
                                    </div>
                                <?php 
                            }
                        }
                        else{
                            ?>
                                <div class="imag">
                                    <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen"></img>
                                    <input type="file" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                    <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <span class="iborrainputfile">Seleccionar archivo</span>
                                    </label>
                                </div>
                            <?php 
                        }
                }
            ?>
            </div>
        </div>
        <br>
    
        <tr>
            <td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="boton1" /></td>
        </tr>
        
        </form>
    </section>

</secttion>


<?php 
    include('./../abajo_carpeta.html');
?>