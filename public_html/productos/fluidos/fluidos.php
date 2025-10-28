<?php
    $sql = "SELECT cat.categoria FROM categorias as cat
            JOIN productos as p ON p.id = cat.producto_id
            WHERE ( p.nombre = 'Fluidos' ) and ( cat.deleted_at is null ) and ( p.deleted_at is null )
            GROUP BY cat.categoria";
    $categorias_selec = $base_de_datos->prepare($sql);
    $categorias_selec->setFetchMode( PDO::FETCH_ASSOC );
    $categorias_selec->execute();
    while( $fila = $categorias_selec->fetch() ){
        $categorias []= $fila;
    }
?>

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-lg-5 col-md-12">
            <div id="vertipos">
                <div class="row row-cols-1 g-3">
                    <?php
                    foreach( $categorias as $categoria ){
                        $cat = trim($categoria['categoria']);
                    ?>
                        <div class="col">
                            <a href="#vertipos" class="text-decoration-none text-dark card-hover-effect" data-category="<?php echo $cat; ?>">
                                <div class="card card-produ card-custom h-100 border-0 d-flex flex-row align-items-center p-3">
                                    <div class="col-2">
                                        <?php
                                        $image_src = '';
                                        $alt_text = '';
                                        if ($cat == "REFRIGERANTE") {
                                            $image_src = $loc . 'img/productos/refrigerante.png';
                                            $alt_text = 'FILTRO TIPO REFRIGERANTE';
                                        } else {
                                            $image_src = $loc . 'img/productos/w-2010.png';
                                        }
                                        ?>
                                        <img src="<?php echo $image_src; ?>" class="img-fluid" data-value="<?php echo $cat; ?>" alt="<?php echo $alt_text; ?>">
                                    </div>
                                    <div class="col-8">
                                        <h5 class="card-title-custom fw-bold Roboto-Bold mb-0 ms-3" data-value="<?php echo $cat; ?>"><?php echo $categoria['categoria']; ?></h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <input type="hidden" id="clase" value="" >
            <input type="hidden" id="categoriaSeleccionada" value="" >
            <input type="hidden" id="pagina" value="1" >
            <input type="hidden" id="orderCol" value="0" >
            <input type="hidden" id="orderType" value="ASC" >
        </div>
        <div class="col-lg-7 col-ms-12 ms-lg-auto">
            <div id="tabla_prod" style="display: block;">
                <div class="container my-5">
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <label for="registros" class="me-2 text-nowrap">Mostrar:</label>
                            <select id="registros" class="form-select flex-grow-1">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="text" id="texto" name="texto" class="form-control">
                        </div>
                    </div>
                </div>
                <table class="pro table-responsive ms-auto" id="tipos" >
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
            <div id="tabla_prod_codigos" style="display: block;" >
                <div class="container my-5">
                    <div class="row align-items-center g-3">
                        <div class="col-12 col-md-6 d-flex align-items-center">
                            <label for="registros_codigos" class="me-2 text-nowrap">Mostrar:</label>
                            <select id="registros_codigos" class="form-select flex-grow-1">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <input type="text" id="campo_codigos" name="texto" class="form-control">
                        </div>
                    </div>
                </div>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {

        
        document.getElementById('tabla_prod').style.display = 'none';
        document.getElementById('tabla_prod_codigos').style.display = 'none';

        //Cambiar Campo de Texto
        document.getElementById("texto").addEventListener("keyup", function(){
            getData();
        }, false);

        //Cambiar Numero de Registros
        document.getElementById("registros").addEventListener("change", function(){
            getData();
        }, false);

        //Cambiar Campo de Texto en Tabla tipo
        document.getElementById("campo_codigos").addEventListener("keyup", function(){
            getFiltro();
        }, false);

        //Cambiar Numero de Registros en Tabla Tipo
        document.getElementById("registros_codigos").addEventListener("change", function(){
            getFiltro();
        }, false);

        //Click sobre las tarjetas de categoría
        const categoriaCards = document.querySelectorAll('.card-hover-effect');
        categoriaCards.forEach(card => {
            card.addEventListener('click', pulsarCategoria);
        });

        //click sobre el tipo se ordene
        let columns = document.getElementsByClassName('sort');
        tamanio = columns.length;
        for(let i = 0; i < tamanio; i++){
            columns[i].addEventListener('click', ordenar);
        }
        

        //click sobre el tipo se ordene
        let columns_filtros = document.getElementsByClassName('sort_filtro');
        tamanio = columns_filtros.length;
        for(let i = 0; i < tamanio; i++){
            columns_filtros[i].addEventListener('click', ordenarFiltro);
        }
    })

    /*-------------FUNCIONES DE LOS TIPOS-------------------------*/

    /*----------------Al pulsar categoria-------------------*/
    function pulsarCategoria(e){
    // Evita que el evento se propague si se hace clic en un elemento interno
    e.preventDefault();
    
    let target = e.currentTarget; // El elemento 'a' actual que maneja el evento
    let categoria_selec = target.getAttribute('data-category');
    document.getElementById('categoriaSeleccionada').value = categoria_selec;

    //Elimina la clase 'active' de todas las tarjetas
    let cards = document.querySelectorAll('.card-produ');
    cards.forEach(card => card.classList.remove('active'));

    // Agrega la clase 'active' a la tarjeta que se hizo clic
    target.querySelector('.card-produ').classList.add('active');

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
        formData.append('producto_id', 3);
        formData.append('pagina', pagina);
        formData.append('registros', registros);
        formData.append('campo', input);
        formData.append('orderType', orderType);
        formData.append('orderCol', orderCol);

        fetch("./../../ajax_busquedas/tipos_tabla.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                document.getElementById('clase').value = data.clase;
                document.getElementById('tabla_prod').style.display = "block";
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
                history.replaceState(null, "", `./index.php?categoria=${categoria}&clase=${data.clase}`);
            }
        )
        .catch(
            error => console.log(error)
        )
    }

    /*---------------Cambiar de Pagina-------------------*/
    function cambiarPagina(pagina){
        document.getElementById('pagina').value = pagina;
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
        formData.append('producto_id', 3);
        formData.append('pagina', pagina);
        formData.append('registros', registros);
        formData.append('campo', input);
        formData.append('clase', clase);
        formData.append('orderType', orderType);
        formData.append('orderCol', orderCol);

        fetch("./../filtro_tabla.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                clase = data.clase;
                cambiarTabla(clase);
                document.getElementById('tabla_prod_codigos').style.display = "block";
                document.getElementById('tabla_prod').style.display = "none";
                document.getElementById('resultados_tipos_codigos').innerHTML = data.data;
                if(data.totalFiltro != data.totalRegistros){
                    document.getElementById("lbl-total_codigos").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                }
                else {
                    document.getElementById('lbl-total_codigos').innerHTML = "";
                }
                document.getElementById('paginacion_codigos').innerHTML = data.paginacion;
                
                history.replaceState(null, "", `./index.php?categoria=${categoria}&tipo=${tipo}&clase=${clase}`);
            }
        )
        .catch(
            error => console.log(error)
        )
    }

    /*--------------Al seleccionar el tipo--------------------*/
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
            document.getElementById('tabla_prod_codigos').style.display = "block";

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
            document.getElementById('tabla_prod').style.display = "block";
            document.getElementById('tabla_prod_codigos').style.display = "none";

            document.getElementById('categoriaSeleccionada').value = categoria;
            document.getElementById('clase').value = clase;
            getData();
        </script>
<?php
    }
?>