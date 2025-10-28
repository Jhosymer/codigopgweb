<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Equivalencias";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

    if( !isset( $_REQUEST['id'] ) ){
        header("location: equivalencias.php");
    }



    $id = $_REQUEST['id'];

    //Consulta para Buscar los datos de la equivalencia
    $sql = "SELECT codigo, marca, codigo_marca FROM filtro_equivalencia WHERE id = :id";
    $filtro_seleccionado = $base_de_datos->prepare($sql) or die('Error al eliminar');
    $filtro_seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $filtro_seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $filtro_seleccionado->execute();

    //Consulta para buscar las marcas que no esten eliminadas
    $marca_act = $base_de_datos->prepare("SELECT marca FROM equivalencia_marca WHERE ( deleted_at IS NULL ) ORDER BY marca ASC");
    $marca_act->setFetchMode(PDO::FETCH_ASSOC); 
    $marca_act->execute();
    while ($fila = $marca_act->fetch()) {
        $resultado []= $fila;
    } 

    //Los datos del filtro se colocan en variables
    foreach($filtro_seleccionado as $filtro){
        $codigo = $filtro['codigo'];
        $marca = $filtro['marca'];
        $codigo_marca = $filtro['codigo_marca'];
    }

    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();
?>


<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">ver  Marca  Equivalencia</h1>
        </div>

       <a href="./equivalencias.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
            <!-- Formulario para actualizar -->
            <form action="update.php" method="POST" class="form_login">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                 <table class="table table-striped table-hover table-responsive dataTable">
                    <input type="hidden" value="<?php echo $id ?>" name="id"> <!-- Id de la equivalencia -->
                    <tr><th colspan="2" style="text-align: center;">Filtro</th></tr>
                    <!-- Código Web -->
                    <tr>
                        <th>Código WEB: </th>
                        <td>  
                            <input class="form-control" type="text" value="<?php echo $codigo ?>" name="codigo" id="codigo" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                                if( isset( $_SESSION["codigo_inexistente"] ) )
                                {
                                    echo "<div'>
                                            <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                            <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                        </div>";
                                    unset($_SESSION["codigo_inexistente"]);
                                }
                            ?>
                        </td>
                    </tr>
                    <tr><th colspan="2" style="text-align: center;">Equivalencia</th></tr>
                    <!-- Nombre de la Equivalencia -->
                    <tr>
                        <th>Marca: </th>
                        <td>
                            <select name="marca" id="marca" class="form-select">
                                <?php foreach($resultado as $resul){ 
                                    if($resul['marca'] == $marca){?>                                       
                                        <option value="<?php echo $resul['marca'];?>" selected><?php echo $resul['marca'];?></option>
                                    <?php }
                                    else {?>
                                        <option value="<?php echo $resul['marca'];?>"><?php echo $resul['marca'];?></option>
                                <?php }}?>
                                <option value="Otro">AGREGAR MARCA</option>
                            </select>
                        </td> 
                    </tr>
                    <!-- Código del Filtro en la Marca -->
                    <tr>
                        <th>Código Marca: </th>
                        <td>
                            <input class="form-control" type="text" value="<?php echo $codigo_marca ?>" name="codigo_marca" id="codigo_marca" />
                        </td>  
                    </tr>
                    <tr>
                        <td class="b_td">
                            <input type="submit" value="Editar" name="equivalencia_marca" class="btn-icon me-4" />
                        </td>
                        <td class="b_td">  
                            <input class="btn-icon me-4" value='Reestablecer' type="reset">
                        </td>
                    </tr>
                </table> 
            </form>
        </div> 

        <!-- Formulario Para Crear Marcas de Equivalencia -->
        <div class="col-12 col-md-6">
            <form action="./subir_marca_editar.php" method="POST" id="form-marca" class="formulario_aire">
                 <table class="table table-striped table-hover table-responsive dataTable">
                    <tr><th colspan="2" style="text-align: center;">Crear Marca</th></tr>
                    <tr>
                        <td><b>Marca: </b></td>
                        <td><input type="text" id="marca" name="marca"></td>
                    </tr>      
                    <tr> 
                        <td class="b_td"> 
                            <input class="btn-icon me-4" type="submit" value="Enviar" name="marca_nueva">
                        </td>
                        <td class="b_td">  
                            <input class="btn-icon me-4" value='Reestablecer' type="reset">
                        </td>
                    </tr> 
                </table> 
            </form>
        </form>
           </div>
        </div>
   
</div>



<?php
    include_once('../index/footer.php')
?>

<script>
    //Cuando cambie la marca, en caso de que se coloque Agregar Marca mostrará el formulario para crear una nueva 
    //marca de equivalencia
    document.getElementById('form-marca').style.display = 'none';
    document.getElementById('marca').addEventListener('change', () => {
        var valorCambiado = document.getElementById('marca').value;
        if(valorCambiado == 'Otro'){
            document.getElementById('form-marca').style.display = 'block';
        }
        else {
            document.getElementById('form-marca').style.display = 'none';
        }
    })
</script>
