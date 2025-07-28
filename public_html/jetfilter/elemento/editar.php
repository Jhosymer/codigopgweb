<?php 
    //Si no existe id te redirigirá a otra ventana
    if( !isset( $_REQUEST['id'] ) ){
        header("location: espec_elemento.php");
    }
    else {
        $id = $_REQUEST['id'];
    }

    //Se incluyen los archivos necesarios
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    //Se incluyen las alertas a verificar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_codigo_existe.php');

    //Si hubo algún error al hacer una edición del filtro o al eliminar la imagen
    alerta_error();
    //Si el código de filtro que se intento subir ya existe
    alerta_codigo_existe();

    //Guarda los datos del filtro
    $seleccionado = $base_de_datos->prepare("SELECT e_e.id_codigo, e_e.codigo, e_e.tipo, f_c.id_tipo, f_c.filtracion, e_e.diametroext1, e_e.diametroint1, e_e.diametroint2, e_e.altura, f_c.und_empaque, e_e.detalle1, e_e.detalle2
                                                        FROM espec_elemento as e_e
                                                        JOIN filtro_codificacion as f_c ON f_c.id = e_e.id_codigo
                                                        WHERE e_e.id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT); 
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $elemento []= $fila;
    }    
   
    //Guarda las imagenes del filtro
    $seleccionado_imagen = $base_de_datos->prepare("SELECT imagen, imagen1, imagen2, imagen3 
                                                FROM espec_elemento 
                                                WHERE id = :id") or die('Error al eliminar'); 
    $seleccionado_imagen->bindParam(':id', $id, PDO::PARAM_INT); 
    $seleccionado_imagen->execute();
    $imagenes = $seleccionado_imagen->fetch(PDO::FETCH_BOTH);

    //Los datos del filtro se ponen en variables
    foreach($elemento as $elem){
        $id_codigo = $elem['id_codigo'];
        $codigo = $elem['codigo'];
        $tipo = $elem['tipo'];
        $id_tipo = $elem['id_tipo'];
        $filtracion_seleccionada = $elem['filtracion'];
        $diametro_exterior1 = $elem['diametroext1'];
        $diametro_int1 = $elem['diametroint1'];
        $diametro_int2 = $elem['diametroint2'];
        $altura = $elem['altura'];
        $und_empaque = $elem['und_empaque'];
        $detalle1 = ( $elem['detalle1'] == '' ) ? 'N/D' : $elem['detalle1'];
        $detalle2 = ( $elem['detalle2'] == '' ) ? 'N/D' : $elem['detalle2'];
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
    $categorias = categoriaDeUnProducto('Elemento', $base_de_datos);

    //Todos los tipos de filtraciones
    $filtraciones = ['Ultrafino', 'Fino', 'Estándar', 'Grueso'];
?>
    <title>Actualizar Datos de Productos de Elemento</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Datos de Productos de Elemento</p>
            </div>
            <div class="tex_tablas">
                <a href="./espec_elemento.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">    
                <form action="update.php" id="form-especificacion" method="POST" class="form_login" enctype="multipart/form-data">
                    <input type="hidden" id="codigo_actual" name="codigo_actual" value="<?php echo $codigoActual; ?>">
                    <input type="hidden" name="id_codigo" value="<?php echo $id_codigo; ?>">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="tabla_edi">
                        <tr>
                            <th> Código: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $codigo ?>" name="codigo" id="codigo" required/>
                            </td>
                        </tr>
                        <tr>
                        <th>Categoría:</th>
                            <td>
                                <select name="categoria" id="categoria" class="selectdis" >
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
                                <select name="tipo" id="tipo" class="selectdis" required>
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
                                <select name="filtracion" id="filtracion" class="selectdis" >
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
                            <th>Diámetro Exterior 1: </th>
                            <td>
                                <input class="edi_inp" type="number" value="<?php echo $diametro_exterior1 ?>" name="diametro_ext1" id="diametro_ext1" min="0" step=".01" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Diámetro Interior 1:</th>
                            <td>
                                <input class="edi_inp" type="number" value="<?php echo $diametro_int1 ?>" name="diametro_int1" id="diametro_int1" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Diámetro Interior 2: </th>
                            <td>
                                <input class="edi_inp" type="number" value="<?php echo $diametro_int2 ?>" name="diametro_int2" id="diametro_int2" min="0" step=".01" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Altura: </th>
                            <td>
                                <input class="edi_inp" type="number" value="<?php echo $altura ?>" name="altura" id="altura" min="0" step=".01" required/>
                            </td>  
                        </tr>
                        <tr>
                            <th>Unidades de Empaque: (Opcional)</th>
                            <td>
                                <input type="number" id="und_empaque" value="<?php echo $und_empaque ?>" name="und_empaque" min="0" >
                            </td>
                        </tr>
                        <tr>
                            <th>Detalle 1: (Opcional)</th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $detalle1 ?>" name="detalle1" id="detalle1" />
                            </td>  
                        </tr>
                        <tr>
                            <th>Detalle 2: (Opcional)</th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $detalle2 ?>" name="detalle2" id="detalle2" />
                            </td>  
                        </tr>
                         <tr>
                            <th>Codigo  de Barra</th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $codigo_barra ?>" name="codigo_barra" id="odigo_barra" />
                            </td>  
                        </tr>
                        <tr>
                            <td class="b_td">
                                <input type="submit" value="Guardar" name="btnimg" class="boton" />
                            </td>
                        </tr>
                    </table> 
                </div>
                <?php 
                    include_once('./../componentes/galeria_editar.php'); //Se incluye el componete para ver y modifcar las imagenes
                ?>
        </form>
    </section>
</section>

    <?php
        include('./../abajo_carpeta.html')
    ?>

    <script src="./../js/comprobar_imagen.js"></script>  <!-- Función que comprueba que la imagen es del tamaño adecuado -->
    <script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
    <script src="./../js/funciones/cambiar_categoria.js" ></script> <!-- Detecta que hubo un cambio de categoria y trae los tipos de esa categoria -->
    <script src="./../js/funciones/evento_tecla_empaque.js" ></script> <!-- Detecta que se pulso una tecla en unidades de empaque -->
    <script src="./../js/funciones/filterInteger.js"></script> <!-- Funcion para verificar que el valor ingresado es entero -->
    <script src="./../js/funciones/filter.js"></script> <!-- Funcion para verificar que el valor ingresado es entero positivo -->