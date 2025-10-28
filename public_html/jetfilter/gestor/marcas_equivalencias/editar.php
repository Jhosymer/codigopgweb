<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Marcas - Equivalencia";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    if( !isset($_REQUEST['id']) ){
        header("location: marcas_equivalencias.php");
    }



    $id = $_REQUEST['id'];

    $seleccionado = $base_de_datos->prepare("SELECT * FROM equivalencia_marca
                                                WHERE id = :id  and ( deleted_at is null )");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $marcas []= $fila;
    }    

    foreach($marcas as $mar){
        $marca = $mar['marca'];
        $mostrar = $mar['mostrar'];
    }

    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();

    
?>

   <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Aplicación Liviano</h1>
        </div>

       <a href="./marcas_equivalencias.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

    <div class="card h-100 mb-5">
        <div class="card-body">
           <div class="row">
               <div class="col-12 col-md-6">
                <form action="update.php" method="POST" class="form_login" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="table table-striped table-hover table-responsive dataTable">
                        <tr>
                            <th> Marca: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $marca ?>" name="marca" id="marca" required/>
                            </td>

                        </tr>  
                        <tr>
                            <th>Mostrar:</th>
                            <td>
                                <select id="mostrar" name="mostrar" class="form-select" required>
                                    <option value="1" <?php echo ($mostrar == 1) ? 'selected' : ''; ?>>SI</option>
                                    <option value="0" <?php echo ($mostrar == 0) ? 'selected' : ''; ?>>NO</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="btn-icon me-4" /></td></tr>
                    </table> 
                </form>
                              </div>
                 </div>
                
</div>

<?php 
     include('../index/footer.php');
?>