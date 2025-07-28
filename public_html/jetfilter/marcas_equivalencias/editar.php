<?php  
    if( !isset($_REQUEST['id']) ){
        header("location: marcas_equivalencias.php");
    }

    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $id = $_REQUEST['id'];

    $seleccionado = $base_de_datos->prepare("SELECT * FROM equivalencia_marca
                                                WHERE id = :id  and ( deleted_at is null )");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $marcas []= $fila;
    }    

    foreach($marcas as $mar){
        $marca = $mar['marca'];
        $mostrar = $mar['mostrar'];
    }

    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();

    
?>
    <title>Actualizar Datos de Marcas - Equivalencias</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Actualizar Datos de Marcas - Equivalencias </p>
            </div>
            <div class="tex_tablas">
                <a href="./marcas_equivalencias.php" class="boton">Atras</a>
            </div>
        </section>

        <section class="es_tabla">
            <div class="tex_tablas">
                <form action="update.php" method="POST" class="form_login" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <table class="tabla_edi">
                        <tr>
                            <th> Marca: </th>
                            <td>
                                <input class="edi_inp" type="text" value="<?php echo $marca ?>" name="marca" id="marca" required/>
                            </td>

                        </tr>  
                        <tr>
                            <th>Mostrar:</th>
                            <td>
                                <select id="mostrar" name="mostrar" class="selectdis" required>
                                    <option value="1" <?php echo ($mostrar == 1) ? 'selected' : ''; ?>>SI</option>
                                    <option value="0" <?php echo ($mostrar == 0) ? 'selected' : ''; ?>>NO</option>
                                </select>
                            </td>
                        </tr>
                        <tr><td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="boton" /></td></tr>
                    </table> 
                </form>
            </div>
        </section>
    </section>

    <?php
        include('./../abajo_carpeta.html')
    ?>