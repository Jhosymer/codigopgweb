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

   include_once('./../alertas/alerta_error.php');
   alerta_error();

?>

   <title>Nuevo Clasificación Producto</title>
   <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Crear Clasificación Producto</p>
            </div>
            <div class="tex_tablas">
                <a href="./productos.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="./crear.php" method="POST" class="formulario_aire">
                    <table class="tabla_edi">
                        <tr>
                            <th>Clasificación:</th>
                            <td>
                                <input type="producto" id="producto" name="producto" required>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td">
                                <input class="boton" type="submit" value="Enviar" name="boton">
                            </td>
                            <td class="b_td">
                                <input class="boton" type="reset">
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
