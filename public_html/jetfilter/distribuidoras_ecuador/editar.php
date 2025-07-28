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

    $seleccionado = $base_de_datos->prepare("SELECT * FROM distribuidoras 
                                                WHERE id = $id");
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $distribuidor_seleccionado []= $fila;
    }    

    foreach($distribuidor_seleccionado as $distribuidor){
        $nombre = $distribuidor['nombre'];
        $email = $distribuidor['email'];
        $estado = $distribuidor['estado'];
        $ciudad = $distribuidor['ciudad'];
        $telefono = $distribuidor['telefono'];
    }
?>
 <title>Actualizar Datos de Distribuidor - Ecuador</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>  Actualizar Datos de Distribuidor - Ecuador</p>
        </div>
        <div class="tex_tablas">
            <a onclick="atras();" class="boton"> Atras</a>
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
            <tr><th> Nombre: </th>
            <td>  <input class="edi_inp" type="text" value="<?php echo $nombre ?>" name="nombre" id="nombre" required /></td>
                </tr>
                <tr>
            <th> Correo: </th>
            <td> <input class="edi_inp" type="text" value="<?php echo $email ?>" name="email" id="email" required /></td>  
                </tr>
                <tr>
                <th>Estado: </th>
                    <td> 
                        <select id="estados_ecuador" name="estado" class="selectdis" required>
                            <option value="" disabled>--Seleccione--</option>
                            <option value="Anzoategui">Anzoategui</option>
                            <option value="Apure">Apure</option>
                            <option value="Aragua">Aragua</option>
                            <option value="Barinas">Barinas</option>
                            <option value="Bolivar">Bolivar</option>
                            <option value="Carabobo">Carabobo</option>
                            <option value="Cojedes">Cojedes</option>
                            <option value="Falcon">Falcon</option>
                            <option value="Guarico">Guarico</option>
                            <option value="Lara">Lara</option>
                            <option value="Merida">Merida</option>
                            <option value="Miranda">Miranda</option>
                            <option value="Monagas">Monagas</option>
                            <option value="Nueva_esparta">Nueva Esparta</option>
                            <option value="Portuguesa">Portuguesa</option>
                            <option value="Sucre">Sucre</option>
                            <option value="Tachira">Tachira</option>
                            <option value="Trujillo">Trujillo</option>
                            <option value="Zulia">Zulia</option>
                        </select>
                    </td>  
                </tr>
                <tr>
                <th>Ciudad: </th>
            <td><input class="edi_inp" type="text" value="<?php echo $ciudad ?>" name="ciudad" id="ciudad" required /></td>  
                </tr>
                <tr>
                <th>Telefono:</th>
            <td><input class="edi_inp" type="text" value="<?php echo $telefono ?>" name="telefono" id="telefono" required /></td>  
                </tr>
                <tr ><td class="b_td"><input type="submit" value="Guardar" name="btnimg" class="boton" /></td></tr>
        </table> 
    </div>
    </form>
    </section>
</section>

    <?php
        include('./../abajo_carpeta.html')
    ?>