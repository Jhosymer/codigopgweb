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

    $paginas = [10, 25, 50, 100];

    alerta_nuevo('La categoria fue agregada');
    alerta_actualizado('La categoria fue actualizada');
    alerta_eliminado("La categoria fue eliminada correctamente");
?>

    <title>Especificaciones - Categorias</title>
    <script src="./../js/sweetAlerta.js"></script>

    <?php
        if( isset( $_GET["errorBase"] ) )
            {
        ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema',
                timer: 1250,
                }) .then(() => {
                window.location.replace( "./categorias.php" );
            })
        </script>
        <?php
            }
    ?>

<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Categorias</p>
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
                    <th>Categoria</th>
                    <th>línea</th>
                    <th>Clasificación</th>
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
            
            $.ajax({
                data: {
                    "campo": input,   
                    "num_registros": num_registros,  
                    "pagina": pagina,
             
                },
                url: './../plantillas/cargar_categoria.php',
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