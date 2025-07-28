<?php
    include_once('./../arriba_carpeta.php');

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
<style>

</style>
<title>Generador de Codigo Qr</title>
<script src="./../js/sweetAlerta.js"></script>
      <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
            <h3 class="catalogo">Generador de Codigo Qr</h3>
            </div>
            <div class="tex_tablas">
                <a href="../especificaciones.php" class="boton">Atras</a>
            </div>
    </section>

    <div class="about_tabla_edi">
       <section class="es_tabla">
            <div class="tex_tablas">
                <table> 
                    <tr>
                        <td>
                        <form action="generar.php" id="form-especificacion" method="POST" class="form_login" enctype="multipart/form-data">
                        <input type="text" class="textbox inline" name="codigo" id="codigo" size="30" placeholder="Filtro">

                        </td>
                        <td>
                        <input type="submit" value="Generar QR" name="btnimg" class="boton" />
                        </form>
                        </td>
                    </tr>


                </table>
            </div>

            <div class="imag">
            <table> 
                <tr>
                  <td>

                  <?php if((isset($_GET['codigo'])) && (isset($_GET['clase']))) {
                        $codigo = $_GET['codigo'];
                        $clase= $_GET['clase']; 
                        
                        $codigo_url = str_replace(" ","%20",$codigo);

                        echo '<h3 class="catalogo">'. $codigo.'</h3>';
       // Llamando a la libreria PHPQRCODE
       include('libreria/phpqrcode/qrlib.php'); 

             // Ingresamos el contenido de nuestro Código QR
               $contenido = "https://webfiltros.com/filtro/filtro.php?codigo=$codigo_url&clase=$clase&cod=1";

// Exportamos una imagen llamado resultado.png que contendra el valor de la avriable $content
QRcode::png($contenido,"libreria/resultado.png",QR_ECLEVEL_L,10,2);

     ?><div class='img_qr'>
                   <img src='libreria/resultado.png?t=<?php echo  $rann?>' class="qr_img"/>
                   </div>
                   </td>
                   <td> 

                   
                   <button type="button" onclick="descargarQR('<?php echo $codigo; ?>')" class="boton">Descargar</button>


                   </td>
                    <?php    
                        } else { ?>

                    <div class='img_qr'>
                    <img src="./../../images/fichas-filtros/web/no-img.jpg" width="100" height="100" class="img_qr" /> 
                    </div>
                    <?php 
                    } ?>

                  </td>
                   <td> 

                   </td>
                </tr> 
            </table>        
                    

            

            </div>
        
        

        </section>
    </div>

    </section>

    
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

        include("./../abajo_carpeta.html");
    ?>

