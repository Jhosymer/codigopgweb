<?php
    try{
        $url_arriba_carpeta = './../arriba_carpeta.php';
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


    $paginas = [10, 25, 50, 100];
    $tbusqueda = ['encontrados','no encontrados' ];


 
?>


    <title>Busquedas Realizadas</title>
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
                    window.location.replace("espec_reporte_requerimiento_filtros.php");
                })
            </script>
            <?php
                }
    ?>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Requerimiento de Filtros</p>
            </div>
            <div class="tex_tablas">
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

            <div class="tex_tablas_excel">
          
            <a id="descargar_excel" class="excel boton" >Descargaren Excel</a>
        </div>
           
            
            <table>
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


  

