<?php 
    
    if( !isset( $_REQUEST['id'] ) ){
        header("location: espec_distribuidor.php");
    }

    try{
        $url_arriba_carpeta = './../../arriba_carpeta_segundoNivel.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('Sucedio un Errror');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('Sucedio un Error');
        </script>";
    }

    try{
        $url_base_datos = './../../conexion/conexion.php';
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

    $id = $_REQUEST['id'];

    $seleccionado = $base_de_datos->prepare("SELECT * FROM distribuidoras 
                                                WHERE id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $distribuidor_seleccionado []= $fila;
    }    

    foreach($distribuidor_seleccionado as $distribuidor){
        $nombre = $distribuidor['nombre'];
        $email = $distribuidor['email'];
        $email2 = $distribuidor['email2'];
        $telefono = $distribuidor['telefono'];
        $telefono2 = $distribuidor['telefono2'];
        $calificacion = $distribuidor['calificacion'];
        $facebook = $distribuidor['facebook'];
        $twitter = $distribuidor['twitter'];
        $instagram = $distribuidor['instagram'];
        $video_instagram = $distribuidor['video_instagram'];
        $direcmaps = $distribuidor['direcmaps'];
    }

    $estrellas = [1, 2, 3, 4, 5, 6];
?>
 <title>Actualizar Datos de Distribuidor - Venezuela</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>  Actualizar Datos de Distribuidor - Venezuela</p>
        </div>
        <div class="tex_tablas">
            <a href="./espec_distribuidor.php" class="boton"> Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
    
            <form action="./update.php" method="POST" class="form_login" >
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table class="tabla_edi">
                    <tr>
                        <th> Nombre: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $nombre ?>" name="nombre" id="nombre" required />
                        </td>
                    </tr>
                    <tr>
                        <th>Correo: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $email ?>" name="email" id="email" required />
                        </td>  
                    </tr>
                    <tr>
                        <th>Correo 2: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $email2 ?>" name="email2" />
                        </td>  
                    </tr>
                    <tr>
                        <th>Telefono:</th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $telefono ?>" name="telefono" id="telefono" required />
                        </td>  
                    </tr>
                    <tr>
                        <th>Telefono 2:</th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $telefono2 ?>" name="telefono_2" id="telefono_2"  />
                        </td>  
                    </tr>
                    <tr>
                        <th>Calificacion:</th>
                        <td>
                        <select name="calificacion" id="calificacion" class="selectdis">
                            <?php 
                                foreach( $estrellas as $estrella){
                                    if( $calificacion == $estrella ){
                            ?>
                                        <option value="<?php echo $estrella; ?>" selected><?php echo $estrella; ?> Estrellas</option>
                           <?php 
                                    }
                                    else {
                            ?>
                                        <option value="<?php echo $estrella; ?>"><?php echo $estrella; ?> Estrellas</option>
                            <?php 
                                    }
                                }
                            ?>
                        </select>
                        </td>  
                    </tr>
                    <tr>
                        <th>Facebook</th>
                        <td>
                            <input type="text" id="facebook" name="facebook"  value="<?php echo $facebook ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Twitter</th>
                        <td>
                            <input type="text" id="twitter" name="twitter" value="<?php echo $twitter ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Instagram</th>
                        <td>
                            <input type="text" id="instagram" name="instagram" value="<?php echo $instagram ?>">
                        </td>
                    </tr>

                     <tr>
                        <th>Video Instagram</th>
                        <td>
                            <input type="text" id="instagram" name="video_instagram" value="<?php echo $video_instagram ?>">
                        </td>
                    </tr>
                    <tr>
                        <th>Google Maps</th>
                        <td>
                            <input type="text" id="instagram" name="direcmaps" value="<?php echo $direcmaps ?>">
                        </td>
                    </tr>
                   
                    <tr>
                        <td class="b_td">
                            <input type="submit" value="Guardar" name="btnimg" class="boton" />
                        </td>
                    </tr>
                    
                </table> 
            </form>
        </div> 
    </section>
</section>

    <?php
        include('./../../abajo_carpeta_segundoNivel.html')
    ?>