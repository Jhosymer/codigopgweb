<?php
     $loc = "../../../";
     $locj = "../../";
     $title = "Especificaciones - Combustible en Linea";
     require_once("../index/header.php");
 

    //Se incluyen todas las alertas que pueden surgir
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

    $paginas = [10, 25, 50, 100];
?>

   
    <script src="./../js/sweetAlerta.js"></script>

 <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Combustible en Linea</h1>
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
                        <th>Código</th>
                        <th>Código Buscar</th>
                        <th>Tipo</th>
                        <th>Filtración</th>
                        <th>Diámetro Exterior</th>
                        <th>Altura</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Und Empaque</th>
                         <th>Codigo de Barra</th>
                        <th>Detalle 1</th>
                        <th>Detalle 2</th>
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
    <script>
        let paginaActual = 1;
        /* Se hace una busqueda al iniciar la pantalla */
        getData(paginaActual);
        //Si se coloca una letra en el campo vuelve a hacer la busqueda
        document.getElementById("campo").addEventListener("keyup", function(){
            getData(paginaActual);
        }, false);
        //Si cambia el número de registros a buscar vuelve a hacer la busqueda
        document.getElementById("num_registros").addEventListener("change", function(){
            getData(paginaActual);
        }, false);

        //Función que hace la busqueda
        function getData(pagina){
            let input = document.getElementById("campo").value;
            let content = document.getElementById("contenido");
            let num_registros = document.getElementById("num_registros").value;
            columnas = ["id", "codigo", "id_codigo", "codigo_buscar", "tipo", "diametroext", "altura","entrada", "salida", "detalle1", "detalle2","deleted_at"];

            $.ajax({
                data: {
                    "campo": input,
                    "num_registros": num_registros,
                    "pagina": pagina,
                    "tabla": "espec_combustiblelinea",
                    'columnas': JSON.stringify(columnas), 
                },
                url: './../plantillas/cargar.php',
                dataType: 'json',
                type: 'POST',
                success: function(response){
                    content.innerHTML = response.data;
                    if(response.totalFiltro != response.totalRegistros){
                        document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + response.totalFiltro + " de " + response.totalRegistros + " registros</p>";
                    }
                    else {
                        document.getElementById('lbl-total').innerHTML = "";
                    }
                    document.getElementById('paginacion').innerHTML =  response.paginacion;
                },
                error: function(){
                    alert("Error");
                }
            });
        }
    </script>

    <script src="./../js/eliminar.js"></script> <!-- Función que manda la advertencia para eliminar el filtro -->