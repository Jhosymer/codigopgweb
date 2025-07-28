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
                window.location.replace("espec_busqueda_filtro.php");
            })
        </script>
        <?php
            }
    ?>

    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Busquedas Realizadas</p>
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
          
            <a id="descargar_excel" class="excel boton" >Descargar en Excel</a>
        </div>
           
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo Buscado</th>
                        <th>Respuesta</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Fechas</th>
                       
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
                url: './../plantillas/cargar_busqueda_filtro.php',
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
        let tipo = "";
       
        const inputOptions = new Promise((resolve) => {
            //Colores a Escoger para Descargar
            resolve({
                'Todas': 'Todas',
                'Si': 'Si',
                'No': 'No'
            })
        });

        //Alerta que se va a mostrar al usuario cuando se pulse click en Descargar
        const { value: color } = Swal.fire({
            input: 'radio',
            html: `<h2 class="swal2-title" id="swal2-title" style="display: block;">Indique</h2>
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;">Rango de fecha</div>
           
            <input id="fechaI" class="swal2-input" placeholder="Inicio ej(2023-10-01)">
            <input id="fechaF" class="swal2-input"  placeholder="Fin ej (2023-10-01)">
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;">¿tipo de Respuesta?</div>

            `,
            showCancelButton: true,
            inputOptions: inputOptions, //Los colores que se colocaron como opciones
            inputValidator: (value) => { //Value -> Color escogido al pulsar el botón de confirmación
                //Si no se escogio, aparecera una alerta con el texto retornado
                fechaI = document.getElementById('fechaI').value;
                fechaF = document.getElementById('fechaF').value;
                if (!value) {
                    return '¡Todos los campos son obligatorios!';
                }
                //En caso de que se escoja un color, se descargara la imagen correspondiente
                else if( value == 'Todas' ){
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./busqueda_filtro_excel.php?tipo=${tipo}&fechaI=${fechaI}&fechaF=${fechaF}`;

                    a.click();
                    

                }
                else if( value == 'Si' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./busqueda_filtro_excel.php?tipo=${tipo}&fechaI=${fechaI}&fechaF=${fechaF}`;

                    a.click();
                }
                else if( value == 'No' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./busqueda_filtro_excel.php?tipo=${tipo}&fechaI=${fechaI}&fechaF=${fechaF}`;

                    a.click();
                }
            }
        });
    });
</script>


  

