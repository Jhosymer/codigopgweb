<?php 
    if( !isset( $_REQUEST['id'] ) ){
        header("location: categorias.php");
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

    $clases = ['Aire Automotriz', 'Aire Industrial', 'Combustible en Linea', 'Elemento', 'Panel', 'Sellado', 'Fluidos', 'cabina'];

    $seleccionado = $base_de_datos->prepare("SELECT id, categoria, clase, producto_id FROM categorias 
                                                WHERE id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $categoria_seleccionada []= $fila;
    }  
    
    $seleccionado = $base_de_datos->prepare("SELECT id, nombre FROM productos");
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $productos []= $fila;
    } 
    

    foreach($categoria_seleccionada as $cate){
        $categoria = $cate['categoria'];
        $clase_seleccionada = $cate['clase'];

        $producto = $base_de_datos->prepare("SELECT id, nombre
                                                    FROM productos
                                                    WHERE id = :producto_id") or die('Error al eliminar');
        $producto->bindParam(':producto_id', $cate['producto_id'], PDO::PARAM_INT); 
        $producto->execute();
        $producto_seleccionado = $producto->fetch(PDO::FETCH_ASSOC);
    }

    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();
    alerta_ya_existe();

?>
    <title>Actualizar Datos de Categorias</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Datos de Categorias</p>
            </div>
            <div class="tex_tablas">
                <a href="./categorias.php" class="boton">Atras</a>
            </div>
        </section>

    <section class="es_tabla">
        <div class="tex_tablas">
            <form action="./update.php" method="POST" class="form_login">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table class="tabla_edi">
                    <tr>
                        <th> Categoria: </th>
                        <td>  
                            <input class="edi_inp" type="text" value="<?php echo $categoria ?>" name="categoria" id="categoria" required/>
                        </td>
                    </tr>
                    <tr>
                        <th>Clase: </th>
                        <td>
                            <select name="clase" id="clase" class="selectdis" >
                                <option value="" selected>¿Cuál es la clase?</option>
                                <?php 
                                    foreach( $clases as $clase ){
                                        if( $clase == $clase_seleccionada ){
                                            ?>
                                                <option value="<?php echo $clase; ?>" selected><?php echo $clase; ?></option>
                                            <?php 
                                        }
                                        else {
                                            ?>
                                                <option value="<?php echo $clase; ?>"><?php echo $clase; ?></option>
                                            <?php    
                                        }
                                    }
                                ?>
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <th>Clasifcación: </th>
                        <td>
                            <select name="producto" id="producto" class="selectdis" required >
                                <option value="" disabled selected>¿Cuál es el producto?</option>
                                <?php 
                                    foreach( $productos as $producto){
                                        if( $producto['id'] == $producto_seleccionado['id'] ){
                                ?>
                                            <option value="<?php echo $producto['id'] ?>" selected><?php echo $producto['nombre'] ?></option>
                                    <?php 
                                        } else {
                                    ?>
                                            <option value="<?php echo $producto['id'] ?>"><?php echo $producto['nombre'] ?></option>
                                <?php 
                                        }
                                    }
                                ?>
                                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="b_td">
                            <input type="submit" value="Guardar" name="categorias_editar" class="boton" />
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

    <script src="./../js/comprobar_imagen.js"></script>
    <script src="./../js/colocar_validacion.js"></script>
    <script src="./../js/comprobar_codigo_repetido_Editar.js"></script>