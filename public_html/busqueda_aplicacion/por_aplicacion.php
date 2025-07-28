<script type="text/javascript" src="./../js/jquery.min.js"></script>
  <script type="text/javascript" src="./../js/jquery.ez-plus.js"></script>
<?php
    include_once('./../../conexion.php');

    $paginas = [10, 25, 50, 100];
?>


    <script type="text/javascript" src="./../js/jquery.min.js"></script>
    <script type="text/javascript" src="./../js/jquery.ez-plus.js"></script>
    <script type="text/javascript" src='./../js/zoom.js'></script>
    <script src="./../js/app.js"></script><!-- /.menu movil -->
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>

    <section class="infor_aplica" style="display: inline;">
        <section class="detalle" id="detalle">
            <h1 class="title_busqueda">Buscar filtros por aplicaciones</h1> 
            <section class="about_aplicacion_op">
                <div class="about_aplicacion_sel about_sel">
                    <label>Aplicación :</label>
                    <select id="lista1" name="lista1" class="aplicacion selectap">
                        <option value="0" disabled selected>Seleccionar aplicación</option>
                        <?php
                        $sentencia = $base_de_datos->query("SELECT aplicacion, id FROM aplicacion_tipo ");
                        $sentencia->setFetchMode(PDO::FETCH_ASSOC); 

                        while ($row = $sentencia->fetch()) {
                            $row['aplicacion'] = substr($row['aplicacion'], 1);
                            if($row['id'] == $tipo){
                            ?>
                                <option value="<?php echo $row['id'] ?>" selected><?php echo $row['aplicacion']; ?></option>
                            <?php
                            }
                            else {
                            echo '<option value="'.$row['id'].'">'.$row['aplicacion'].'</option>';
                            }
                        } ?>
                    </select>
                    <div id="contenido">
                        <div id="select2lista">
                            <label style="display: none; " id="label_lista2">Marca :</label>
                            <select style="display: none; " id="lista2" name="lista2" class="aplicacion selectap">                            
                            </select>
                        </div>
                    </div>
                    <div id="boton_volver">
                    <a onclick="volver_registros()"><img src="./../img/tipo/bt__vm.png" alt="" class="bt_marca"></a>
                    </div>
                </div>
                    <div class="about_aplicacion_sel">
                        <div style="display: none;" id="contenido2">
                            <div id="tabla">
                                <label>Mostrar:</label>
                                <select name="registros" id="registros" >
                                    <?php 
                                        foreach($paginas as $pag){
                                        if($pag == $perPage){
                                                ?>
                                                    <option value="<?php echo $perPage; ?>" selected><?php echo $perPage; ?></option>
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
                                <input type='text' id='texto' name="texto" class='vBuscar'>
                                
                                <table id="contenido" class='busqueda_apli table-responsive'>
                                    <thead class='busqueda_apli'>
                                        <tr class='busqueda_apli'> 
                                            <td class='busqueda_apli' >
                                                Nombre
                                                <img id="imagen_descendente_modelo" onclick="orden(1, 0, 0)"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer; " width="20px" alt="">
                                                <img id="imagen_ascendente_modelo" onclick="orden(2, 0, 0)"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                                                <img id="igual_modelo" onclick="orden(1, 0, 0)"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                                            </td>
                                            <td class='busqueda_apli'>
                                                Cilindrada
                                                <img id="imagen_descendente_cilindrada" onclick="orden(0, 1, 0)"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                                                <img id="imagen_ascendente_cilindrada" onclick="orden(0, 2, 0)"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                                                <img id="igual_cilindrada" onclick="orden(0, 1, 0)"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                                            </td>
                                            <td class='busqueda_apli'>
                                                Año
                                                <img id="imagen_descendente_ano" onclick="orden(0, 0, 1)"  src="./../img/icono/orden_ascendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                                                <img id="imagen_ascendente_ano" onclick="orden(0, 0, 2)"  src="./../img/icono/orden_descendente.png" style="position: relative; display: none; cursor: pointer;" width="20px" alt="">
                                                <img id="igual_ano" onclick="orden(0, 0, 1)"  src="./../img/icono/arriba_abajo.png" style="position: relative; opacity: 0.25; cursor: pointer;" width="20px" alt="">
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="resultado" class='busqueda_apli'>
                                    </tbody>
                                </table>
                            </div>
                            <div id="totalResultado">

                            </div>
                            <div id="navegacion" class="links linka">

                            </div>
                        </div>
                        <div id="vehiculo_seleccionado">

                        </div>
                    </div>
            </section>
        </section>
    </section>

    <script src='./../js/busqueda_filtros/cambio_aplicacion.js'></script>
    <script src='./../js/busqueda_filtros/cambio_marca.js'></script>
    <script src='./../js/busqueda_filtros/cambio_marca_ordenado.js'></script>
    <script src='./../js/busqueda_filtros/aplicacion2.js'></script>
    <script src='./../js/busqueda_filtros/aplicacion3.js'></script>
    <script src='./../js/busqueda_filtros/getData.js'></script>
    <script src='./../js/busqueda_filtros/getRegistro.js'></script>
    <script src='./../js/busqueda_filtros/volver_registros.js'></script>   

    <script type="text/javascript">
        paginaActual = 1;

        getData(paginaActual);
        
        document.getElementById("texto").addEventListener("keyup", function(){
            getData(1);
        }, false);
        document.getElementById("registros").addEventListener("change", function(){
            getData(1);
        }, false);

        $(document).ready(function(){
            $('#tabla').css("display","none");
            $('#boton_volver').css("display","none");
            $('#detalle_producto').css("display","none");
        })

        function getFiltro(codigo, codigoVehiculo = null){
            if( codigoVehiculo == null ){
                $('#detalle').css("display","none");
                $.ajax({
                    data: {
                        'codigo': codigo,
                    },
                    url: "./../ajax_busquedas/filtro_seleccionado.php",
                    type: "POST",
                    dataType: "json",
                    success: function (response){
                        $('#detalle_producto').css("display","flex");
                        document.getElementById('filtro_titulo').innerHTML = response.titulo;
                        document.getElementById('filtro_carrusel').innerHTML = response.carrusel;
                        document.getElementById('filtro_especificaciones').innerHTML = response.especificaciones;
                        document.getElementById('filtro_aplicacion').innerHTML = response.aplicacion;
                        document.getElementById('filtro_equivalencia').innerHTML = response.equivalencia;
                        zoomImagen();
                    },
                    error: function (error){
                        alert("Error");
                    }
                });
            }
            else {
                $('#detalle').css("display","none");
                $.ajax({
                    data: {
                        'codigoVehiculo': codigoVehiculo,
                        'codigo': codigo,
                    },
                    url: "./../ajax_busquedas/filtro_seleccionado.php",
                    type: "POST",
                    dataType: "json",
                    success: function (response){
                        $('#detalle_producto').css("display","flex");
                        document.getElementById('filtro_titulo').innerHTML = response.titulo;
                        document.getElementById('filtro_carrusel').innerHTML = response.carrusel;
                        document.getElementById('filtro_especificaciones').innerHTML = response.especificaciones;
                        document.getElementById('filtro_aplicacion').innerHTML = response.aplicacion;
                        document.getElementById('filtro_equivalencia').innerHTML = response.equivalencia;
                        zoomImagen();
                    },
                    error: function (error){
                        alert("Error");
                    }
                });
            }
        }

        function volver(codigo){
            $.ajax({
                data: {
                    'codigo': codigo,
                },
                url: "./../ajax_busquedas/volver.php",
                type: "POST",
                dataType: "json",
                success: function (response){
                    id_vehiculo = response.id_vehiculo;
                    id_marca = response.id_marca;
                    id_tipo = response.id_tipo;
                },
                error: function (error){
                    alert("Error");
                }
            });

            window.location.href = `./aplicaciones.php?aplic=${id_vehiculo}&marca=${id_marca}&vehic=${id_vehiculo}`;
        }
    </script>

<script src="./../js/app.js"></script>
<script type="text/javascript" src="./../js/main.js"></script><!-- /.js zoom imagen catalogo -->

    <?php 
if( isset($_POST['aplicacion']) ){
        ?>
            <script>
                document.getElementById('lista1').value = <?php echo $_POST['aplicacion']; ?>;
                var valorCambiado = "id="+document.getElementById('lista1').value;
                    $.ajax({
                        data: valorCambiado,
                        url: './../ajax_busquedas/ajax_aplicacion.php',
                        type: 'POST',
                        success: function(response){
                            $('#lista2').css("display", "block");
                            $('#label_lista2').css("display", "block");
                            document.getElementById('lista2').innerHTML = response;
                        },
                        error: function(){
                            alert("Error");
                        }
                    });
            </script>
        <?php
    }
    ?>