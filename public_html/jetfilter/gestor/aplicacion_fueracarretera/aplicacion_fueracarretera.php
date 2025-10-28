<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Aplicacion Fuera de Carretera";
    include_once('../index/header.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    $paginas = [10, 25, 50, 100];

    alerta_nuevo();
    alerta_actualizado('La aplicacion ha sido actualizada');
    alerta_eliminado("La aplicación se ha eliminado correctamente");
?>

   
 <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Aplicación Fuera de Carretera</h1>
        </div>
        <a href="./nuevo.php"  class="btn-icon me-4" >Nuevo</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>
        <div class="overflow-auto me-4 ms-4">
            <div class="d-flex align-items-center about_tabla_edi">
            <label for="campo" class="me-2">Mostrar:</label>
            <select name="num_registros" id="num_registros" class="form-select form-select-sm me-2" style="width: auto;">
                <?php 
                    foreach($paginas as $pag){
                        if($pag == $perPage){
                        ?>
                            <option value="<?php echo $pag; ?>" selected><?php echo $pag; ?></option>
                        <?php
                        }
                        else{
                        ?>
                            <option value="<?php echo $pag; ?>"><?php echo $pag; ?></option>
                        <?php
                        }
                    }
                ?> 
            </select>
            <input type="text" class="form-control form-control-sm" name="campo" id="campo" size="30" placeholder="Buscar" style="width: auto;">
        </div>

      <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Vehículo</th>
                        <th>Motor</th>
                        <th>Aplicación</th>
                        <th>Id Código</th>
                        <th>Código</th>
                        <th>Detalle</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider" id="contenido">

                </tbody>
            </table>
            <div id="row" class="mb-5 me-2">
                <label id="lbl-total"></label>
                <div id="paginacion" class="links d-flex justify-content-end">
                </div>
            </div>
        </div>
 

    <?php 
       require_once("../index/footer.php");
    ?>
    <script src="./../js/aplicacion/getData.js" ></script>
    <script>
        let paginaActual = 1;
        getData(paginaActual, 3);
        document.getElementById("campo").addEventListener("keyup", function(){
            getData(paginaActual, 3);
        }, false);
        document.getElementById("num_registros").addEventListener("change", function(){
            getData(paginaActual, 3);
        }, false);
    </script>
    <script src="./../js/eliminar.js" ></script> <!-- Evento al pulsar eliminar, salta la advertencia -->
