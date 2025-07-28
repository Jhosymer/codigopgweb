<?php 
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: ./espec_fluidos.php");
    }
    $colocar_eliminar = 1;
    
    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');
    

    $id = $_REQUEST['id']; //Guarda el id del filtro a editar
   // $codigo = $_REQUEST['codigo']; //Guarda el id del filtro a editar
    $idcodigo = $_REQUEST['idcodigo'];
    

    //Consulta que devuelve las pdf y el código del filtro
   $seleccionado_pdf = $base_de_datos->prepare("SELECT id, codigo, boletin, instalacion FROM filtro_codificacion
                                                WHERE id = :idcodigo ") or die('Error al eliminar'); 
    $seleccionado_pdf->bindParam(':idcodigo', $idcodigo, PDO::PARAM_INT);
    $seleccionado_pdf->execute();
    $documentos = $seleccionado_pdf->fetch(PDO::FETCH_BOTH);

    $codigo = $documentos['codigo'];
    $boletin = $documentos['boletin'];
    $instalacion = $documentos['instalacion'];


    //Se incluyen las alertas a verificar
    include_once('./../alertas/pdf_eliminada.php');
    include_once('./../alertas/alerta_error.php');

?>

<title>Actualizar Informacion Adicional</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Actualizar Informacion Adicional</p>
        </div>
        <div class="tex_tablas">
            <a href="./espec_fluidos.php" class="boton">Atras</a>
        </div>
    </section>

    <section class="editar_pro">
        <h1 style="text-align: center; margin-top: 1.25em;"><?php echo $codigo; ?></h1>
        <form action="update_pdf.php" method="POST" class="form_login" id="editar_imagenes" enctype="multipart/form-data">
            <input type="hidden" name="id_codigo" value="<?php echo $documentos['id']; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="imagen_eliminada" id="imagen_eliminada" value="">
            <?php 
                include_once('./../componentes/pdf_editar.php'); //Componente de los Documentos
            ?>
            <tr>
                <td class="b_td"><input type="submit" value="Guardar" name="btnpdf" class="boton1" /></td>
            </tr>
        
            </form>
        </section>
    </section>
<?php 
    include('./../abajo_carpeta.html');
?>


