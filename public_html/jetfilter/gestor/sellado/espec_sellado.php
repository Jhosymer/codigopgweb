<?php
    $loc = "../../../";
     $locj = "../../";
     $title = "Especificaciones - Sellado ";
    try{
        $url_arriba_carpeta = '../index/header.php';
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
        $url_base_datos = '../../../config/conexion.php';
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

     
 <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Sellado</h1>
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