<?php 
     $loc = "../../../../";
     $locj = "../../../";
     $title = "Nuevo - Zona de Distribución Distribuidor";
    try{
        $url_arriba_carpeta = './../../index/header.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('hubo un Error');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
            echo "
            <script>
                alert('Hubo un Error');
            </script>";
    }
    try {
        include_once('./../../../../config/conexion.php');

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        header("location: nuevo_estados.php?errorBase=true");
    }
   
    $sql = "SELECT id, nombre FROM distribuidoras WHERE pais = 'Venezuela' and deleted_at is null ORDER BY nombre ASC";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->setFetchMode( PDO::FETCH_ASSOC );
    $seleccionado->execute();
    while ( $fila = $seleccionado->fetch() ) {
        $distribuidoras []= $fila;
    } 
?>
 

    <?php 
         if( isset($_GET["campo"]) ){
      ?>
         <script>
            Swal.fire({
               icon: 'error',
               title: 'Oops...',
               text: '¡Faltaron campos por llenar!',
               timer: 2000,
            }) .then(() => {
                  window.location.replace("nuevo_estados.php");
            })
         </script>
      <?php
         }
            
        if( isset($_GET["errorBase"]) )
            {
        ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema con la base de datos',
                timer: 1250,
                }) .then(() => {
                window.location.replace("espec_aireautomotriz.php");
            })
        </script>
        <?php
            }
    ?>
 
       
   
 <div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Nuevo Distribuidor - Venezuela</h1>
        </div>

       <a href="estados_distribuidores.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_estados.php" method="POST" class="formulario_aire">
                 <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                    <tr>
                        <th>Distribuidor</th>
                        <td>
                            <select class="form-select" name="distribuidora_id" required>
                                <option value="" disabled selected>--Seleccione la distribuidora--</option>
                                <?php foreach( $distribuidoras as $distribuidora ){ ?>
                                    <option value="<?php echo $distribuidora['id'];?>" ><?php echo $distribuidora['nombre'];?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: center;">Estados</th>
                    </tr>
                        <tr id="estado">
                            <th>Estado</th>
                            <td style="display:flex;">
                                <select id="estados_venezuela" name="estado" class="form-select" required>
                                    <option value="" disabled selected>--Seleccione el Estado--</option>
                                    <option value="Amazonas">Amazonas</option>
                                    <option value="Anzoategui">Anzoategui</option>
                                    <option value="Apure">Apure</option>
                                    <option value="Aragua">Aragua</option>
                                    <option value="Barinas">Barinas</option>
                                    <option value="Bolivar">Bolivar</option>
                                    <option value="Carabobo">Carabobo</option>
                                    <option value="Cojedes">Cojedes</option>
                                    <option value="Delta Amacuro"> Delta Amacuro</option>
                                    <option value="Distrito Capital"> Distrito Capital</option>
                                    <option value="Falcon">Falcon</option>
                                    <option value="Guarico">Guarico</option>
                                    <option value="Lara">Lara</option>
                                    <option value="La Guaira">La Guaira</option>
                                    <option value="Merida">Merida</option>
                                    <option value="Miranda">Miranda</option>
                                    <option value="Monagas">Monagas</option>
                                    <option value="Nueva Esparta">Nueva Esparta</option>
                                    <option value="Portuguesa">Portuguesa</option>
                                    <option value="Sucre">Sucre</option>
                                    <option value="Tachira">Tachira</option>
                                    <option value="Trujillo">Trujillo</option>
                                    <option value="Yaracuy">Yaracuy</option>
                                    <option value="Zulia">Zulia</option>
                                    <option value="Nacional">Territorio Nacional</option>
                                </select>
                            </td>
                        </tr>
                    <tr>
                        <th>Ciudad</th>
                        <td>
                            <input type="text" id="ciudad" name="ciudad" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <th>Direccion:</th>
                        <td>
                            <input type="text" id="direccion" name="direccion" class="form-control" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="b_td">  
                            <input class="btn-icon" name="enviar_estado" value="Enviar" type="submit">
                        </td>
                        <td class="b_td">  
                            <input class="btn-icon" type="reset">
                        </td>
                    </tr>
                </table>
        </form>
     </div>
        </div>
   
</div>

<?php 
    include_once('./../../index/footer.php');
?>

<script>
   /* var estados = ['Amazonas', 'Anzoategui', 'Apure', 'Aragua', 'Barinas', 'Bolivar', 'Carabobo', 'Cojedes', 'Delta Amacuro', 'Distrito Capital', 'Falcon', 'Guarico', 'Lara', 'Merida', 'Miranda', 'Monagas', 'Nueva Esparta', 'Portuguesa', 'Sucre', 'Tachira', 'Trujillo', 'Vargas', 'Yaracuy', 'Zulia'];
    boton_agregar = document.getElementById('agregar_estado');
    boton_agregar.addEventListener('click', () => {
        const select_estado = document.querySelector("#estado");

        var newRow = "<tr><th>Estado</th><td><select class='form-select' name='estado[]'>";
        var theOptions = "";
        estados.forEach( function(estado) {
            theOptions += `<option value="${estado}">${estado}</option>`;
        });
        newRow += theOptions;
        newRow += "</select></td></tr>";

        select_estado.insertAdjacentHTML('afterend', newRow);
    }); */
</script>