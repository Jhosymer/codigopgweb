<?php
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $paginas = [10, 25, 50, 100];

    //Alertas a Comprobar
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    alerta_nuevo();
    alerta_actualizado('La aplicacion ha sido actualizada');
    alerta_eliminado("La aplicación se ha eliminado correctamente");
?>

    <title>Marcas - Aplicaciones</title>
    <script src="./../js/sweetAlerta.js"></script>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Marcas - Aplicaciones</p>
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

            <input type="text" class="textbox inline" name="campo" id="campo" size="30" placeholder="Buscar">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Marca</th>
                        <th>Sincronizado</th>
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
        </div>
    </section>
<?php 
    include_once('./../abajo_carpeta.html');
?>
    <script>
       let paginaActual = 1;
        getMarcas();

        document.getElementById("campo").addEventListener("keyup", function(){
            getMarcas(paginaActual);
        }, false);

        document.getElementById("num_registros").addEventListener("change", function(){
            getMarcas(paginaActual);
        }, false);

        function getMarcas(pagina){
            let input = document.getElementById('campo').value;
            let content = document.getElementById('contenido');
            let num_registros = document.getElementById("num_registros").value;

            $.ajax({
                data: {
                    "campo": input,
                    "num_registros": num_registros,
                    "pagina": pagina,
                },
                url: './plantilla_marca.php',
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

<script src="./../js/eliminar.js"></script>