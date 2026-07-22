<?php
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar - Combustible en Linea"; 

     require_once("../index/header.php");
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: espec_combustiblelinea.php");
    }
    else {
        $id = $_REQUEST['id'];
    }

    //Se incluyen los archivos necesarios

    include_once('../../../config/conexion.php');

    //Se incluyen las alertas a verificar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_codigo_existe.php');

    //Si hubo algún error al hacer una edición del filtro o al eliminar la imagen
    alerta_error();
    //Si el código de filtro que se intento subir ya existe
    alerta_codigo_existe();

    //Guarda los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT e_c.id_codigo, e_c.codigo, e_c.tipo, f_c.id_tipo, f_c.filtracion, e_c.diametroext, e_c.altura, e_c.entrada, e_c.salida, f_c.und_empaque, e_c.detalle1, e_c.detalle2, e_c.id_rosca_entrada, e_c.id_rosca_salida, e_c.id_pulgada_entrada, e_c.id_pulgada_salida
                                                FROM espec_combustiblelinea as e_c
                                                JOIN filtro_codificacion as f_c ON f_c.id = e_c.id_codigo
                                                WHERE e_c.id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $combustible_linea []= $fila;
    }    
    
    //Guarda las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                FROM espec_combustiblelinea
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT); 
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);

    //Los datos del filtro se ponen en variables
    foreach($combustible_linea as $combustible){
        $id_codigo = $combustible['id_codigo'];
        $codigo = $combustible['codigo'];
        $tipo = $combustible['tipo'];
        $id_tipo = $combustible['id_tipo'];
        $filtracion_seleccionada = $combustible['filtracion'];
        $diametro_exterior = $combustible['diametroext'];
        $altura = $combustible['altura'];
        $entrada = $combustible['entrada'];
        $salida = $combustible['salida'];
        $und_empaque = $combustible['und_empaque'];
        $detalle1 = ( $combustible['detalle1'] == '' ) ? 'N/D' : $combustible['detalle1'];
        $detalle2 = ( $combustible['detalle2'] == '' ) ? 'N/D' : $combustible['detalle2'];
        $id_rosca_entrada_actual = $combustible['id_rosca_entrada'];
        $id_rosca_salida_actual = $combustible['id_rosca_salida'];
        $id_pulgada_entrada_actual = $combustible['id_pulgada_entrada'];
        $id_pulgada_salida_actual = $combustible['id_pulgada_salida'];
    }

    $sw_entrada_init = ($id_rosca_entrada_actual > 0) ? 'si' : 'no';
    $sw_salida_init = ($id_rosca_salida_actual > 0) ? 'si' : 'no';

    $codigoActual = htmlspecialchars($codigo);

    // Buscar codigo de barra
    $sql = "SELECT * FROM filtro_codificacion  WHERE id = :id_codigo";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR );
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $barra = $seleccionado->fetch();
    $codigo_barra = $barra['codigo_barra'];

    //Devuelve $tipos_seleccionado (Objeto) con el id, tipo y categoria_id del tipo de filtro
    include_once('./../componentes/consultaSelectTipo.php');

    //En base al id del tipo, se busca la categoria a la que pertenece
    //Y todos los tipos de esa categoria
    //Si el id del tipo es N/D no devolvera nada
    include_once('./../componentes/consultaTipoYCategoria.php');

    /* Se incluye una funcion que busca todos las categorias de la clase */
    include_once('./../componentes/consultaCategoriasDeUnProducto.php');
    //Primer Parametro el Nombre de la Clase
    //Segundo Parametro la variable de conexion a la base de datos
    $categorias = categoriaDeUnProducto('Combustible en Linea', $base_de_datos);

    //Todos los tipos de filtraciones
    $filtraciones = ['Ultrafino', 'Fino', 'Estándar', 'Grueso'];


    $query_roscas = $base_de_datos->prepare("SELECT id, codigo FROM roscas WHERE deleted_at IS NULL ORDER BY codigo ASC");
$query_roscas->execute();
$lista_roscas = $query_roscas->fetchAll(PDO::FETCH_ASSOC);

$query_pulgadas = $base_de_datos->prepare("SELECT id, codigo FROM pulgadas WHERE deleted_at IS NULL ORDER BY codigo ASC");
$query_pulgadas->execute();
$lista_pulgadas = $query_pulgadas->fetchAll(PDO::FETCH_ASSOC); // Corregido
    
?>


<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Actualizar Datos de Productos de Combustible en Linea</h1>
        </div>

       <a href="./espec_combustiblelinea.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">
       <div class="card h-100 mb-5">
            <div class="card-body">
             <div class="row">
               <div class="col-12 col-md-6">
            <form action="update.php" id="form-especificacion" method="POST" class="form_login" enctype="multipart/form-data">
                <input type="hidden" id="codigo_actual" name="codigo_actual" value="<?php echo $codigoActual; ?>">
                <input type="hidden" name="id_codigo" value="<?php echo $id_codigo; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                 <table class="table table-striped table-hover table-responsive dataTable">
                    <tr>
                        <th> Código: </th>
                        <td>  
                            <input class="form-control" type="text" value="<?php echo $codigo ?>" name="codigo" id="codigo" required/>
                        </td>
                    </tr>
                    <tr>
                        <th>Categoría:</th>
                        <td>
                            <select name="categoria" id="categoria" class="form-select" >
                            <option value="" selected >Categoría -- Clasificacion</option>
                            <?php
                                foreach( $categorias as $categoria ){
                                    if( $categoria['id'] == $categoria_seleccionada['id'] ){
                                        ?>
                                            <option value="<?php echo $categoria_seleccionada['id']; ?>" selected><?php echo $categoria_seleccionada['categoria'] . ' -- ' . $categoria['nombre']; ?></option>
                                        <?php
                                    }
                                    else {
                                        ?>
                                            <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria'] . ' -- ' . $categoria['nombre']; ?></option>
                                        <?php
                                    }
                                }
                            ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Tipo: </th>
                        <td>
                            <select name="tipo" id="tipo" class="form-select" required>
                                <option value="" selected disabled>¿Cuál es el tipo?</option>
                                <option value="N/D" >N/D</option>
                                <?php
                                    foreach( $tipos as $tipo ){
                                        if( $tipo['id'] == $tipos_seleccionado['id'] ){
                                            ?>
                                                <option value="<?php echo $tipos_seleccionado['id']; ?>" selected><?php echo $tipos_seleccionado['tipo']; ?></option>
                                            <?php
                                        }
                                        else {
                                            ?>
                                                <option value="<?php echo $tipo['id']; ?>" ><?php echo $tipo['tipo']; ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <th>Filtración: (Opcional)</th>
                        <td>
                            <select name="filtracion" id="filtracion" class="form-select" >
                                <option value="" selected >¿Cuál es la filtración?</option>
                                <?php
                                    foreach( $filtraciones as $filtracion ){
                                        if( $filtracion == $filtracion_seleccionada ){
                                            ?>
                                                <option value="<?php echo $filtracion_seleccionada; ?>" selected><?php echo $filtracion_seleccionada; ?></option>
                                            <?php
                                        }
                                        else {
                                            ?>
                                                <option value="<?php echo $filtracion; ?>"><?php echo $filtracion; ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Diámetro Exterior:</th>
                        <td> 
                            <input class="form-control" type="number" value="<?php echo $diametro_exterior ?>" name="diametro_ext" id="diametro_ext" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required />
                        </td>  
                    </tr>
                    <tr>
                        <th>Altura:</th>
                        <td> 
                            <input class="form-control" type="number" value="<?php echo $altura ?>" name="altura" id="altura" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required />
                        </td>  
                    </tr>
                    <?php
                    $tipo_entrada_init = ($id_rosca_entrada_actual > 0) ? 'rosca' : (($id_pulgada_entrada_actual > 0) ? 'pulgada' : 'mm');
                    $tipo_salida_init = ($id_rosca_salida_actual > 0) ? 'rosca' : (($id_pulgada_salida_actual > 0) ? 'pulgada' : 'mm');
                    ?>
<tr>
    <th>Tipo de Entrada</th>
    <td>
        <select id="sw_entrada" name="sw_entrada" class="form-select" onchange="toggleEntrada()">
            <option value="rosca" <?php echo ($tipo_entrada_init == 'rosca') ? 'selected' : ''; ?>>Rosca</option>
            <option value="mm" <?php echo ($tipo_entrada_init == 'mm') ? 'selected' : ''; ?>>Diámetro (MM)</option>
            <option value="pulgada" <?php echo ($tipo_entrada_init == 'pulgada') ? 'selected' : ''; ?>>Pulgada</option>
        </select>
    </td>
</tr>

<tr id="fila_entrada_rosca" style="<?php echo ($tipo_entrada_init == 'rosca') ? '' : 'display:none;'; ?>">
    <th>Rosca Entrada:</th>
    <td>
        <select name="id_rosca_entrada" class="form-select">
            <option value="0">Seleccione una rosca</option>
            <?php foreach($lista_roscas as $rosca): ?>
                <option value="<?php echo $rosca['id']; ?>" <?php echo ($rosca['id'] == $id_rosca_entrada_actual) ? 'selected' : ''; ?>>
                    <?php echo $rosca['codigo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>

<tr id="fila_entrada_mm" style="<?php echo ($tipo_entrada_init == 'mm') ? '' : 'display:none;'; ?>">
    <th>Entrada (MM):</th>
    <td><input type="number" name="entrada" value="<?php echo $entrada; ?>" step=".01" class="form-control"></td>
</tr>

<tr id="fila_entrada_pulgada" style="<?php echo ($tipo_entrada_init == 'pulgada') ? '' : 'display:none;'; ?>">
    <th>Entrada (Pulgada):</th>
    <td>
        <select name="id_pulgada_entrada" class="form-select">
            <option value="0">Seleccione una medida</option>
            <?php foreach($lista_pulgadas as $p): ?>
                <option value="<?php echo $p['id']; ?>" <?php echo ($p['id'] == $id_pulgada_entrada_actual) ? 'selected' : ''; ?>>
                    <?php echo $p['codigo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>

<tr>
    <th>Tipo Salida</th>
    <td>
        <select id="sw_salida" name="sw_salida" class="form-select" onchange="toggleSalida()">
            <option value="rosca" <?php echo ($tipo_salida_init == 'rosca') ? 'selected' : ''; ?>>Rosca</option>
            <option value="mm" <?php echo ($tipo_salida_init == 'mm') ? 'selected' : ''; ?>>Diámetro (MM)</option>
            <option value="pulgada" <?php echo ($tipo_salida_init == 'pulgada') ? 'selected' : ''; ?>>Pulgada</option>
        </select>
    </td>
</tr>

<tr id="fila_salida_rosca" style="<?php echo ($tipo_salida_init == 'rosca') ? '' : 'display:none;'; ?>">
    <th>Rosca Salida:</th>
    <td>
        <select name="id_rosca_salida" class="form-select">
            <option value="0">Seleccione una rosca</option>
            <?php foreach($lista_roscas as $rosca): ?>
                <option value="<?php echo $rosca['id']; ?>" <?php echo ($rosca['id'] == $id_rosca_salida_actual) ? 'selected' : ''; ?>>
                    <?php echo $rosca['codigo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>

<tr id="fila_salida_mm" style="<?php echo ($tipo_salida_init == 'mm') ? '' : 'display:none;'; ?>">
    <th>Salida (MM):</th>
    <td>
        <input type="number" name="salida" value="<?php echo $salida; ?>" step=".01" class="form-control" onblur="this.value = parseFloat(this.value).toFixed(2)">
    </td>
</tr>

<tr id="fila_salida_pulgada" style="<?php echo ($tipo_salida_init == 'pulgada') ? '' : 'display:none;'; ?>">
    <th>Salida (Pulgada):</th>
    <td>
        <select name="id_pulgada_salida" class="form-select">
            <option value="0">Seleccione una medida</option>
            <?php foreach($lista_pulgadas as $p): ?>
                <option value="<?php echo $p['id']; ?>" <?php echo ($p['id'] == $id_pulgada_salida_actual) ? 'selected' : ''; ?>>
                    <?php echo $p['codigo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>
                    <tr>
                        <th>Unidades de Empaque: (Opcional)</th>
                        <td>
                            <input type="number" class="form-control" id="und_empaque" value="<?php echo $und_empaque ?>" name="und_empaque" min="0" >
                        </td>
                    </tr>
                    <tr>
                        <th>Detalle 1: (Opcional)</th>
                        <td>  
                            <input class="form-control" type="text" value="<?php echo $detalle1 ?>" name="detalle1" id="detalle1" />
                        </td>  
                    </tr>
                    <tr>
                        <th>Detalle 2: (Opcional)</th>
                        <td>  
                            <input class="form-control" type="text" value="<?php echo $detalle2 ?>" name="detalle2" id="detalle2" />
                        </td>  
                    </tr>
                     <tr>
                            <th>Codigo  de Barra</th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $codigo_barra ?>" name="codigo_barra" id="odigo_barra" />
                            </td>  
                        </tr>
                    <tr>
                        <td class="b_td">
                            <input type="submit" value="Guardar" name="btnimg" class="btn-icon" />
                        </td>
                    </tr>
                </table> 
            </div>
            <div class="col-12 col-md-6">
            <?php 
                include_once('./../componentes/galeria_editar.php'); //Se incluye el componete para ver y modifcar las imagenes
            ?>
        </form>
  </div>
                </div>
            </div>
        </div>
   
</div>
    <?php
            include('../index/footer.php');
    ?>

    <script src="./../js/comprobar_imagen.js"></script>  <!-- Función que comprueba que la imagen es del tamaño adecuado -->
    <script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
    <script src="./../js/funciones/cambiar_categoria.js" ></script> <!-- Detecta que hubo un cambio de categoria y trae los tipos de esa categoria -->
    <script src="./../js/funciones/evento_tecla_empaque.js" ></script> <!-- Detecta que se pulso una tecla en unidades de empaque -->
    <script src="./../js/funciones/filterInteger.js"></script> <!-- Funcion para verificar que el valor ingresado es entero -->
    <script src="./../js/funciones/filter.js"></script> <!-- Funcion para verificar que el valor ingresado es entero positivo -->

   <script>
function toggleEntrada() {
    let tipo = document.getElementById('sw_entrada').value;
    
    // Ocultar todas las filas
    let filas = ['fila_entrada_rosca', 'fila_entrada_mm', 'fila_entrada_pulgada'];
    filas.forEach(id => {
        let el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    // LIMPIEZA SEGURA: Solo limpiar si el elemento existe en el DOM
    if (tipo !== 'rosca') {
        let el = document.querySelector('select[name="id_rosca_entrada"]');
        if (el) el.value = "0";
    }
    if (tipo !== 'mm') {
        let el = document.querySelector('input[name="entrada"]');
        if (el) el.value = "0.00";
    }
    if (tipo !== 'pulgada') {
        let el = document.querySelector('select[name="id_pulgada_entrada"]');
        if (el) el.value = "0";
    }

    // Mostrar solo la seleccionada
    let mostrar = document.getElementById('fila_entrada_' + tipo);
    if (mostrar) mostrar.style.display = 'table-row';
}

function toggleSalida() {
    let tipo = document.getElementById('sw_salida').value;
    
    // Ocultar todas
    let filas = ['fila_salida_rosca', 'fila_salida_mm', 'fila_salida_pulgada'];
    filas.forEach(id => {
        let el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });

    // LIMPIEZA SEGURA
    if (tipo !== 'rosca') {
        let el = document.querySelector('select[name="id_rosca_salida"]');
        if (el) el.value = "0";
    }
    if (tipo !== 'mm') {
        let el = document.querySelector('input[name="salida"]');
        if (el) el.value = "0.00";
    }
    if (tipo !== 'pulgada') {
        let el = document.querySelector('select[name="id_pulgada_salida"]');
        if (el) el.value = "0";
    }

    // Mostrar solo la seleccionada
    let mostrar = document.getElementById('fila_salida_' + tipo);
    if (mostrar) mostrar.style.display = 'table-row';
}
</script>