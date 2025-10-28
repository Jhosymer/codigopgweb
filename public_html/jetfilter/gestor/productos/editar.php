<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Clasificación Producto";   
    if( !isset( $_REQUEST['id'] ) ){
        header("location: productos.php");
    }

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
            throw new Exception ('No encontró la base de datos');
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
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

    $id = $_REQUEST['id'];

    $seleccionado = $base_de_datos->prepare("SELECT nombre
                                                FROM productos 
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                             
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $prodctos []= $fila;
    } 

    foreach($prodctos as $pro){
        $nombre = $pro['nombre'];
    }

    $clases = array(
        'aireautomotriz'  => 'Aire Automotriz',
        'aireindustrial' => 'Aire Industrial',
        'combustiblelinea' => 'Combustible en Linea',
        'elemento' => 'Elemento',
        'panel' => 'Panel',
        'sellado' => 'Sellado',
    );

    include_once('./../alertas/alerta_error.php');
    alerta_error();
?>

          
<div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Clasificación Producto</h1>
        </div>

       <a href="./productos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

    <div class="card h-100 mb-5">
        <div class="card-body">
           <div class="row">
               <div class="col-12 col-md-6">
                <form action="update.php" id="form-especificacion" method="POST" class="form_login">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                        <input type="hidden" value="<?php echo $id ?>" name="id" >
                        <tr>
                            <th>Clasificación: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $nombre ?>" name="nombre" id="nombre" required/>
                            </td>
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="btnimg" class="btn-icon me-4" />
                            </td>
                        </tr>
                    </table> 
     
            </form>
                               </div>
                 </div>
                
</div>

<?php 
     include('../index/footer.php');
?>