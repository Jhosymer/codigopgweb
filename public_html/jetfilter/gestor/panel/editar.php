<?php
    $loc = "../../../";
     $locj = "../../";
     $title = "Editar - Panel";
     require_once("../index/header.php");  
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: espec_aireautomotriz.php");
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
    $seleccionado = $base_de_datos->prepare("SELECT e_p.id_codigo, e_p.codigo, e_p.tipo, f_c.id_tipo, f_c.filtracion, e_p.largo, e_p.ancho, e_p.altura, f_c.und_empaque, e_p.detalle1, e_p.detalle2
                                            FROM espec_panel as e_p
                                            JOIN filtro_codificacion as f_c ON f_c.id = e_p.id_codigo 
                                                WHERE e_p.id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                              
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $panel []= $fila;
    }    
   
    //Guarda las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                FROM espec_panel 
                                                WHERE id = :id") or die('Error al eliminar');
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT);                                             
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);

    //Los datos del filtro se ponen en variables
    foreach($panel as $pan){
        $id_codigo = $pan['id_codigo'];
        $codigo = $pan['codigo'];
        $tipo = $pan['tipo'];
        $id_tipo = $pan['id_tipo'];
        $filtracion_seleccionada = $pan['filtracion'];
        $largo = $pan['largo'];
        $ancho = $pan['ancho'];
        $altura = $pan['altura'];
        $und_empaque = $pan['und_empaque'];
        $detalle1 = ( $pan['detalle1'] == '' ) ? 'N/D' : $pan['detalle1'];
        $detalle2 = ( $pan['detalle2'] == '' ) ? 'N/D' : $pan['detalle2'];
    }

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
    $categorias = categoriaDeUnProducto('Panel', $base_de_datos);

    //Todos los tipos de filtraciones
    $filtraciones = ['Ultrafino', 'Fino', 'Estándar', 'Grueso'];
?>


           
      
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Actualizar Datos de Productos de Panel</h1>
        </div>

       <a href="./espec_panel.php"  class="btn-icon me-4" >Atrás</a>
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
                        <input type="hidden" value="<?php echo $id ?>" name="id" >
                        <tr>
                            <th> Codigo: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $codigo ?>" name="codigo" id="codigo" required/>
                            </td>
                        </tr>
                        <tr>
                            <th>Categoria:</th>
                            <td>
                                <select name="categoria" id="categoria" class="form-select" >
                                <option value="" selected >Categoria -- Clasificación</option>
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
                            <th>Largo: </th>
                            <td>
                                <input class="form-control" type="number" value="<?php echo $largo ?>" name="largo" id="largo" min="0" step=".01" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Ancho: </th>
                            <td>
                                <input class="form-control" type="number" value="<?php echo $ancho ?>" name="ancho" id="ancho" min="0" step=".01" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Altura: </th>
                            <td>
                                <input class="form-control" type="number" value="<?php echo $altura ?>" name="altura" id="altura" min="0" step=".01" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Unidades de Empaque: (Opcional)</th>
                            <td>
                                <input type="number" class="form-select"  id="und_empaque" value="<?php echo $und_empaque; ?>" name="und_empaque" min="0" >
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

    <script src="./../js/comprobar_imagen.js"></script>
    <script src="./../js/colocar_validacion.js"></script>
    <script src="./../js/comprobar_codigo_repetido_Editar.js"></script>
    <script src="./../js/funciones/filterInteger.js"></script>
    <script src="./../js/funciones/filter.js"></script>

    <script>
    categoria = document.getElementById('categoria');
    categoria.addEventListener('change', function(){

        value = document.getElementById('categoria').value;
        formData = new FormData(); 
        formData.append('categoria', value);

        fetch("./../plantillas/cambiar_categoria.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                document.getElementById("tipo").innerHTML = "<option value=''>N/D</option>" + data;
            }
        )
        .catch(
            error => console.log(error)
        )
   });

   document.getElementById('und_empaque').addEventListener('keypress', function(evt) {
        /*-----------SI SUCEDE EL EVENTO----------------*/
        /*-------SE GUARDA EL EVENTO Y EL CAMPO--------*/
        if (filterInteger(evt, evt.target) === false) {
            /*Se evita que el evento suceda */
            evt.preventDefault();
        }
    });
</script>