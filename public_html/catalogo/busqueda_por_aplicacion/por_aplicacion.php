<?php


    $paginas = [10, 25, 50, 100];
?>
 <div class="container my-5">
    <h1 class="titulo_bold rojoweb text-uppercase">Buscar filtros por aplicaciones</h1>
    <div class="row">
        <div class="col-md-5">
            <div class="card p-3 shadow-sm border-0 mt-5">
                <form>
                    <div class="mb-3">
                        <label for="lista1" class="form-label"><b>Aplicación :</b></label>
                        <select id="lista1" name="lista1" class="form-select">
                            <option value="0" disabled selected>Seleccionar aplicación</option>
                            <?php
                            $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : (isset($_POST['tipo']) ? $_POST['tipo'] : null);

                            $sentencia = $base_de_datos->query("SELECT aplicacion, id FROM aplicacion_tipo ");
                            $sentencia->setFetchMode(PDO::FETCH_ASSOC);
                            while ($row = $sentencia->fetch()) {
                                $row['aplicacion'] = substr($row['aplicacion'], 1);
                                if ($row['id'] == $tipo) {
                                ?>
                                    <option  value="<?php echo htmlspecialchars($row['id']); ?>" selected class="Roboto-Bold"><?php echo htmlspecialchars($row['aplicacion']); ?></option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?php echo htmlspecialchars($row['id']); ?>" ><?php echo htmlspecialchars($row['aplicacion']); ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    

                    <div id="select2lista" class="mb-3">
                        <label for="lista2" class="form-label" id="label_lista2" style="display: none;"><b>Marca :</b></label>
                        <select id="lista2" name="lista2" class="form-select aplicacion selectap" style="display: none;"></select>
                    </div>

                    <div id="boton_volver" style="display: none;" class=" mt-5 mb-3">
                        <a onclick="volver_registros()" class="btn-icon"> Volver</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-7 ">
            <div id="contenido2" style="display: none;">
                <div id="tabla" class="d-flex flex-column">

                        <div class="row align-items-center g-3 mb-3 mt-5">
                            <div class="col-auto d-flex align-items-center">
                                <label for="registros" class="me-2 text-nowrap">Mostrar:</label>
                                <select name="registros" id="registros" class="form-select form-select-sm" style="width: auto;">
                                    <?php foreach ($paginas as $pag) : ?>
                                    <?php if ($pag == $perPage) : ?>
                                        <option value="<?php echo $perPage; ?>" selected ><?php echo $perPage; ?></option>
                                    <?php else : ?>
                                        <option value="<?php echo $pag; ?>"><?php echo $pag; ?></option>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-auto">
                                <input type="text" id="texto" name="texto" class="form-control form-control-sm">
                            </div>
                        </div>

                    

                    <div class="table-responsive">
                        <table id="contenido" class='table table-sm table-borderless table-custom table-codigos-web'>
                            <thead class='bg-danger text-white'>
                                <tr>
                                    <th>
                                        Nombre
                                        <i id="imagen_descendente_modelo" onclick="orden(1, 0, 0)" class='bx bx-sort-a-z' style="display: none; cursor: pointer;"></i>
                                        <i id="imagen_ascendente_modelo" onclick="orden(2, 0, 0)" class='bx bx-sort-z-a' style="display: none; cursor: pointer;"></i>
                                        <i id="igual_modelo" onclick="orden(1, 0, 0)" class='bx bx-sort' style="opacity: 0.25; cursor: pointer;"></i>
                                    </th>
                                    <th>
                                        Cilindrada
                                        <i id="imagen_descendente_cilindrada" onclick="orden(0, 1, 0)" class='bx bx-sort-a-z' style="display: none; cursor: pointer;"></i>
                                        <i id="imagen_ascendente_cilindrada" onclick="orden(0, 2, 0)" class='bx bx-sort-z-a' style="display: none; cursor: pointer;"></i>
                                        <i id="igual_cilindrada" onclick="orden(0, 1, 0)" class='bx bx-sort' style="opacity: 0.25; cursor: pointer;"></i>
                                    </th>
                                    <th>
                                        Año
                                        <i id="imagen_descendente_ano" onclick="orden(0, 0, 1)" class='bx bx-sort-a-z' style="display: none; cursor: pointer;"></i>
                                        <i id="imagen_ascendente_ano" onclick="orden(0, 0, 2)" class='bx bx-sort-z-a' style="display: none; cursor: pointer;"></i>
                                        <i id="igual_ano" onclick="orden(0, 0, 1)" class='bx bx-sort' style="opacity: 0.25; cursor: pointer;"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="resultado"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div id="totalResultado"></div>
                        <div id="navegacion" class="links linka"></div>
                    </div>
                </div>
            </div>

            <div id="vehiculo_seleccionado" class="card mt-4" style="display: none;">
                <div class="card-header bg-black text-white fw-bold">VEHICULO</div>
                
            </div>
        </div>
    </div>
</div>
                                        

    <script src='./../../js/busqueda_filtros/cambio_aplicacion.js'></script>
    <script src='./../../js/busqueda_filtros/cambio_marca.js'></script>
    <script src='./../../js/busqueda_filtros/cambio_marca_ordenado.js'></script>
    <script src='./../../js/busqueda_filtros/aplicacion2.js'></script>
    <script src='./../../js/busqueda_filtros/aplicacion3.js'></script>
    <script src='./../../js/busqueda_filtros/getData.js'></script>
    <script src='./../../js/busqueda_filtros/getRegistro.js'></script>
    <script src='./../../js/busqueda_filtros/volver_registros.js'></script>   

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
                    url: "./../../ajax_busquedas/filtro_seleccionado.php",
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
                    url: "./../../ajax_busquedas/filtro_seleccionado.php",
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
                url: "./../../ajax_busquedas/volver.php",
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

            window.location.href = `./index.php?aplic=${id_vehiculo}&marca=${id_marca}&vehic=${id_vehiculo}`;
        }
    </script>



   
<?php 
if( isset($_GET['aplicacion']) ){
?>
    <script>
        document.getElementById('lista1').value = <?php echo $_GET['aplicacion']; ?>;
        var valorCambiado = "id="+document.getElementById('lista1').value;
            $.ajax({
                data: valorCambiado,
                url: './../../ajax_busquedas/ajax_aplicacion.php',
                type: 'POST', // This is correct for the AJAX call
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