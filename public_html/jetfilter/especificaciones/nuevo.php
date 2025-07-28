<?php 
    try{
        $url_arriba_carpeta = './../arriba_carpeta.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('No se encontro el archivo arriba_carpeta.php');
        }
        else {
            include_once($url_arriba_carpeta);
        }
     }
     catch(Exception $e){
           echo "
           <script>
              alert('No se encontro el archivo arriba_carpeta.php');
           </script>";
     }
?>

<title>Nueva Especificación</title>
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Crear Especificación</p>
            </div>
            <div class="tex_tablas">
                <a onclick="atras();" class="boton">Atras</a>
            </div>
        </section>

        <secttion class="es_tabla">
            <div class="tex_tablas">
                <form action="crear_especificacion.php" method="POST" class="formulario_especificacion" enctype="multipart/form-data">
                    <table class="tabla_edi">
                        <tr>
                            <th>Itemname:</th>
                            <td>
                                <input type="text" id="itemname" name="itemname">
                            </td>
                        </tr>
                        <tr>
                            <th>Itemcode:</th>
                            <td>
                                <input type="text" id="itemcode" name="itemcode">
                            </td>
                        </tr>
                        <tr>
                            <th>U_Esp 1:</th>
                            <td>
                                <input type="text" id="u_esp_1" name="u_esp_1">
                            </td>
                        </tr>
                        <tr>
                            <th>U_Desc 1:</th>
                            <td>
                                <input type="text" id="u_desc_1" name="u_desc_1">
                            </td>
                        </tr>
                        <tr class="2">
                            <th>U_Esp 2:</th>
                            <td>
                                <input type="text" id="u_esp_2" name="u_esp_2">
                            </td>
                        </tr>
                        <tr class="2">
                            <th>U_Desc 2:</th>
                            <td>
                                <input type="text" id="u_desc_2" name="u_desc_2">
                            </td>
                        </tr>
                        <tr class="3">
                            <th>U_Esp 3:</th>
                            <td>
                                <input type="text" id="u_esp_3" name="u_esp_3">
                            </td>

                        <tr class="3">
                            <th>U_Desc 3:</th>
                            <td>
                                <input type="text" id="u_desc_3" name="u_desc_3">
                            </td>
                        </tr>
                        <tr class="4">
                            <th>U_Esp 4:</th>
                            <td>
                                <input type="text" id="u_esp_4" name="u_esp_4">
                            </td>
                        </tr>
                        <tr class="4">
                            <th>U_Desc 4:</th>
                            <td>
                                <input type="text" id="u_desc_4" name="u_desc_4">
                            </td>
                        </tr>
                        <tr class="5">
                            <th>U_Esp 5:</th>
                            <td>
                                <input type="text" id="u_esp_5" name="u_esp_5">
                            </td>
                        </tr>
                        <tr class="5">
                            <th>U_Desc 5:</th>
                            <td>
                                <input type="text" id="u_desc_5" name="u_desc_5">
                            </td>
                        </tr>
                        <tr class="6">
                            <th>U_Esp 6:</th>
                            <td>
                                <input type="text" id="u_esp_6" name="u_esp_6">
                            </td>
                        </tr>
                        <tr class="6">
                            <th>U_Desc 6:</th>
                            <td>
                                <input type="text" id="u_desc_6" name="u_desc_6">
                            </td>
                        </tr>
                        <tr class="7">
                            <th>U_Esp 7:</th>
                            <td>
                                <input type="text" id="u_esp_7" name="u_esp_7">
                            </td>
                        </tr>
                        <tr class="7"> 
                            <th>U_Desc 7:</th>
                            <td>
                                <input type="text" id="u_desc_7" name="u_desc_7">
                            </td>
                        </tr>
                        <tr class="8">
                            <th>U_Esp 8:</th>
                            <td>
                                <input type="text" id="u_esp_8" name="u_esp_8">
                            </td>
                        </tr>
                        <tr class="8">
                            <th>U_Desc 8:</th>
                            <td>
                                <input type="text" id="u_desc_8" name="u_desc_8">
                            </td>
                        </tr>
                        <tr class="9">
                            <th>U_Esp 9:</th>
                            <td>
                                <input type="text" id="u_esp_9" name="u_esp_9">
                            </td>
                        </tr>
                        <tr class="9">
                            <th>U_Desc 9:</th>
                            <td>
                                <input type="text" id="u_desc_9" name="u_desc_9">
                            </td>
                        </tr>
                        <tr class="10">
                            <th>U_Esp 10:</th>
                            <td>
                                <input type="text" id="u_esp_10" name="u_esp_10">
                            </td>
                        </tr>
                        <tr class="10">
                            <th>U_Desc 10:</th>
                            <td>
                                <input type="text" id="u_desc_10" name="u_desc_10">
                            </td>
                        </tr>
                        <tr> 
                            <td colspan="2">
                                <input type="button" value="Agregar Especificación" onclick="aparecer()" class="boton"/>
                            </td>
                        </tr>
                        <tr>
                            <th>Linea:</th>
                            <td>
                            <select name="linea" class="selectdis">
                                <option value="Aire Automotriz">Aire Automotriz</option>
                                <option value="Aire Industrial">Aire Industrial</option>
                                <option value="Combustible Linea">Combustible en Linea</option>
                                <option value="Elemento">Elemento</option>
                                <option value="Panel">Panel</option>
                                <option value="Sellado">Sellado</option>
                            </select>
                            </td>
                        </tr>
                        <tr> 
                            <td class="b_td"> <input class="boton" type="submit" value="Enviar" name="especificacion"></td>
                            <td class="b_td">  <input class="boton" type="reset"></td>
                        </tr>
                    </table>
                </div>
                <div class="galeria"></br>
                    <div class="contenedor-imagenes">
                        <?php 
                            for($i = 0; $i < 4; $i++){
                                ?>
                                    <div class="imag">
                                    <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen"></img>
                                    <input type="file" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                    <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                        <span class="iborrainputfile">Seleccionar archivo</span>
                                    </label>
                                    </div>
                                <?php                
                            }
                        ?>
                    </div>
                </div>
                </form>
        </section>
    </section>

<?php 
    include_once('./../abajo_carpeta.html');
?>

<script>
    for(let i = 2; i < 11; i++){
        $(`.${i}`).hide();
    }
    let j = 2;
    function aparecer(){
        if(j < 11){
            $(`.${j}`).show();
            j++;
        }
    }
</script>