<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Equivalencias";
     require_once("./../index/header.php");
    include_once('../../../config/conexion.php');

    //Se incluyen todas las alertas que pueden surgir
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    alerta_error();
    alerta_nuevo('El filtro fue agregado');
    alerta_actualizado('La información del filtro fue actualizada');
    alerta_eliminado("El filtro se elimino correctamente");

    $paginas = [10, 25, 50, 100, 250, 500, 2500, 10000];
?>

<style>
   .excel{
        background: #30A11C !important;
   }

    .excel:hover {
        background: #1D6411 !important;
    }
   
</style>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Equivalencias</h1>
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
                    <th>ID</th>
                    <th>Código</th>
                    <th>Código Buscar</th>
                    <th>Marca</th>
                    <th>Código Marca</th>
                    <th>Código Marca Buscar</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="table-group-divider" id="contenido">

            </tbody>
        </table>
       
        

                 <div id="row" class="me-2">
                <label id="lbl-total"></label>
                <div id="paginacion" class="links d-flex justify-content-end"></div>
                <input type="hidden" id="pagina" value="1" >
                        </div>

        <div class="tex_tablas_excel mb-5">
            <a id="descargar_excel" class="btn btn-success" >Descargar Página actual en Excel</a>
            <a id="descargar_excel_todo" class="btn btn-success" >Descargar Todo en Excel</a>
        </div>


    </div>
</section>


  <?php 
     include('../index/footer.php');
?>


<script>
    let paginaActual = 1;
    getData(paginaActual);

    document.getElementById("campo").addEventListener("keyup", function(){
        getData(paginaActual);
    }, false);

    document.getElementById("num_registros").addEventListener("change", function(){
        getData(paginaActual);
    }, false);

    function getData(pagina){
        let input = document.getElementById("campo").value;
        let content = document.getElementById("contenido");
        let num_registros = document.getElementById("num_registros").value;
        document.getElementById('pagina').value = pagina;

        $.ajax({
            data: {
                "campo": input,   
                "num_registros": num_registros,  
                "pagina": pagina,
            },
            url: './plantilla_equivalencia.php',
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

    descargar_excel = document.getElementById('descargar_excel');
    descargar_excel.addEventListener('click', () => {
        pagina = document.getElementById('pagina').value;
        registros = document.getElementById('num_registros').value;
        window.location.href = `./equivalencia_excel.php?pagina=${pagina}&registros=${registros}`;
    })

    descargar_excel_todo = document.getElementById('descargar_excel_todo');
    descargar_excel_todo.addEventListener('click', () => {
        window.location.href = `./equivalencia_excel.php`;
    })
</script>

<script src="./../js/eliminar.js"></script> <!-- Función que manda la advertencia para eliminar el filtro -->
<script src="../js/buscar.js"></script>