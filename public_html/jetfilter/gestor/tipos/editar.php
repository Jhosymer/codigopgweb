<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Tipos";       
    if( !isset( $_REQUEST['id'] ) ){
        header("location: tipo.php");
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

    $seleccionado = $base_de_datos->prepare("SELECT tipo, categoria_id
                                                FROM tipos 
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                             
    $seleccionado->execute();
    $tipo = $seleccionado->fetch(PDO::FETCH_ASSOC);

    $categoria = $base_de_datos->prepare("SELECT categorias.id, categoria, productos.nombre, clase
                                                    FROM categorias
                                                    JOIN productos ON productos.id = categorias.producto_id
                                                    WHERE ( categorias.id = :categoria_id ) and ( categorias.deleted_at is null )");
    $categoria->bindParam(':categoria_id', $tipo['categoria_id'], PDO::PARAM_INT); 
    $categoria->execute();
    $categoria_seleccionado = $categoria->fetch(PDO::FETCH_ASSOC);

    $tipo = $tipo['tipo'];

    $categorias_totales = $base_de_datos->prepare("SELECT categorias.id, categoria, productos.nombre, clase
                                                    FROM categorias
                                                    JOIN productos ON productos.id = categorias.producto_id
                                                    WHERE ( categorias.deleted_at is null )");
    $categorias_totales->execute();
    while( $fila = $categorias_totales->fetch(PDO::FETCH_ASSOC) ){
        $categorias []= $fila;
    }

    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();
    alerta_ya_existe();
?>
   

<div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Tipos</h1>
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
                <form action="update.php" id="form-especificacion" method="POST" class="form_login">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                       <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                        <input type="hidden" value="<?php echo $id ?>" name="id" >
                        <tr>
                            <th> Tipo: </th>
                            <td>
                                <input  class="form-control" type="text" value="<?php echo $tipo ?>" name="tipo" id="tipo" required/>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria: </th>
                            <td>
                                <select name="categoria" id="categoria" class="form-select" required>
                                    <option value="" selected disabled>Categoria --- Clasificación --- Linea</option>
                                    <?php 
                                        foreach( $categorias as $cate ){
                                            if( $cate['id'] == $categoria_seleccionado['id'] ){
                                                ?>
                                                    <option value="<?php echo $cate['id']; ?>" selected><?php echo $cate['categoria'] . ' --- ' . $cate['nombre'] . ' --- ' . $cate['clase']; ?></option>
                                                <?php
                                            }
                                            else {
                                                ?>
                                                    <option value="<?php echo $cate['id']; ?>"><?php echo $cate['categoria'] . ' --- ' . $cate['nombre'] . ' --- ' . $cate['clase']; ?></option>
                                                <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </td>  
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="editar_tipo" class="btn-icon" />
                            </td>
                        </tr>
                    </table> 
                               </div>
                 </div>
                
</div>

<?php 
     include('../index/footer.php');
?>