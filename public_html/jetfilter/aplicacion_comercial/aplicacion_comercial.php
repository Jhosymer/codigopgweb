<?php 
    //Incluir archivos y alertas
    include_once('./../arriba_carpeta.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    $paginas = [10, 25, 50, 100];

    alerta_nuevo();
    alerta_actualizado('La aplicacion ha sido actualizada');
    alerta_eliminado("La aplicación se ha eliminado correctamente");
?>

    <title>Aplicación Comercial</title>
    <script src="./../js/sweetAlerta.js"></script>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Aplicación Comercial</p>
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
    <script src="./../js/aplicacion/getData.js" ></script>
    <script>
        let paginaActual = 1;
        getData(paginaActual, 2);
        document.getElementById("campo").addEventListener("keyup", function(){
            getData(paginaActual, 2);
        }, false);
        document.getElementById("num_registros").addEventListener("change", function(){
            getData(paginaActual, 2);
        }, false);
    </script>
    <script src="./../js/eliminar.js" ></script> <!-- Evento al pulsar eliminar, salta la advertencia -->
