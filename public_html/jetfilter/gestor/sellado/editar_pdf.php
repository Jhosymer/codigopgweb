<?php
    $loc = "../../../";
     $locj = "../../";
     $title = "Actualizar Informacion Adicional - sellado";
     require_once("../index/header.php");
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: ./espec_sellado.php");
    }
    $colocar_eliminar = 1;
    
    //Se incluyen los archivos necesarios

  include_once('../../../config/conexion.php');

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
    $id_codigo = $documentos['id'];


    //Se incluyen las alertas a verificar
    include_once('./../alertas/pdf_eliminada.php');
    include_once('./../alertas/alerta_error.php');

   
?>


           
      <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Actualizar Informacion Adicional</h1>
        </div>
        <a href="./espec_sellado.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    

<div class="stats-progress progress mb-5" style="height:3px"></div>
    <div class="container mb-2 mt-5">

 <div class="card h-100 mb-5">
        <div class="card-header card-header-web">
           <h3 class="Panton-Bold mb-0 ms-2"><?php echo $codigo; ?></h3>
             </div>
         <div class="card-body">
        <form action="update_pdf.php" method="POST" class="form_login" id="editar_pdf" enctype="multipart/form-data">
             <input type="hidden" name="id_codigo" value="<?php echo $id_codigo; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <input type="hidden" name="pdf_eliminada" id="pdf_eliminada" value="">
            <?php 
                include_once('./../componentes/pdf_editar.php'); //Componente de los Documentos
            ?>
            <div class="mt-auto">
           <input type="submit" value="Guardar" name="btnpdf" class="btn-icon me-4" />
              </div>
        
            </form>
        </div>
                </div>
<?php 
    include('../index/footer.php');
?>



<script src="./../js/ed_pdf.js"></script> 
<script src="./../js/eliminar_pdf.js"></script> <!-- Enviará la acción para eliminar imagen -->



