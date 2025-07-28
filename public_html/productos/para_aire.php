<link rel="stylesheet" href="./../css/estilo_body_apli.css">
<title>Filtro para Aire</title>


<?php
    include_once("./../web/arriba_carpeta.php");
    include_once("./../../conexion.php");
    
    $sql = "SELECT cat.categoria FROM categorias as cat
                    JOIN productos as p ON p.id = cat.producto_id
                    WHERE ( p.nombre = 'Aire' ) and ( cat.deleted_at is null )  and ( p.deleted_at is null )
                    GROUP BY cat.categoria";
    $categorias_selec = $base_de_datos->prepare($sql);  
    $categorias_selec->setFetchMode( PDO::FETCH_ASSOC );
    $categorias_selec->execute();
    while( $fila = $categorias_selec->fetch() ){
        $categorias []= $fila;
    }
?>
    <div class="aplicacion_producto">
        <div class="grid_productos">
            <h1 class="nombre_prod">Filtro para Aire</h1>
            <div class="grid_productos_tabla">
                <div class="grid_productos">
                    <?php 
                        foreach( $categorias as $categoria ){
                            $categoria['categoria'] = trim($categoria['categoria']);
                            $cat = $categoria['categoria'];
                    ?>
                        <div data-category="<?php echo $cat; ?>" class="clase2" >
                            <div class="about_fitros_productos" id="about_fitros_productos">
                                <div class="div_img_pro" style="cursor: pointer;" >
                                    <?php 
                                        if( $categoria['categoria'] == "SELLADO ROSCABLE" ) { 
                                    ?>
                                            <img src="./../img/productos/aceite.jpg" data-value="<?php echo $cat; ?>"  class="img_prod" alt="FILTRO TIPO SELLADO ROSCABLE">
                                    <?php } 
                                        else if( $categoria['categoria'] == "SELLO AXIAL" ){ 
                                    ?>
                                            <img src="./../img/productos/WCA-7178.png" data-value="<?php echo $cat; ?>" class="img_prod" alt="FILTRO TIPO SELLO AXIAL">
                                    <?php
                                        }
                                        else if( $categoria['categoria'] == "SELLO RADIAL" ){ 
                                            ?>
                                                    <img src="./../img/productos/WRA-5626.png" data-value="<?php echo $cat; ?>" class="img_prod" alt="FILTRO TIPO SELLO RADIAL">
                                            <?php
                                        }
                                        else if( $categoria['categoria'] == "FLUJO CANAL" ){ 
                                            ?>
                                                    <img src="./../img/productos/WCA-4702.png" data-value="<?php echo $cat; ?>" class="img_prod" alt="FILTRO TIPO FLUJO CANAL">
                                            <?php
                                        }
                                        else if( $categoria['categoria'] == "PANEL" ){ 
                                            ?>
                                                    <img src="./../img/productos/WA-10111.png" data-value="<?php echo $cat; ?>" class="img_prod" alt="FILTRO TIPO PANEL">
                                            <?php
                                        }
                                        else if( $categoria['categoria'] == "CARBURADO" ){ 
                                            ?>
                                                    <img src="./../img/productos/WCA-2718_.png" data-value="<?php echo $cat; ?>" class="img_prod" alt="FILTRO TIPO CARBURADO">
                                            <?php
                                        }
                                        else {
                                    ?>
                                            <img src="./../img/productos/w-2010.png" data-value="<?php echo $cat; ?>"  class="img_prod" alt="">
                                    <?php
                                        } 
                                    ?>
                                </div> 
                                <div class="div_img_pro" class="img_prod" style="cursor: pointer;" >
                                  <a href="#tabla_prod">  <h2 class="titulo_prod" data-value="<?php echo $cat; ?>" ><?php echo $categoria['categoria'] ?></h2> </a>
                                </div>
                            </div>
                        </div>
                    <?php 
                        }
                    ?>
                    </div>
                    <input type="hidden" id="clase" value="" >
                    <input type="hidden" id="categoriaSeleccionada" value="" >
                    <input type="hidden" id="pagina" value="1" >
                    <input type="hidden" id="orderCol" value="0" >
                    <input type="hidden" id="orderType" value="ASC" >
             
                <div class="tabla_prod" id="tabla_prod" style="display: block;">
                    <label>Mostrar:</label>
                    <select id="registros">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>               
                    </select>
                    <input type="text" id="texto" name="texto" class="vBuscar">
                    <table class="pro table-responsive" id="tipos" >
                        <thead class="pro" >
                            <tr class="pro" > 
                                <td class="pro sort asc" >
                                    Tipo
                                </td>
                                <td class="pro sort asc" >
                                    Cantidad
                                </td>
                            </tr>
                        </thead>
                        <tbody id="resultados_tipos" >

                        </tbody>
                    </table>
                    <br />
                    <div id="row">
                        <div id="lbl-total"></div>
                        <div id="paginacion" class="links">
                        </div>
                    </div>
                </div>
                <div class="tabla_prod" id="tabla_prod_codigos" style="display: block;" >
                    <label>Mostrar:</label>
                    <select id="registros_codigos">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>               
                    </select>
                    <input type="text" id="campo_codigos" name="texto" class="vBuscar">
                    <table class="pro table-responsive" id="tipos_codigos" >
                        <thead class="pro">
                            <tr id="aireautomotriz_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >ø ext (mm)</td>
                                <td class="pro sort_filtro asc" >ø int (mm)</td>
                                <td class="pro sort_filtro asc" >Altura (mm)</td>
                            </tr>
                            <tr id="aireindustrial_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >ø ext (mm)</td>
                                <td class="pro sort_filtro asc" >ø int (mm)</td>
                                <td class="pro sort_filtro asc" >Altura (mm)</td>
                            </tr>
                            <tr id="combustiblelinea_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >ø ext (mm)</td>
                                <td class="pro sort_filtro asc" >Altura (mm)</td>
                                <td class="pro sort_filtro asc" >Lineas</td>
                            </tr>
                            <tr id="elemento_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >ø ext (mm)</td>
                                <td class="pro sort_filtro asc" >ø int (mm)</td>
                                <td class="pro sort_filtro asc" >Altura (mm)</td>
                            </tr>
                            <tr id="fluidos_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >Detalle 1</td>
                                <td class="pro sort_filtro asc" >Detalle 2</td>
                            </tr>
                            <tr id="panel_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc" >Largo (mm)</td>
                                <td class="pro sort_filtro asc" >Ancho (mm)</td>
                                <td class="pro sort_filtro asc" >ø Altura (mm)</td>
                            </tr>
                            <tr id="sellado_codigos" class="pro oculto_head" > 
                                <td class="pro sort_filtro asc">
                                    Codigo
                                </td>
                                <td class="pro sort_filtro asc">Rosca (mm)</td>
                                <td class="pro sort_filtro asc" >ø ext (mm)</td>
                                <td class="pro sort_filtro asc" >Altura (mm)</td>
                                <td class="pro sort_filtro asc" >Empacadura (mm)</td>
                                <td class="pro sort_filtro asc" >Valvulas</td>
                            </tr>
                        </thead>
                        <tbody id="resultados_tipos_codigos">

                        </tbody>
                    </table>
                    <br />
                    <div id="row_codigos">
                        <div id="lbl-total_codigos"></div>
                        <div id="paginacion_codigos" class="links">
                        </div>
                    </div>
                </div>
                <input type="hidden" id="tipoSeleccionado" value="" >
                <input type="hidden" id="paginaFiltro" value="1" >
                <input type="hidden" id="orderColFiltro" value="0" >
                <input type="hidden" id="orderTypeFiltro" value="ASC" >
                <input type="hidden" id="clase" value="" >
            </div>
        </div>
    </div>

<?php
    include("./../web/abajo_carpeta.php");
?>

<script>

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('tabla_prod').style.display = 'none';
        document.getElementById('tabla_prod_codigos').style.display = 'none';

        //Evento Cambiar Campo de Texto
        document.getElementById("texto").addEventListener("keyup", function(){
            getData();
        }, false);

        //Evento Cambiar Numero de Registros
        document.getElementById("registros").addEventListener("change", function(){
            getData();
        }, false);

         //Evento Cambiar Campo de Texto en Tabla tipo
         document.getElementById("campo_codigos").addEventListener("keyup", function(){
            getFiltro();
        }, false);

        //Evento Cambiar Numero de Registros en Tabla Tipo
        document.getElementById("registros_codigos").addEventListener("change", function(){
            getFiltro();
        }, false);

        //Evento Click Sobre Imagen Categoria
        categorias = document.getElementsByClassName('div_img_pro');
        tamanio = categorias.length;
        for(let i = 0; i < tamanio; i++){
            categorias[i].addEventListener('click', pulsarCategoria );
        }

        //Evento click sobre el tipo se ordene
        let columns = document.getElementsByClassName('sort');
        tamanio = columns.length;
        for(let i = 0; i < tamanio; i++){
            columns[i].addEventListener('click', ordenar);
        }

        //Evento click sobre el tipo se ordene
        let columns_filtros = document.getElementsByClassName('sort_filtro');
        tamanio = columns_filtros.length;
        for(let i = 0; i < tamanio; i++){
            columns_filtros[i].addEventListener('click', ordenarFiltro);
        }
    })

    /*-------------FUNCIONES DE LOS TIPOS-------------------------*/ 

    /*----------------Al pulsar categoria-------------------*/
    function pulsarCategoria(e){
        data = e.target;
        categoria_selec = data.getAttribute('data-value');
        document.getElementById('categoriaSeleccionada').value = categoria_selec;

        clase3 = document.querySelectorAll(".clase3");
        if( clase3.length != [] ){
            for(let i = 0; i < clase3.length; i++){
                clase3[i].classList.remove('clase3');
                clase3[i].classList.add('clase2');
            }
        }

        clase_data = document.querySelector(`div[data-category="${categoria_selec}"]`);
        clase_data.classList.add('clase3');
        clase_data.classList.remove('clase2');

        document.getElementById('pagina').value = 1;
        document.getElementById('paginaFiltro').value = 1;

        getData();
    }

    /*----------Realizar la busqueda de los tipos-------------*/
    function getData(){
        let categoria = document.getElementById("categoriaSeleccionada").value;
        let input = document.getElementById("texto").value;
        let registros = document.getElementById("registros").value;
        let pagina = document.getElementById("pagina").value;
        if( pagina == null ){
            pagina = 1;
        } 

        let orderType = document.getElementById("orderType").value;
        let orderCol = document.getElementById("orderCol").value;

        formData = new FormData();
        formData.append('categoria', categoria);
        formData.append('producto_id', 2);
        formData.append('pagina', pagina);
        formData.append('registros', registros);
        formData.append('campo', input);
        formData.append('orderType', orderType);
        formData.append('orderCol', orderCol);

        fetch("./../ajax_busquedas/tipos_tabla.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                document.getElementById('clase').value = data.clase;
                document.getElementById('tabla_prod').style.display = "table";
                document.getElementById('tabla_prod_codigos').style.display = "none";
                document.getElementById('tipos').style.display = "table";
                document.getElementById('resultados_tipos').innerHTML = data.data;
                if(data.totalFiltro != data.totalRegistros){
                    document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                }
                else {
                    document.getElementById('lbl-total').innerHTML = "";
                }
                document.getElementById('paginacion').innerHTML =  data.paginacion;
                history.replaceState(null, "", `./p_aire.php?categoria=${categoria}&clase=${data.clase}`);
            }
            
        )
        .catch(
            error => console.log(error)
        )
    }

    /*---------------Cambiar de Pagina-------------------*/ 
    function cambiarPagina(pagina){
        document.getElementById('pagina') = pagina;
        getData();
    }

    /*--------Ordenar Tabla Tipos--------------------*/ 
    function ordenar(e){
        let elemento = e.target;
        document.getElementById('orderCol').value = elemento.cellIndex;

        if( elemento.classList.contains('ASC') ){
            document.getElementById('orderType').value = 'ASC';
            elemento.classList.remove('ASC');
            elemento.classList.add('DESC');
        }
        else{
            document.getElementById('orderType').value = 'DESC';
            elemento.classList.remove('DESC');
            elemento.classList.add('ASC');
        }

        getData();
    }

    /*------------------------FUNCTIONES DE LAS FILTROS-------------------*/

    /*--------------Hacer la busqueda de los filtros--------------------*/
    function getFiltro(){
        let categoria = document.getElementById("categoriaSeleccionada").value;
        let clase = document.getElementById("clase").value;
        let tipo = document.getElementById("tipoSeleccionado").value;
        let input = document.getElementById("campo_codigos").value;
        let registros = document.getElementById("registros_codigos").value;
        let pagina = document.getElementById("paginaFiltro").value;
        if( pagina == null ){
            pagina = 1;
        } 

        let orderType = document.getElementById("orderTypeFiltro").value;
        let orderCol = document.getElementById("orderColFiltro").value;

        formData = new FormData();
        formData.append('categoria', categoria);
        formData.append('tipo', tipo);
        formData.append('producto_id', 2);
        formData.append('pagina', pagina);
        formData.append('registros', registros);
        formData.append('campo', input);
        formData.append('clase', clase);
        formData.append('orderType', orderType);
        formData.append('orderCol', orderCol);

        fetch("./filtro_tabla.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                clase = data.clase;
                cambiarTabla(clase);
                document.getElementById('tabla_prod_codigos').style.display = "table";
                document.getElementById('tabla_prod').style.display = "none";
                document.getElementById('resultados_tipos_codigos').innerHTML = data.data;
                if(data.totalFiltro != data.totalRegistros){
                    document.getElementById("lbl-total_codigos").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                }
                else {
                    document.getElementById('lbl-total_codigos').innerHTML = "";
                }
                document.getElementById('paginacion_codigos').innerHTML =  data.paginacion;
                
                history.replaceState(null, "", `./p_aire.php?categoria=${categoria}&tipo=${tipo}&clase=${clase}`);
            }
            
        )
        .catch(
            error => console.log(error)
        )
    }

    /*--------------Al pulsar tipo--------------------*/
    function pulsarTipo(tipo){
        document.getElementById('tipoSeleccionado').value = tipo;
        getFiltro();
    }

    /*---------------Cambiar de Pagina de Filtro-------------------*/ 
    function cambiarPaginaFiltro(pagina){
        document.getElementById('paginaFiltro').value = pagina;
        getFiltro();
    }

    /*---------Mostrar la Tabla de la Clase Correspondiente--------*/
    function cambiarTabla(linea){
        clases = ['aireautomotriz', 'aireindustrial', 'combustiblelinea', 'elemento', 'panel', 'sellado', 'fluidos'];

        clases.forEach( (clase) => {
            if( linea == clase ){
                document.getElementById(clase + '_codigos').style.display = 'contents';
            }
            else {
                document.getElementById(clase + '_codigos').style.display = 'none';
            }
        })
    }

    /*--------Ordenar Tabla Filtros--------------------*/ 
    function ordenarFiltro(e){
        let elemento = e.target;
        document.getElementById('orderColFiltro').value = elemento.cellIndex;

        if( elemento.classList.contains('ASC') ){
            document.getElementById('orderTypeFiltro').value = 'ASC';
            elemento.classList.remove('ASC');
            elemento.classList.add('DESC');
        }
        else{
            document.getElementById('orderTypeFiltro').value = 'DESC';
            elemento.classList.remove('DESC');
            elemento.classList.add('ASC');
        }

        getFiltro();
    }
</script>

<?php 
    if( isset($_GET['tipo']) ){
        $categoria = htmlspecialchars( $_GET['categoria'] );
        $tipo = htmlspecialchars( $_GET['tipo'] );
    }
    if( isset($_GET['categoria']) ){
        $categoria = htmlspecialchars( $_GET['categoria'] );
    }
    if( isset($_GET['clase']) ){
        $clase = htmlspecialchars( $_GET['clase'] );
    }

    if( isset($_GET['categoria']) && isset($_GET['tipo']) ){
        ?>
            <script>
                categoria = '<?php echo $categoria ?>';
                tipo = '<?php echo $tipo ?>';
                clase = '<?php echo $clase ?>';
                document.getElementById('tabla_prod').style.display = "none";
                document.getElementById('tabla_prod_codigos').style.display = "contents";

                document.getElementById('categoriaSeleccionada').value = categoria;
                document.getElementById('tipoSeleccionado').value = tipo;
                document.getElementById('clase').value = clase;
                getFiltro();
            </script>
        <?php
    }
    else if( isset($_GET['categoria']) && isset($_GET['clase']) ){
        ?>
        <script>
            categoria = '<?php echo $categoria ?>';
            clase = '<?php echo $clase ?>';
            document.getElementById('tabla_prod').style.display = "contents";
            document.getElementById('tabla_prod_codigos').style.display = "none";

            document.getElementById('categoriaSeleccionada').value = categoria;
            document.getElementById('clase').value = clase;
            getData();
        </script>
    <?php
    }
?>