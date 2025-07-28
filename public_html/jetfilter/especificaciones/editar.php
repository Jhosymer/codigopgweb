<?php 
    $page = ( isset($_GET['page']) ) ? $_GET['page'] : 1;
    $registros = ( isset($_GET['registros']) ) ? $_GET['registros'] : 10;
    
    if( !isset($_POST['editar']) ){
        header("location: espec_aireautomotriz.php");
    }

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

    try{
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('No encontró la base de datos');
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
    }
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

    $id = $_POST['editar'];
    $page = $_POST['page'];
    $registros = $_POST['registros'];

    $filtro_seleccionado = $base_de_datos->query("SELECT * FROM especificaciones 
                                                            WHERE codigo = $id") or die('Error al eliminar');
    
   
    $seleccionado_imagen = $base_de_datos->query("SELECT imagen, imagen1, imagen2, imagen3 FROM especificaciones 
                                                                                            WHERE codigo = $id") or die('Error al eliminar'); 
    $imagenes = $seleccionado_imagen->fetch();
    foreach($filtro_seleccionado as $filtro){
        $itemname = $filtro['Itemname'];
        $itemcode = $filtro['itemcode'];
        $buscar = $filtro['codigobuscar'];
        $esp1 = $filtro['U_Esp1'];
        $desc1 = $filtro['U_Desc1'];
        $esp2 = $filtro['U_Esp2'];
        $desc2 = $filtro['U_Desc2'];
        $esp3 = $filtro['U_escp3'];
        $desc3 = $filtro['U_Desc3'];
        $esp4 = $filtro['U_Esp4'];
        $desc4 = $filtro['U_Desc4'];
        $esp5 = $filtro['U_Esp5'];
        $desc5 = $filtro['U_Desc5'];
        $esp6 = $filtro['U_Esp6'];
        $desc6 = $filtro['U_Desc6'];
        $esp7 = $filtro['U_Esp7'];
        $desc7 = $filtro['U_Desc7'];
        $esp8 = $filtro['U_Esp8'];
        $desc8 = $filtro['U_Desc8'];
        $esp9 = $filtro['U_Esp9'];
        $desc9 = $filtro['U_Desc9'];
        $esp10 = $filtro['U_Esp10'];
        $desc10 = $filtro['U_Desc10'];
        $linea = $filtro['linea'];
    }

    $lineas = ['Aire Automotriz', 'Aire Industrial', 'Combustible Linea', 'Elemento', 'Panel', 'Sellado'];
?>
<title>Actualizar Datos de las Especificaciones</title>
<section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
      <p>  Actualizar Datos de las Especificaciones</p>
        </div>
        <div class="tex_tablas">
        <a onclick="atras();" class="boton">Atras</a>
   
    </div>
</section>

<secttion class="es_tabla">
    <div class="tex_tablas">
   
    <form action="update.php" method="POST" class="form_login" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <input type="hidden" name="registros" value="<?php echo $registros; ?>">
        <table class="tabla_edi">
            <input type="hidden" value="<?php echo $id ?>" name="id">
                <tr>
            <th> Itemname: </th>
            <td> <input class="edi_inp" type="text" value="<?php echo $itemname ?>" name="itemname" id="itemname"/></td>  
                </tr>
                <tr>
                <th>Itemcode: </th>
            <td><input  class="edi_inp" type="text" value="<?php echo $itemcode ?>" name="itemcode" id="itemcode" /></td>  
                </tr>
                <tr>
                <th>U_Esp 1: </th>
            <td><input class="edi_inp" type="text" value="<?php echo $esp1 ?>" name="esp1" id="esp1"/></td>  
                </tr>
                <tr>
                <th>U_Desc 1:</th>
            <td><input class="edi_inp" type="text" value="<?php echo $desc1 ?>" name="desc1" id="desc1" /></td>  
                </tr>
                <tr>
                <th>U_Esp 2: </th>
            <td><input class="edi_inp" type="text" value="<?php echo $esp2 ?>" name="esp2" id="esp2"/></td>  
                </tr>
                <tr>
                <th>U_Desc 2: </th>
            <td> <input class="edi_inp" type="text" value="<?php echo $desc2 ?>" name="desc2" id="desc2"/></td>  
                </tr>
                <tr>
                <th>U_Esp 3:</th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $esp3 ?>" name="esp3" id="esp3"/></td>  
                <tr>
                <th>U_Desc 3: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc3 ?>" name="desc3" id="desc3"/></td>  
                </tr>
                <tr>
                <th>U_Esp 4:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp4 ?>" name="esp4" id="esp4"  /></td>  
                </tr>
                <th>U_Desc 4: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc4 ?>" name="desc4" id="desc4"/></td>  
                </tr>
                <tr>
                <th>U_Esp 5:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp5 ?>" name="esp5" id="esp5"  /></td>  
                </tr>
                <th>U_Desc 5: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc5 ?>" name="desc5" id="desc5"/></td>  
                </tr>
                <tr>
                <th>U_Esp 6:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp6 ?>" name="esp6" id="esp6"  /></td>  
                </tr>
                <th>U_Desc 6: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc6 ?>" name="desc6" id="desc6"/></td>  
                </tr>
                <tr>
                <th>U_Esp 7:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp7 ?>" name="esp7" id="esp7"  /></td>  
                </tr>
                <th>U_Desc 7: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc7 ?>" name="desc7" id="desc7"/></td>  
                </tr>
                <tr>
                <th>U_Esp 8:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp8 ?>" name="esp8" id="esp8"  /></td>  
                </tr>
                <th>U_Desc 8: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc8 ?>" name="desc8" id="desc8"/></td>  
                </tr>
                <tr>
                <th>U_Esp 9:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp9 ?>" name="esp9" id="esp9"  /></td>  
                </tr>
                <th>U_Desc 9: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc9 ?>" name="desc9" id="desc9"/></td>  
                </tr>
                <tr>
                <th>U_Esp 10:</th>
                <td> <input class="edi_inp" type="text" value="<?php echo $esp10 ?>" name="esp10" id="esp10"  /></td>  
                </tr>
                <th>U_Desc 10: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $desc10 ?>" name="desc10" id="desc10"/></td>  
            <tr>
                <th>Linea:</th>
                <td>
                    <select name="linea" class="selectdis">
                        <?php 
                            foreach($lineas as $lin){
                                if($lin == $linea){
                        ?>
                                    <option value="<?php echo $lin;?>" selected><?php echo $lin;?></option>
                            <?php
                                }
                            ?>
                                <option value="<?php echo $lin;?>"><?php echo $lin;?></option>
                        <?php
                            }
                        ?>
                    </select>
                </td>
            </tr>


                <tr ><td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="boton" /></td></tr>
        </table> 
    </div>

        <div class="galeria"></br>
    
   
    
        <div class="contenedor-imagenes">
        <?php 
                for($i = 0; $i < 4; $i++){
                        if( $imagenes[$i] != "" ){
                            if( file_exists("./../../images/fichas-filtros/web/$imagenes[$i].jpg") ){
                                ?>
                                    <div class="imag">
                                        <img src="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" class="imagen" data="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg"></img>
                                        <input type="file" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                        <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                            <span class="iborrainputfile">Seleccionar archivo</span>
                                        </label>
                                    </div>
                                <?php 
                            }
                            else {
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
                        }
                        else{
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
                }
            ?>
    </div>
</div>
       
</form>
    </section>
</section>


    <?php
        include('./../abajo_carpeta.html')
    ?>