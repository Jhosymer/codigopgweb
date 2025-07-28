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
        $conexion = './../conexion/conexion.php';
        if ( !file_exists( $conexion ) ){
            throw new Exception ('Hubo un error');
        }
        else {
            include_once($conexion);
        }
    }
    catch(Exception $e){
        ?>
            <script>
                alert( '<?php echo $e->getMessage() ?>' );
            </script>
        <?php
    }

   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_ya_existe.php');
   alerta_error();
   alerta_ya_existe();

    $sql = "SELECT id, nombre FROM productos";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->execute();
    while ( $fila = $seleccionado->fetch(PDO::FETCH_ASSOC) ) {
        $productos []= $fila;
    }   

?>

   <title>Nueva Categoria</title>
   <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Crear Categoria</p>
            </div>
            <div class="tex_tablas">
                <a href="./categorias.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="store.php" method="POST" class="formulario_aire">
                    <table class="tabla_edi">
                        <tr>
                            <th>Categoria:</th>
                            <td>
                                <input type="text" id="categoria" name="categoria" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Clase:</th>
                            <td>
                                <select name="clase" class="selectdis">
                                    <option value="" selected>¿Cual es la clase?</option>
                                    <option value="Aire Automotriz">Aire Automotriz</option>
                                    <option value="Aire Industrial">Aire Industrial</option>
                                    <option value="Combustible en Linea">Combustible en Linea</option>
                                    <option value="Elemento">Elemento</option>
                                    <option value="Panel">Panel</option>
                                    <option value="Sellado">Sellado</option>
                                    <option value="Fluidos">Fluidos</option>
                                       <option value="cabina">Cabina</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Clasifcación:</th>
                            <td>
                                <select name="producto" class="selectdis" required>
                                    <option value="" selected>¿Cual es el producto?</option>
                                    <?php 
                                        foreach( $productos as $producto){
                                    ?>
                                        <option value="<?php echo $producto['id'] ?>" ><?php echo $producto['nombre']; ?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td">
                                <input class="boton" type="submit" value="Enviar" name="categoria_enviar">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </section>
   </section>

<?php 
    include_once('./../abajo_carpeta.html');
?>
