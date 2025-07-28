<?php
    try{
        $url_arriba_carpeta = './../../arriba_carpeta_segundoNivel.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('Sucedio Un error');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('Sucedio un Error');
        </script>";
    }

    try{
        $url_base_datos = './../../conexion/conexion.php';
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

    include_once('./../../alertas/alerta_nuevo.php');
    include_once('./../../alertas/alerta_eliminado.php');
    include_once('./../../alertas/alerta_actualizado.php');
    include_once('./../../alertas/alerta_limite.php');

    $paginas = [10, 25, 50, 100];

    alerta_nuevo('El distribuidor fue agregado');
    alerta_actualizado('La distribuidora del filtro fue actualizada');
    alerta_eliminado("El distribuidor se elimino correctamente");
    alerta_lmite();
?>

.
    <title>Distribuidores - Venezuela</title>
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
                window.location.replace("espec_aireautomotriz.php");
            })
        </script>
        <?php
            }
    ?>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Distribuidores - Venezuela</p>
            </div>
            <div class="tex_tablas">
                <a href="./nuevo.php" class="boton">Nuevo</a>
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
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Correo 2</th>
                        <th>Telefono</th>
                        <th>Telefono 2</th>
                        <th>Calificación</th>
                        <th>Facebook</th>
                        <th>Twitter</th>
                        <th>Instagram</th>
                         <th> Video Instagram</th>
                         <th> Google Maps</th>
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
        include("./../../abajo_carpeta_segundoNivel.html");
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
        $(document).ready(function(){

        });

        function getData(pagina){
            let input = document.getElementById("campo").value;
            let content = document.getElementById("contenido");
            let num_registros = document.getElementById("num_registros").value;

            $.ajax({
                data: {
                    "campo": input,
                    "num_registros": num_registros,
                    "pagina": pagina,
                    "pais": 'Venezuela'
                },
                url: './../../plantillas/cargar_distribuidoras.php',
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
                error: function(error){
                    alert(error.responseText);
                }
            });
        }
    </script>

    <script src="./../js/eliminar.js"></script>