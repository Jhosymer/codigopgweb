<?php 
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');

    $marca_act = $base_de_datos->prepare("SELECT marca FROM equivalencia_marca WHERE ( deleted_at IS NULL ) ORDER BY marca ASC");
    $marca_act->execute();
    $resultado = $marca_act->fetchAll();
    
    //Alertas a Comprobar
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_ya_existe.php');
    alerta_error();  
    alerta_nuevo();
    alerta_ya_existe();
?>

<title>Nueva Equivalencia</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>Crear Equivalencia</p>
        </div>
        <div class="tex_tablas">
            <a href="./equivalencias.php" class="boton">Atras</a>
        </div>
   </section>

    <section class="about_tabla_edi">
        <div class="tex_tablas">
             <!-- Formulario para Crear una Nueva Equivalencia -->
            <form action="subir_equivalencia.php" method="POST" class="formulario_aire">
                <table class="tabla_edi">
                    <tr><th colspan="2" style="text-align: center;">Filtro</th></tr>
                    <tr>
                        <th>Código WEB:</th>
                        <td>
                            <input type="text" id="codigo" name="codigo" class="codigo" required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                                if( isset( $_SESSION["codigo_inexistente"]) )
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
                    <tr>
                        <th>Marca: </th>
                        <td>
                            <select name="marca" class="selectdis" id="marca" required>
                                <?php foreach($resultado as $resul){ ?>
                                    <option value="<?php echo $resul[0];?>"><?php echo $resul[0];?></option>
                                <?php }?>
                                <option value="Otro">Agregar Marca</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Código Marca:</th>
                        <td>
                            <input type="text" id="codigo_marca" name="codigo_marca" class="codigo_marca" required>
                        </td>
                    </tr>
                    <tr> 
                        <td class="b_td"> 
                            <input class="boton" type="submit" value="Enviar" name="equivalencia_nueva">
                        </td>
                        <td class="b_td">  
                            <input class="boton" type="reset">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- Formulario para Crear una Nueva Marca -->
        <div class="tex_tablas">
            <form action="./subir_marca.php" method="POST" id="form-marca" class="formulario_aire">
                <table class="tabla_edi">
                    <tr><th colspan="2" style="text-align: center;"><b>Crear Marca</b></th></tr>
                    <tr>
                        <td><b>Marca: </b></td>
                        <td><input type="text" id="marca" name="marca"></td>
                    </tr>       
                    <tr> 
                        <td class="b_td"> 
                            <input class="boton" type="submit" value="Enviar" name="marca_nueva">
                        </td>
                        <td class="b_td">  
                            <input class="boton" type="reset">
                        </td>
                    </tr> 
                </table> 
            </form>
        </div>
    </section>
</section>

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

<?php 
    include_once('./../abajo_Carpeta.html')
?>