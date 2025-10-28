<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Nueva | Categoria";
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
        $conexion = '../../../config/conexion.php';
        if ( !file_exists( $conexion ) ){
            throw new Exception ('Hubo un error');
        }
        else {
            include_once($conexion);
        }
    }
    catch(Exception $e){
        ?>
            <script>
                alert( '<?php echo $e->getMessage() ?>' );
            </script>
        <?php
    }

   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_ya_existe.php');
   alerta_error();
   alerta_ya_existe();

    $sql = "SELECT id, nombre FROM productos";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->execute();
    while ( $fila = $seleccionado->fetch(PDO::FETCH_ASSOC) ) {
        $productos []= $fila;
    }   

?>

   
    <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Clasificación Producto</h1>
        </div>

       <a href="./categorias.php"  class="btn-icon me-4" >Atrás</a>
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
                            <th>Categoria:</th>
                            <td>
                                <input type="text" class="form-control" id="categoria" name="categoria" required>
                            </td>
                        </tr>
                        <tr>
                            <th>Clase:</th>
                            <td>
                                <select name="clase" class="form-select">
                                    <option value="" selected>¿Cual es la clase?</option>
                                    <option value="Aire Automotriz">Aire Automotriz</option>
                                    <option value="Aire Industrial">Aire Industrial</option>
                                    <option value="Combustible en Linea">Combustible en Linea</option>
                                    <option value="Elemento">Elemento</option>
                                    <option value="Panel">Panel</option>
                                    <option value="Sellado">Sellado</option>
                                    <option value="Fluidos">Fluidos</option>
                                       <option value="cabina">Cabina</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Clasifcación:</th>
                            <td>
                                <select name="producto" class="form-select" required>
                                    <option value="" selected>¿Cual es el producto?</option>
                                    <?php 
                                        foreach( $productos as $producto){
                                    ?>
                                        <option value="<?php echo $producto['id'] ?>" ><?php echo $producto['nombre']; ?></option>
                                    <?php 
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td">
                                <input class="btn-icon" type="submit" value="Enviar" name="categoria_enviar">
                            </td>
                        </tr>
                    </table>
            
            </form>
    </div>
        </div>
   
</div>

<?php 
    include_once('../index/footer.php');
?>
