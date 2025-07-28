<?php     
    if( !isset( $_REQUEST['id'] ) ){
        header("location: equivalencias.php");
    }

    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_REQUEST['id'];

    //Consulta para Buscar los datos de la equivalencia
    $sql = "SELECT codigo, marca, codigo_marca FROM filtro_equivalencia WHERE id = :id";
    $filtro_seleccionado = $base_de_datos->prepare($sql) or die('Error al eliminar');
    $filtro_seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $filtro_seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $filtro_seleccionado->execute();

    //Consulta para buscar las marcas que no esten eliminadas
    $marca_act = $base_de_datos->prepare("SELECT marca FROM equivalencia_marca WHERE ( deleted_at IS NULL ) ORDER BY marca ASC");
    $marca_act->setFetchMode(PDO::FETCH_ASSOC); 
    $marca_act->execute();
    while ($fila = $marca_act->fetch()) {
        $resultado []= $fila;
    } 

    //Los datos del filtro se colocan en variables
    foreach($filtro_seleccionado as $filtro){
        $codigo = $filtro['codigo'];
        $marca = $filtro['marca'];
        $codigo_marca = $filtro['codigo_marca'];
    }

    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();
?>
<title>Actualizar Datos de las Equivalencias</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Actualizar Datos de las Equivalencias</p>
        </div>
        <div class="tex_tablas">
            <a href="./equivalencias.php" class="boton">Atras</a>
        </div>
    </section>

    <secttion class="about_tabla_edi">
        <div class="tex_tablas">
            <!-- Formulario para actualizar -->
            <form action="update.php" method="POST" class="form_login">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table class="tabla_edi">
                    <input type="hidden" value="<?php echo $id ?>" name="id"> <!-- Id de la equivalencia -->
                    <tr><th colspan="2" style="text-align: center;">Filtro</th></tr>
                    <!-- Código Web -->
                    <tr>
                        <th>Código WEB: </th>
                        <td>  
                            <input class="edi_inp" type="text" value="<?php echo $codigo ?>" name="codigo" id="codigo" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                                if( isset( $_SESSION["codigo_inexistente"] ) )
                                {
                                    echo "<div'>
                                            <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                            <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                        </div>";
                                    unset($_SESSION["codigo_inexistente"]);
                                }
                            ?>
                        </td>
                    </tr>
                    <tr><th colspan="2" style="text-align: center;">Equivalencia</th></tr>
                    <!-- Nombre de la Equivalencia -->
                    <tr>
                        <th>Marca: </th>
                        <td>
                            <select name="marca" id="marca" class="selectdis">
                                <?php foreach($resultado as $resul){ 
                                    if($resul['marca'] == $marca){?>                                       
                                        <option value="<?php echo $resul['marca'];?>" selected><?php echo $resul['marca'];?></option>
                                    <?php }
                                    else {?>
                                        <option value="<?php echo $resul['marca'];?>"><?php echo $resul['marca'];?></option>
                                <?php }}?>
                                <option value="Otro">AGREGAR MARCA</option>
                            </select>
                        </td> 
                    </tr>
                    <!-- Código del Filtro en la Marca -->
                    <tr>
                        <th>Código Marca: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $codigo_marca ?>" name="codigo_marca" id="codigo_marca" />
                        </td>  
                    </tr>
                    <tr>
                        <td class="b_td">
                            <input type="submit" value="Editar" name="equivalencia_marca" class="boton" />
                        </td>
                        <td class="b_td">  
                            <input class="boton" value='Reestablecer' type="reset">
                        </td>
                    </tr>
                </table> 
            </form>
        </div> 

        <!-- Formulario Para Crear Marcas de Equivalencia -->
        <div class="tex_tablas">
            <form action="./subir_marca_editar.php" method="POST" id="form-marca" class="formulario_aire">
                <table class="tabla_edi">
                    <tr><th colspan="2" style="text-align: center;">Crear Marca</th></tr>
                    <tr>
                        <td><b>Marca: </b></td>
                        <td><input type="text" id="marca" name="marca"></td>
                    </tr>      
                    <tr> 
                        <td class="b_td"> 
                            <input class="boton" type="submit" value="Enviar" name="marca_nueva">
                        </td>
                        <td class="b_td">  
                            <input class="boton" value='Reestablecer' type="reset">
                        </td>
                    </tr> 
                </table> 
            </form>
        </div>
    </section>
</section>


<?php
    include_once('./../abajo_carpeta.html')
?>

<script>
    //Cuando cambie la marca, en caso de que se coloque Agregar Marca mostrará el formulario para crear una nueva 
    //marca de equivalencia
    document.getElementById('form-marca').style.display = 'none';
    document.getElementById('marca').addEventListener('change', () => {
        var valorCambiado = document.getElementById('marca').value;
        if(valorCambiado == 'Otro'){
            document.getElementById('form-marca').style.display = 'block';
        }
        else {
            document.getElementById('form-marca').style.display = 'none';
        }
    })
</script>
