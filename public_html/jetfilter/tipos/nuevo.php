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
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('No se pudo culminar el proceso');
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

   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_ya_existe.php');
   alerta_error();
   alerta_ya_existe();

    $sql = "SELECT id, categoria, producto_id, clase FROM categorias WHERE ( deleted_at is null )";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->execute();
    while ( $fila = $seleccionado->fetch(PDO::FETCH_ASSOC) ) {
        $categorias []= $fila;
    }
    
    $sql = "SELECT nombre FROM productos WHERE ( id = :id )";
    $seleccionado_espera = $base_de_datos->prepare($sql);
?>

   <title>Nuevo Producto de Tipo</title>
   <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Crear Tipo de Especificación</p>
            </div>
            <div class="tex_tablas">
                <a href="./tipo.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="store.php" method="POST" class="formulario_aire">
                    <table class="tabla_edi">
                        <tr>
                            <th>Tipo:</th>
                            <td>
                                <input type="text" id="tipo" name="tipo" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria:</th>
                            <td>
                                <select name="categoria" class="selectdis" required>
                                    <option value="" selected disabled>Categoria --- Clasificación --- Linea</option>
                                    <?php 
                                        foreach( $categorias as $categoria ){
                                            $seleccionado_espera->bindParam('id', $categoria['producto_id'], PDO::PARAM_INT );
                                            $seleccionado_espera->execute();
                                            $producto = $seleccionado_espera->fetch( PDO::FETCH_ASSOC );
                                            if( $categoria['clase'] != null){
                                                ?>
                                                    <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['categoria'] . ' --- ' . $producto['nombre'] . " --- " . $categoria['clase']; ?></option>
                                                <?php
                                            }
                                            else {
                                                ?>
                                                    <option value="<?php echo $categoria['id'] ?>"><?php echo $categoria['categoria'] . ' --- ' . $producto['nombre']; ?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td">
                                <input class="boton" type="submit" value="Enviar" name="tipo_enviar">
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
