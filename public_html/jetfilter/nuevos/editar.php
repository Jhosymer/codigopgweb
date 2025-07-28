<?php 
    try{
        if ( !file_exists( './../arriba_carpeta.php' ) ){
            throw new Exception ('No se encontro el archivo arriba_carpeta.php');
        }
        else {
            include_once('./../arriba_carpeta.php');
        }
    }
    catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }

    try{
        if ( !file_exists( './../conexion/conexion.php' ) ){
            throw new Exception ('No se encontro el archivo conexion.php');
        }
        else {
            include_once('./../conexion/conexion.php');
        }
    }
    catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }

    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

    $sql = "SELECT codigo FROM nuevos_filtros WHERE id = :id";
    $seleccionar_filtro = $base_de_datos->prepare($sql);
    $seleccionar_filtro->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionar_filtro->execute();
    $filtro = $seleccionar_filtro->fetch(PDO::FETCH_ASSOC);

    if( isset( $_SESSION["num_imagenes"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: '¡No existen imágenes!',
                        text: 'Ese filtro no cuenta con las imagenes.',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['num_imagenes'] );
        }
        if( isset( $_SESSION["repetido"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Ya existe!',
                        text: 'Ese filtro ya existe',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['repetido'] );
        }
?>

    <title>Cambiar Productos Principales</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Cambiar Producto Principal</p>
            </div>
            <div class="tex_tablas">
                <a href="./nuevos_filtros.php" class="boton">Atras</a>
            </div>
        </section>

    <section class="about_tabla_edi">
        <div class="tex_tablas">
            <form action="update.php" method="POST" class="formulario_aire">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <table class="tabla_edi">
                    <tr>
                        <th>Codigo</th>
                        <td>
                            <input type="text" value="<?php echo $filtro['codigo'] ?>" id="codigo_filtro" name="codigo_filtro" class="codigo_filtro" required>
                        </td>
                    </tr>
                    <?php
                        if(isset($_GET["codigo_error"]) && $_GET["codigo_error"] == 'true')
                        {
                            echo "
                                <tr>
                                    <td colspan='2'>
                                        <div>
                                            <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                            <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        ?>
                    <tr> 
                        <td class="b_td"> 
                            <input class="boton" type="submit" value="Enviar" name="editar_principal">
                        </td>
                        <td class="b_td">  
                            <input class="boton" type="reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </section>
</section>

<?php 
    include('./../abajo_carpeta.html');
?>