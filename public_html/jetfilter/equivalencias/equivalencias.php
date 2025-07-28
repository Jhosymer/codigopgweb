<?php
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

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

<title>Equivalencias</title>
<script src="../js/sweetAlerta.js" ></script>

<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Equivalencias</p>
        </div>
        <div class="tex_tablas">
            <a href="nuevo.php" class="boton">Nuevo</a>
        </div>
    </section>

    <div class="about_tabla_edi">
        <label for="campo" >Mostrar:</label>
        <select name="num_registros" id="num_registros" class="mostar_textbox" >
            <?php 
                foreach($paginas as $pag){
                    if($pag == $perPage){
                    ?>
                        <option value="<?php echo $pag; ?>" selected><?php echo $pag; ?></option>
                    <?php
                    }
                    else{
                    ?>
                        <option value="<?php echo $pag; ?>" ><?php echo $pag; ?></option>
                    <?php
                    }
                }
            ?> 
        </select>

        <input type="text" class="textbox inline"  id='campo' size="30" placeholder="Buscar">
        
        <table>
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
            <tbody id="contenido">

            </tbody>
        </table>
        <div id="row">
            <div id="lbl-total"></div>
            <div id="paginacion" class="links">
            </div>
        </div>
        <input type="hidden" id="pagina" value="1" >
        <div class="tex_tablas_excel">
            <a id="descargar_excel" class="excel boton" >Descargar Página actual en Excel</a>
            <a id="descargar_excel_todo" class="excel boton" >Descargar Todo en Excel</a>
        </div>
    </div>
</section>

<?php 
    include_once('./../abajo_carpeta.html');
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