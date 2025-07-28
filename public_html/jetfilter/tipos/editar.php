<?php     
    if( !isset( $_REQUEST['id'] ) ){
        header("location: tipo.php");
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

    $seleccionado = $base_de_datos->prepare("SELECT tipo, categoria_id
                                                FROM tipos 
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                             
    $seleccionado->execute();
    $tipo = $seleccionado->fetch(PDO::FETCH_ASSOC);

    $categoria = $base_de_datos->prepare("SELECT categorias.id, categoria, productos.nombre, clase
                                                    FROM categorias
                                                    JOIN productos ON productos.id = categorias.producto_id
                                                    WHERE ( categorias.id = :categoria_id ) and ( categorias.deleted_at is null )");
    $categoria->bindParam(':categoria_id', $tipo['categoria_id'], PDO::PARAM_INT); 
    $categoria->execute();
    $categoria_seleccionado = $categoria->fetch(PDO::FETCH_ASSOC);

    $tipo = $tipo['tipo'];

    $categorias_totales = $base_de_datos->prepare("SELECT categorias.id, categoria, productos.nombre, clase
                                                    FROM categorias
                                                    JOIN productos ON productos.id = categorias.producto_id
                                                    WHERE ( categorias.deleted_at is null )");
    $categorias_totales->execute();
    while( $fila = $categorias_totales->fetch(PDO::FETCH_ASSOC) ){
        $categorias []= $fila;
    }

    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();
    alerta_ya_existe();
?>
    <title>Actualizar Datos del Tipo</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Datos del Tipo</p>
            </div>
            <div class="tex_tablas">
                <a href="./tipo.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="update.php" id="form-especificacion" method="POST" class="form_login">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="tabla_edi">
                        <input type="hidden" value="<?php echo $id ?>" name="id" >
                        <tr>
                            <th> Tipo: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $tipo ?>" name="tipo" id="tipo" required/>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria: </th>
                            <td>
                                <select name="categoria" id="categoria" class="selectdis" required>
                                    <option value="" selected disabled>Categoria --- Clasificación --- Linea</option>
                                    <?php 
                                        foreach( $categorias as $cate ){
                                            if( $cate['id'] == $categoria_seleccionado['id'] ){
                                                ?>
                                                    <option value="<?php echo $cate['id']; ?>" selected><?php echo $cate['categoria'] . ' --- ' . $cate['nombre'] . ' --- ' . $cate['clase']; ?></option>
                                                <?php
                                            }
                                            else {
                                                ?>
                                                    <option value="<?php echo $cate['id']; ?>"><?php echo $cate['categoria'] . ' --- ' . $cate['nombre'] . ' --- ' . $cate['clase']; ?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>  
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="editar_tipo" class="boton" />
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