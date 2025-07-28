<?php
    try{
        $url_arriba_carpeta = './../arriba_carpeta.php';
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
        $url_base_datos = './../conexion/conexion.php';
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

    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');
    include_once('./../alertas/alerta_imagenes.php');

    $paginas = [10, 25, 50, 100];

    alerta_nuevo('El filtro fue agregado');
    alerta_actualizado('La información del filtro fue actualizada');
    alerta_eliminado("El filtro se elimino correctamente");
    alerta_actualizado_imagenes("Las imagenes del filtro fueron actualizadas");
?>

    <title>Especificaciones - Sellado</title>
    <script src="./../js/sweetAlerta.js"></script>

    <?php
        if( isset($_GET["errorBase"]) )
            {
        ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema con la base de datos',
                timer: 1250,
                }) .then(() => {
                window.location.replace("espec_sellado.php?page="+<?php echo $page; ?>);
            })
        </script>
        <?php
            }
    ?>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Sellado</p>
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
                        <th>Código</th>
                        <th>Código Buscar</th>
                        <th>Tipo</th>
                        <th>Filtración</th>
                        <th>Diámetro Ext</th>
                        <th>Diámetro Int</th>
                        <th>Altura</th>
                        <th>Diámetro Emp Ext</th>
                        <th>Diámetro Emp Int</th>
                        <th>Espesor Emp</th>
                        <th>Valvula AL</th>
                        <th>Apertura</th>
                        <th>Valvula AD</th>
                        <th>Und Empaque</th>
                        <th>Detalle 1</th>
                        <th>Detalle 2</th>
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
        include("./../abajo_carpeta.html");
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

            formData = new FormData();
            formData.append('campo', input);
            formData.append('pagina', pagina);
            formData.append('num_registros', num_registros);
            formData.append('tabla', 'espec_sellado');
            fetch("./../plantillas/cargar.php", {
                method: 'POST',
                body: formData,
            })
            .then( response => response.json() )
            .then(
                data => {
                    content.innerHTML = data.data;
                    if(data.totalFiltro != data.totalRegistros){
                        document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                    }
                    else {
                        document.getElementById('lbl-total').innerHTML = "";
                    }
                    document.getElementById('paginacion').innerHTML =  data.paginacion;
                }
            )
            .catch(
                error => alert('error')
            )
        }
    </script>

    <script src="./../js/eliminar.js"></script> <!-- Función que manda la advertencia para eliminar el filtro -->