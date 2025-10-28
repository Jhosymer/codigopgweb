<?php
    $loc = "../../../";
     $locj = "../../";
     $title = "Editar imagenes - Fluidos";
     require_once("../index/header.php");
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: ./espec_fluidos.php");
    }
    $colocar_eliminar = 1;
    
    //Se incluyen los archivos necesarios
    include_once('../../../config/conexion.php');

    $id = $_REQUEST['id']; //Guarda el id del filtro a editar

    //Consulta que devuelve las imagenes y el código del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3, id_codigo, codigo
                                                FROM espec_fluidos 
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

         
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Actualizar Imagenes de Productos de Fluido</h1>
        </div>

       <a href="./espec_fluidos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>
<div class="container mb-2 mt-5">
    <div class="card h-100 mb-5">
            <div class="card-header card-header-web">
                <h3 class="Panton-Bold mb-0 ms-2"> <?php echo $codigo ?></h3>
            </div>
            <div class="card-body">
        <form action="update_imagenes.php" method="POST" class="form_login" id="editar_imagenes" enctype="multipart/form-data">
            <input type="hidden" name="id_codigo" value="<?php echo $imagenes['id_codigo']; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="imagen_eliminada" id="imagen_eliminada" value="">
            <?php 
                include_once('./../componentes/galeria_editar.php');
            ?>
            <tr>
                <td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="btn-icon me-4" /></td>
            </tr>
        
            </form>
    
        </div>
        </div>
             </div>
<?php 
    include('../index/footer.php');
?>

<script src="./../js/comprobar_imagen.js"></script> <!-- Función que comprueba que la imagen es del tamaño adecuado -->
<script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
<script src="./../js/eliminar_imagenes.js"></script> <!-- Enviará la acción para eliminar la imagen -->