<?php 
     $loc = "../../../../";
     $locj = "../../../";
     $title = "Zona de Distribución Distribuidor";
    try{
        $url_arriba_carpeta = './../../index/header.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('Hubo un Error');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('Hubo un Error');
        </script>";
    }

    try{
        $url_base_datos = './../../../../config/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('Hubo un Fallo');
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

    $paginas = [10, 25, 50, 100];

    alerta_nuevo('El distribuidor fue agregado');
    alerta_actualizado('La distribuidora del filtro fue actualizada');
    alerta_eliminado("El distribuidor se elimino correctamente");
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
                window.location.replace("espec_aireautomotriz.php");
            })
        </script>
        <?php
            }
    ?>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
    <div class="text-center flex-grow-1">
        <h1 class="titulo">Zona de Distribución Distribuidor</h1>
    </div>
        <a href="./nuevo_estados.php"  class="btn-icon me-4" >Nuevo</a>
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
                        <th>Nombre de Distribuidor</th>
                        <th>Estado</th>
                        <th>Ciudad</th>
                        <th>Direccion</th>
                        <th>Principal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider" id="contenido">

                </tbody>
            </table>
            <div id="row" class="mb-5 me-2">
                <label id="lbl-total"></label>
                <div id="paginacion" class="links d-flex justify-content-end"></div>
            </div>
    </div>
</div>
 

    <?php 
        require_once("./../../index/footer.php");
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

            formData = new FormData();
            formData.append('campo', input);
            formData.append('pagina', pagina);
            formData.append('num_registros', num_registros);
            formData.append('pais', 'Venezuela');

            fetch("./../../plantillas/cargar_distribuidoras_estado.php", {
                method: 'POST',
                body: formData,
            })
            .then( response => response.json() )
            .then(
                data => {
                    content.innerHTML = data.data;
                    if( data.totalFiltro != data.totalRegistros ){
                        document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                    }
                    else {
                        document.getElementById('lbl-total').innerHTML = "";
                    }
                    document.getElementById('paginacion').innerHTML =  data.paginacion;
                }
            )
            .catch(
                error => console.log(error)
            )
        }
    </script>

    <script src="./../../js/eliminar.js"></script>