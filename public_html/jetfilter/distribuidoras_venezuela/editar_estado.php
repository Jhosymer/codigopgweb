<?php     
    if( !isset( $_REQUEST['id'] ) ){
        header("location: estados.distribuidores.php");
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

    $id = $_REQUEST['id'];
    $sql = "SELECT id_distribuidora FROM distribuidora_estado WHERE id = :id";
    $id_distribuidora = $base_de_datos->prepare($sql);
    $id_distribuidora->bindParam(':id', $id, PDO::PARAM_INT);
    $id_distribuidora->setFetchMode(PDO::FETCH_ASSOC);
    $id_distribuidora->execute();
    $id_distribuidora = $id_distribuidora->fetch();
    $id_distribuidora = $id_distribuidora['id_distribuidora'];


    $seleccionado = $base_de_datos->prepare("SELECT d.nombre, d_e.estado, d_e.ciudad, d_e.direccion FROM distribuidora_estado as d_e
                                                JOIN distribuidoras as d ON d.id = d_e.id_distribuidora
                                                WHERE d_e.id = :id");
    $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);                                            
    $seleccionado->execute();
    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
        $distribuidor_seleccionado []= $fila;
    }    

    foreach($distribuidor_seleccionado as $distribuidor){
        $nombre = $distribuidor['nombre'];
        $direccion = $distribuidor['direccion'];
        $estado = $distribuidor['estado'];
        $ciudad = $distribuidor['ciudad'];
    }

    $sql = "SELECT id, nombre FROM distribuidoras WHERE pais = 'Venezuela'";
    $distribuidoras_selec = $base_de_datos->prepare($sql);
    $distribuidoras_selec->setFetchMode(PDO::FETCH_ASSOC);
    $distribuidoras_selec->execute();
    while( $fila = $distribuidoras_selec->fetch() ){
        $distribuidoras []= $fila;
    }

    $estrellas = [1, 2, 3, 4, 5, 6];
    $estados = array(
        'Amazonas' => 'Amazonas',
        'Anzoategui' => 'Anzoategui',
        'Apure' => 'Apure',
        'Aragua' => 'Aragua',
        'Barinas' => 'Barinas', 
        'Bolivar' => 'Bolivar',
        'Carabobo' => 'Carabobo',
        'Cojedes' => 'Cojedes',
        'DeltaAmacuro' => 'Delta Amacuro',
        'Distrito Capital' => 'Distrito Capital',
        'Falcon' => 'Falcon',
        'Lara' => 'Lara',
        'Merida' => 'Merida',
        'Miranda' => 'Miranda',
        'Monagas' => 'Monagas',
        'Nueva Esparta' => 'Nueva Esparta',
        'Portuguesa' => 'Portuguesa',
        'Sucre' => 'Sucre',
        'Tachira' => 'Tachira',
        'Trujillo' => 'Trujillo',
        'Vargas' => 'Vargas',
        'Yaracuy' => 'Yaracuy',
        'Zulia' => 'Zulia',
    );
?>
 <title>Actualizar Datos de Distribuidor - Venezuela</title>
<section class="about_tabla_espe">
    <section class="about-if_tabla_esp">
        <div class="tex_tablas">
            <p>  Actualizar Datos de Distribuidor - Venezuela</p>
        </div>
        <div class="tex_tablas">
            <a href="./estados_distribuidores.php" class="boton"> Atras</a>
        </div>
    </section>

    <section class="es_tabla">
        <div class="tex_tablas">
    
            <form action="update_estados.php" method="POST" class="form_login" >
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <table class="tabla_edi">
                    <input type="hidden" value="<?php echo $id ?>" name="id">
                    <tr>
                        <th> Nombre: </th>
                        <td>
                           <select name="id_distribuidora" class="selectdis" required>
                                <?php foreach( $distribuidoras as $distribuidora ){ 
                                    if( $distribuidora['id'] == $id_distribuidora ){ ?>
                                        <option value="<?php echo $id; ?>" selected><?php echo $nombre; ?></option>
                                    <?php } else {?>
                                        <option value="<?php echo $distribuidora['id']; ?>"><?php echo $distribuidora['nombre']; ?></option>
                                <?php }
                                } ?>
                           </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Estado: </th>
                        <td> 
                            <select id="estados_venezuela" name="estado" class="selectdis" required>
                                <?php 
                                    foreach($estados as $estado_array){
                                        if(key($estados) == $estado){
                                            ?>
                                                <option value="<?php echo key($estados); ?>" selected><?php echo $estado_array; ?></option>
                                            <?php
                                        }
                                        else {
                                            ?>
                                                <option value="<?php echo key($estados); ?>"><?php echo $estado_array; ?></option>
                                            <?php
                                        }
                                        next($estados);
                                    }
                                ?>
                                
                            </select>
                        </td>  
                    </tr>
                    <tr>
                        <th>Direccion: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $direccion ?>" name="direccion" id="direccion" required />
                        </td>  
                    </tr>
                    <tr>
                        <th>Ciudad: </th>
                        <td>
                            <input class="edi_inp" type="text" value="<?php echo $ciudad ?>" name="ciudad" id="ciudad" required />
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
        include('./../abajo_carpeta.html')
    ?>