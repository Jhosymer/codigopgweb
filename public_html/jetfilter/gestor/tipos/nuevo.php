<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Nueva | Tipos";
   try{
      $url_arriba_carpeta = '../index/header.php';
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
        $url_base_datos = '../../../config/conexion.php';
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


             
  
    <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Tipos</h1>
        </div>

       <a href="./tipo.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
                <form action="store.php" method="POST" class="formulario_aire">
                    <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                        <tr>
                            <th>Tipo:</th>
                            <td>
                                <input type="text" id="tipo" class="form-control" name="tipo" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria:</th>
                            <td>
                                <select name="categoria" class="form-select" required>
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
                                <input class="btn-icon" type="submit" value="Enviar" name="tipo_enviar">
                            </td>
                            <td class="b_td">
                                <input class="btn-icon" type="reset">
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
    </div>
        </div>
   
</div>

<?php 
    include_once('../index/footer.php');
?>