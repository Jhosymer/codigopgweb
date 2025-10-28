<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Generador de Codigo Qr";

    include_once('../index/header.php');

    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');
    include_once('./../alertas/alerta_imagenes.php');

    alerta_error();
    alerta_nuevo('El filtro fue agregado');
    alerta_actualizado('La información del filtro fue actualizada');
    alerta_eliminado("El filtro se elimino correctamente");
    alerta_actualizado_imagenes("Las imagenes del filtro fueron actualizadas");

    ?>
  <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Generador de Codigo Qr Ficha Tecnica</h1>
        </div>
        <a href="../especificaciones.php"  class="btn-icon me-4" >Atrás</a>
    </div>

       <div class="stats-progress progress mb-5" style="height:3px"></div>

<div class="container mb-2 mt-5 ">
    <div class="card h-100 mb-5">
        <div class="card-body">
            <form action="generar.php" id="form-especificacion" method="POST" class="form_login">
                <div class="input-group mb-1" style="max-width: 300px;">
                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Codigo" aria-label="Filtro">
                    <button type="submit" name="btnimg" class="btn btn-primary btn-sm">Generar QR</button>
                </div>
            </form>


            <?php if((isset($_GET['codigo'])) && (isset($_GET['clase']))) {
                $codigo = $_GET['codigo'];
                $clase= $_GET['clase']; 
                $codigo_url = str_replace(" ","%20",$codigo);

                echo '<h3 class="Panton-Bold rojoweb mt-5 ms-4">'. $codigo.'</h3>';
                include('libreria/phpqrcode/qrlib.php'); 
                $contenido = "https://webfiltros.com/catalogo/ficha_tecnica/index.php?codigo=$codigo_url&clase=$clase&cod=1";
                QRcode::png($contenido,"libreria/resultado.png",QR_ECLEVEL_L,10,2);
            ?>
            <div class='img_qr d-flex align-items-center'>
                <img src='libreria/resultado.png?t=<?php echo $rann?>' class="qr_img ms-4 me-4" width="300" height="300" />
                <button type="button" onclick="descargarQR('<?php echo $codigo; ?>')" class="btn-icon">Descargar</button>
            </div>
            <?php } else { ?>
            <div class='img_qr mt-5'>
                <img src="./../../../images/fichas-filtros/web/no-img.jpg" width="100" height="100" class="img_qr ms-4 me-4" /> 
            </div>
            <?php } ?>
        </div>
    </div>
</div>



    
    <script>
function descargarQR(codigo) {
    var link = document.createElement('a');
    // Agregar un timestamp a la URL para evitar caché
    link.href = 'libreria/resultado.png?t=' + new Date().getTime();
    link.download = 'qr-' + codigo + '.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
<?php

        include("../index/footer.php");
    ?>

