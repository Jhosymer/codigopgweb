<?php 
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: ./espec_aireindustrial.php");
    }
    $colocar_eliminar = 1;
    
    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_REQUEST['id']; //Guarda el id del filtro a editar

    //Consulta que devuelve las imagenes y el código del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3, id_codigo, codigo
                                                FROM espec_aireindustrial 
                                                WHERE id = :id ") or die('Error al eliminar'); 
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);

    $codigo = $imagenes['codigo'];

    //Se incluyen las alertas a verificar
    include_once('./../alertas/imagenes_eliminada.php');
    include_once('./../alertas/alerta_error.php');

    //Si hubo algún error al hacer una edición del filtro o al eliminar la imagen
    alerta_error();
    //Si se eliminó la imagen correctamente
    alerta_imagenes_eliminada("La imagen ha sido eliminada");
?>
    <title>Actualizar Imagenes de Productos de Aire Industrial</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Actualizar Imagenes de Productos de Aire Industrial</p>
        </div>
        <div class="tex_tablas">
            <a href="./espec_aireindustrial.php" class="boton">Atras</a>
        </div>
    </section>

    <section class="editar_pro">
        <h1 style="text-align: center; margin-top: 1.25em;"><?php echo $codigo; ?></h1>
        <form action="update_imagenes.php" method="POST" class="form_login" id="editar_imagenes" enctype="multipart/form-data">
            <input type="hidden" name="id_codigo" value="<?php echo $imagenes['id_codigo']; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="imagen_eliminada" id="imagen_eliminada" value="">
            <?php 
                include_once('./../componentes/galeria_editar.php'); //Componente de las Imagenes
            ?>
            <tr>
                <td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="boton1" /></td>
            </tr>
        
            </form>
        </section>
    </section>
<?php 
    include('./../abajo_carpeta.html');
?>

<script src="./../js/comprobar_imagen.js"></script> <!-- Función que comprueba que la imagen es del tamaño adecuado -->
<script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
<script src="./../js/eliminar_imagenes.js"></script> <!-- Enviará la acción para eliminar la imagen -->