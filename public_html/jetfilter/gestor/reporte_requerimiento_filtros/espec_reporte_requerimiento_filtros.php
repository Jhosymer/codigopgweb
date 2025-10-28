<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Busquedas Realizadas";
    try{
        $url_arriba_carpeta = '../index/header.php';
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


    $paginas = [10, 25, 50, 100];
    $tbusqueda = ['encontrados','no encontrados' ];


 
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
                    window.location.replace("espec_reporte_requerimiento_filtros.php");
                })
            </script>
            <?php
                }
    ?>

 <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Requerimiento de Filtros</h1>
        </div>
        <a href="./../especificaciones.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>
        <div class="overflow-auto me-4 ms-4">
            <div class="d-flex align-items-center about_tabla_edi mb-3">
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

        <div class="mt-5 ">
          
            <a id="descargar_excel" class="btn-icon" >Descargaren Excel</a>
        </div>

     
            
           
            
            <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Fabricante</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Motor</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                    
                        
                    
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
                },
                url: './../plantillas/cargar_requerimiento_filtros.php',
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

        
       

    /*----------------EVENTO PARA CLICK DESDARGAR----------- */
    botonColoresCorporativos = document.getElementById('descargar_excel');
    botonColoresCorporativos.addEventListener('click', () => {
        Swal.fire({
        title:'Indique',
        html: ` <div class="swal2-html-container" id="swal2-html-container" style="display: block;">Rango de fecha</div>
        <div> <form method='POST' action="AAA">
        <input id="fechaI" class="swal2-input" placeholder="Inicio ej(2023-10-01)">
            <input id="fechaF" class="swal2-input"  placeholder="Fin ej(2023-10-01)">
            </form><div>`,
        confirmButtonText:'Enviar',
        confirmButtonColor:'#E2001Ac8',
        footer:"Todos los campos son obligatorios",
        padding:'1rem',
        background:'true',
        showLoaderOnConfirm: true,
        cancelButtonText:'Cancelar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            
            fechaI = document.getElementById('fechaI').value;
            fechaF = document.getElementById('fechaF').value; 
           

            if( fechaI != '' && fechaF != '' ){
            
                var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./requerimiento_filtros_excel.php?fechaI=${fechaI}&fechaF=${fechaF}`

                    a.click();
            }
            else {
                var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./requerimiento_filtros_excel.php?fechaI=${fechaI}&fechaF=${fechaF}`

                    a.click();
            }
        }
    })

    });
</script>


  

