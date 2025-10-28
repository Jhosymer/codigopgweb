<?php 
     $loc = "../../../../";
     $locj = "../../../";
     $title = "Editar - Zona de Distribución Distribuidor";     
    if( !isset( $_REQUEST['id'] ) ){
        header("location: ./estados.distribuidores.php");
    }

    try{
        $url_arriba_carpeta = './../../index/header.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('Sucedio un error');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('Sucedio un error');
        </script>";
    }

    try{
        $url_base_datos = './../../../../config/conexion.php';
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


    $seleccionado = $base_de_datos->prepare("SELECT d_e.id_distribuidora, d.nombre, d_e.estado, d_e.ciudad, d_e.direccion, d_e.principal FROM distribuidora_estado as d_e
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
        $principal = $distribuidor['principal'];
        $id_distribuidora = $distribuidor['id_distribuidora'];
    }

    $sql = "SELECT estado FROM distribuidora_estado WHERE ( id_padre = :id_padre ) and ( deleted_at is null )";
    $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
    $seleccionado->bindParam(':id_padre', $id, PDO::PARAM_INT);
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
    $seleccionado->execute();
    $numFilaDistribuidores = $seleccionado->rowCount();
    $i = 0;
    while ( $fila = $seleccionado->fetch() ) {
        $distribuciones[$i] = $fila['estado'];
        $i++;
    }

    $sql = "SELECT id, nombre FROM distribuidoras WHERE ( pais = 'Venezuela' ) and ( deleted_at is null )";
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
        'Delta Amacuro' => 'Delta Amacuro',
        'Distrito Capital' => 'Distrito Capital',
        'Falcon' => 'Falcon',
        'Lara' => 'Lara',
        'La Guaira' => 'La Guaira',
        'Merida' => 'Merida',
        'Miranda' => 'Miranda',
        'Monagas' => 'Monagas',
        'Nueva Esparta' => 'Nueva Esparta',
        'Portuguesa' => 'Portuguesa',
        'Sucre' => 'Sucre',
        'Tachira' => 'Tachira',
        'Trujillo' => 'Trujillo',
        'Yaracuy' => 'Yaracuy',
        'Zulia' => 'Zulia',
    );

    $estados_nacional = array(
        'Amazonas' => 'Amazonas',
        'Anzoategui' => 'Anzoategui',
        'Apure' => 'Apure',
        'Aragua' => 'Aragua',
        'Barinas' => 'Barinas', 
        'Bolivar' => 'Bolivar',
        'Carabobo' => 'Carabobo',
        'Cojedes' => 'Cojedes',
        'Delta Amacuro' => 'Delta Amacuro',
        'Distrito Capital' => 'Distrito Capital',
        'Falcon' => 'Falcon',
        'Lara' => 'Lara',
        'La Guaira' => 'La Guaira',
        'Merida' => 'Merida',
        'Miranda' => 'Miranda',
        'Monagas' => 'Monagas',
        'Nueva Esparta' => 'Nueva Esparta',
        'Portuguesa' => 'Portuguesa',
        'Sucre' => 'Sucre',
        'Tachira' => 'Tachira',
        'Trujillo' => 'Trujillo',
        'Yaracuy' => 'Yaracuy',
        'Zulia' => 'Zulia',
        'Nacional' => 'Territorio Nacional'
    );
?>

<style>
    .estados {
        display: none;
    }
</style>



    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Actualizar Datos de Distribuidor - Venezuela</h1>
        </div>

       <a href="./estados_distribuidores.php"  class="btn-icon me-4" >Atras</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

   <div class="container mb-2 mt-5">

    <div class="card h-100 mb-5">
        <div class="card-body">
           <div class="row">
               <div class="col-12 col-md-6">
    
            <form action="update_estados.php" method="POST" class="form_login" >
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <?php 
                    if( $principal == 1 ){
                ?>
                     <table class="table table-striped table-hover table-responsive dataTable">
                        <tr>
                            <th> Nombre: </th>
                            <td>
                            <select name="id_distribuidora" class="form-select" required>
                                    <?php foreach( $distribuidoras as $distribuidora ){ 
                                        if( $distribuidora['id'] == $id_distribuidora ){ ?>
                                            <option value="<?php echo $id_distribuidora; ?>" selected><?php echo $nombre; ?></option>
                                        <?php } else {?>
                                           
                                    <?php }
                                    } ?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Estado: </th>
                            <td> 
                                <select id="estados_venezuela" name="estado" class="form-select" required>
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
                            <th>Dirección: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $direccion ?>" name="direccion" id="direccion" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Ciudad: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $ciudad ?>" name="ciudad" id="ciudad" required />
                            </td>  
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align: center;">Distribución</th>
                        </tr>
                        <?php
                            if( $numFilaDistribuidores == 0 ){
                        ?>
                            <tr>
                                <td colspan="2">
                                    <select id="elegir_distribucion" class="form-select" >
                                        <option value="" disabled selected>¿Puede distribuir a otros estados?</option>
                                        <option value="SI" >SI</option>
                                        <option value="NO" >NO</option>
                                    </select>
                                </td> 
                            </tr>
                            <tr class="estados" id="estados" >
                                <th>
                                    Estado: 
                                </th>
                                <td>
                                    <select id="estados_distribucion" name="estados_distribucion[]" class="form-select">
                                        <option value="" disabled selected>--Seleccione el Estado--</option>
                                        <option value="Amazonas">Amazonas</option>
                                        <option value="Anzoategui">Anzoátegui</option>
                                        <option value="Apure">Apure</option>
                                        <option value="Aragua">Aragua</option>
                                        <option value="Barinas">Barinas</option>
                                        <option value="Bolivar">Bolívar</option>
                                        <option value="Carabobo">Carabobo</option>
                                        <option value="Cojedes">Cojedes</option>
                                        <option value="Delta Amacuro"> Delta Amacuro</option>
                                        <option value="Distrito Capital"> Distrito Capital</option>
                                        <option value="Falcon">Falcón</option>
                                        <option value="Guarico">Guárico</option>
                                        <option value="Lara">Lara</option>
                                        <option value="La Guaira">La Guaira</option>
                                        <option value="Merida">Mérida</option>
                                        <option value="Miranda">Miranda</option>
                                        <option value="Monagas">Monagas</option>
                                        <option value="Nueva Esparta">Nueva Esparta</option>
                                        <option value="Portuguesa">Portuguesa</option>
                                        <option value="Sucre">Sucre</option>
                                        <option value="Tachira">Táchira</option>
                                        <option value="Trujillo">Trujillo</option>
                                        <option value="Yaracuy">Yaracuy</option>
                                        <option value="Zulia">Zulia</option>
                                    </select>
                                </td> 
                            </tr>
                        <?php 
                            }
                            else {
                                foreach( $distribuciones as $distri ){
                                    ?>
                                        <tr id="estados_distribucion">
                                            <th>
                                                Estado: 
                                            </th>
                                            <td>
                                                <select name="estados_distribucion[]" class="form-select">
                                                    <?php
                                                        foreach($estados as $estado_array){
                                                            if(key($estados) == $distri){
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
                                    <?php
                                }
                            }     
                        ?>
                        <tr>
                            <th class="b_td">
                                <input type="submit" value="Guardar" name="enviar_principal" class="btn-icon" />
                            </th>
                            <?php 
                                if( $numFilaDistribuidores > 0 ){
                            ?>
                                    <td class="b_td" >
                                        <input type="button" value="+" class="btn-icon" id="boton_mas2" />
                                    </td>
                            <?php
                                }
                                else {
                            ?>
                                    <td class="b_td" >
                                        <input type="button" value="+" class="boton estados" id="boton_mas" />
                                    </td>
                            <?php
                                }
                            ?>
                        </tr>
                    </table> 
                <?php
                    } 
                    else if( $principal == 0 ){
                ?>
                        <table class="tabla_edi">
                            <tr>
                                <th>Estado: </th>
                                <td> 
                                    <select id="estados_venezuela" name="estados_venezuela" class="form-select" required>
                                        <?php 
                                            foreach($estados as $estado_array){
                                                if(key($estados) == $estado){
                                                    ?>
                                                        <option value="<?php echo key($estados); ?>"  selected><?php echo $estado_array; ?></option>
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
                            <th class="b_td">
                                <input type="submit" value="Guardar" name="enviar_distribuidora" class="btn-icon" />
                            </th>
                        </table>
                <?php 
                    }
                    else if( $principal == 2 ){
                ?>
                    <table class="tabla_edi">
                        <tr>
                            <th> Nombre: </th>
                            <td>
                            <select name="id_distribuidora" class="form-select" required>
                                    <?php foreach( $distribuidoras as $distribuidora ){ 
                                        if( $distribuidora['id'] == $id_distribuidora ){ ?>
                                            <option value="<?php echo $id_distribuidora; ?>" selected><?php echo $nombre; ?></option>
                                        <?php } else {?>
                                           
                                    <?php }
                                    } ?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Estado: </th>
                            <td> 
                                <select id="estados_venezuela" name="estado" class="form-select" required>
                                    <?php 
                                        foreach($estados_nacional as $estado_array){
                                            if(key($estados_nacional) == $estado){
                                                ?>
                                                    <option value="<?php echo key($estados_nacional); ?>" selected><?php echo $estado_array; ?></option>
                                                <?php
                                            }
                                            else {
                                                ?>
                                                    <option value="<?php echo key($estados_nacional); ?>"><?php echo $estado_array; ?></option>
                                                <?php
                                            }
                                            next($estados_nacional);
                                        }
                                    ?>
                                    
                                </select>
                            </td>  
                        </tr>
                        <tr>
                            <th>Dirección: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $direccion ?>" name="direccion" id="direccion" required />
                            </td>  
                        </tr>
                        <tr>
                            <th>Ciudad: </th>
                            <td>
                                <input class="form-control" type="text" value="<?php echo $ciudad ?>" name="ciudad" id="ciudad" required />
                            </td>  
                        </tr>
                        <th class="b_td">
                            <input type="submit" value="Guardar" name="enviar_nacional" class="btn-icon" />
                        </th>
                    </table>
                <?php
                    }
                ?>
           </form>
                                 </div>
                 </div>
                
</div>
    <?php
        include('./../../index/footer.php')
    ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        elegir_distribucion = document.getElementById('elegir_distribucion');

        if( elegir_distribucion != null ){
            elegir_distribucion.addEventListener('change', () => {
                valor_elegir_distribucion = document.getElementById('elegir_distribucion').value;
                if( valor_elegir_distribucion == 'SI' ){
                    estados = document.querySelectorAll('.estados');
                    estados.forEach( function(estado){
                        estado.style.display = "table-row";
                    })
                }
                else {
                    estados = document.querySelectorAll('.estados');
                    estados.forEach( function(estado){
                        estado.style.display = "none";
                    })
                }
            })
        }

        var estados2 = ['Amazonas', 'Anzoategui', 'Apure', 'Aragua', 'Barinas', 'Bolivar', 'Carabobo', 'Cojedes', 'Delta Amacuro', 'Distrito Capital', 'Falcon', 'Guarico', 'Lara', 'La Guaira', 'Merida', 'Miranda', 'Monagas', 'Nueva Esparta', 'Portuguesa', 'Sucre', 'Tachira', 'Trujillo', 'Yaracuy', 'Zulia'];
        boton_mas = document.getElementById('boton_mas');
        if( boton_mas != null ){
            boton_mas.addEventListener('click', () => {
                const select_estado = document.querySelector("#estados");

                var newRow = "<tr><th>Estado</th><td><select class='form-select' name='estados_distribucion[]'>";
                var theOptions = "";
                estados2.forEach( function(estado) {
                    theOptions += `<option value="${estado}">${estado}</option>`;
                });
                newRow += theOptions;
                newRow += "</select></td></tr>";

                select_estado.insertAdjacentHTML('afterend', newRow);
            }); 
        }

        boton_mas = document.getElementById('boton_mas2');
        if( boton_mas != null ){
            boton_mas.addEventListener('click', () => {
                const select_estado = document.querySelector("#estados_distribucion");

                var newRow = "<tr><th>Estado</th><td><select id='estados_venezuela' name='estados_distribucion[]' class='form-select' >";
                var theOptions = "";
                estados2.forEach( function(estado) {
                    theOptions += `<option value="${estado}">${estado}</option>`;
                });
                newRow += theOptions;
                newRow += "</select></td></tr>";

                select_estado.insertAdjacentHTML('afterend', newRow);
            }); 
        }
    });
</script>