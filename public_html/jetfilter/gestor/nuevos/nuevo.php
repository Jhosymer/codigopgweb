<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Crear | Producto nuevos";
    try{
        if ( !file_exists( '../index/header.php' ) ){
            throw new Exception ('No se encontro el archivo arriba_carpeta.php');
        }
        else {
            include_once('../index/header.php');
        }
    }
    catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }

    try{
        if ( !file_exists( '../../../config/conexion.php' ) ){
            throw new Exception ('No se encontro el archivo conexion.php');
        }
        else {
            include_once('../../../config/conexion.php');
        }
    }
    catch(Exception $e){
        echo "Error: " . $e->getMessage();
    }

    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

        if( isset( $_SESSION["error_limite"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'El número de registros está en su límite',
                        text: 'Elimine registros para agregar más',
                        timer: 2500,
                    }) 
                </script>
            <?php
            unset( $_SESSION['num_imagenes'] );
        }

        if( isset( $_SESSION["num_imagenes"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: '¡No existen imágenes!',
                        text: 'Ese filtro no cuenta con las imagenes.',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['num_imagenes'] );
        }

        if( isset( $_SESSION["repetido"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Ya existe!',
                        text: 'Ese filtro ya existe',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['repetido'] );
        }

        

  $marcas = [];
try {
    $sentencia = $base_de_datos->prepare("SELECT id, marca FROM `aplicacion_marca` WHERE `deleted_at` IS NULL ORDER BY marca ASC");
    $sentencia->execute();
    $marcas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Manejo de error si la consulta falla
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error al cargar las marcas',
            text: 'Ha sucedido un error al intentar obtener los datos de las marcas.',
        })
    </script>
    <?php
}
?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Producto Nuevo</h1>
        </div>

       <a href="./nuevos_filtros.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
           
            <div class="card-body">
                <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_nuevos.php" method="POST" class="formulario_aire">
                  <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                    <tr>
                        <th>Codigo</th>
                        <td>
                            <input type="text" id="codigo_filtro" name="codigo_filtro" class="form-control" required>
                        </td>
                    </tr>
                        <?php
                            if(isset($_GET["codigo_error"]) && $_GET["codigo_error"] == 'true')
                            {
                                echo "
                                    <tr>
                                        <td colspan='2'>
                                            <div>
                                                <h3 style='border-radius: 7.5px 7.5px 0px 0px; background-color: #B81616; color:white; text-align:center; padding: 0.3em; margin-top: 1em'>Error</h3>
                                                <div style='border-radius: 0px 0px 7.5px 7.5px; background-color: #F78787; color: white; padding: 1em; text-align:center; margin-bottom: 1.5em;'>No se encontraron coincidencias</div>
                                            </div>
                                        </td>
                                    </tr>";
                            }
                        ?>
                       <tr id="marca-row-1">
            <th>Marca Aplicación I</th>
            <td>
                <select id="marca_filtro_1" name="marca_filtro_1" class="form-select" required>
                    <option value="">-- Seleccione una Marca --</option>
                    <?php 
                        foreach ($marcas as $marca) {
                            echo "<option value='{$marca['id']}'>{$marca['marca']}</option>";
                        }
                    ?>
                </select>
                <button type="button" class="btn-icon mt-2" id="add-marca-btn-2" style="display: none;">Agregar otra Marca</button>
            </td>
        </tr>

        <tr id="marca-row-2" style="display: none;">
            <th>Marca Aplicación II</th>
            <td>
                <select id="marca_filtro_2" name="marca_filtro_2" class="form-select">
                    <option value="">-- Seleccione una Marca --</option>
                </select>
                <button type="button" class="btn-icon mt-2" id="add-marca-btn-3" style="display: none;">Agregar otra Marca</button>
            </td>
        </tr>
        
        <tr id="marca-row-3" style="display: none;">
            <th>Marca Aplicación III</th>
            <td>
                <select id="marca_filtro_3" name="marca_filtro_3" class="form-select">
                    <option value="">-- Seleccione una Marca --</option>
                </select>
            </td>
        </tr>
        
                    <tr> 
                        <td class="b_td"> 
                            <input class="btn-icon" type="submit" value="Enviar" name="nuevo_principal">
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lista completa de marcas.
    const allMarcas = <?php echo json_encode($marcas); ?>;
    
    const select1 = document.getElementById('marca_filtro_1');
    const select2 = document.getElementById('marca_filtro_2');
    const select3 = document.getElementById('marca_filtro_3');

    const row1 = document.getElementById('marca-row-1');
    const row2 = document.getElementById('marca-row-2');
    const row3 = document.getElementById('marca-row-3');

    const addBtn2 = document.getElementById('add-marca-btn-2');
    const addBtn3 = document.getElementById('add-marca-btn-3');

    /**
     * Función para actualizar la visibilidad de los botones "Agregar".
     * Lógica: El botón X solo se muestra si el selector X+1 NO está visible.
     */
    function updateButtonVisibility() {
        // Lógica del Botón 2 (Depende de select1 seleccionado y row2 NO visible)
        const isRow2Visible = window.getComputedStyle(row2).display !== 'none';
        
        if (select1.value !== '' && !isRow2Visible) {
            addBtn2.style.display = 'inline-block';
        } else {
            addBtn2.style.display = 'none';
        }

        // Lógica del Botón 3 (Depende de select2 seleccionado y row3 NO visible)
        const isRow3Visible = window.getComputedStyle(row3).display !== 'none';

        if (select2.value !== '' && !isRow3Visible) {
            addBtn3.style.display = 'inline-block';
        } else {
            addBtn3.style.display = 'none';
        }
    }


    /**
     * Función que filtra y rellena un select, excluyendo las marcas ya seleccionadas.
     * @param {HTMLElement} targetSelect - El select que se va a rellenar (select2 o select3).
     */
    function filterAndPopulateSelect(targetSelect) {
        
        // 1. Obtener todas las IDs seleccionadas, EXCLUYENDO el ID del select que estamos rellenando.
        let selectedIds = [];
        
        if (targetSelect !== select1 && select1.value !== '') {
            selectedIds.push(select1.value);
        }
        if (targetSelect !== select2 && select2.value !== '') {
            selectedIds.push(select2.value);
        }
        if (targetSelect !== select3 && select3.value !== '') {
            selectedIds.push(select3.value);
        }
        
        // 2. Guardar la ID seleccionada actualmente para intentar re-seleccionarla.
        const currentSelectedId = targetSelect.value;

        // 3. Limpiar el select
        targetSelect.innerHTML = '<option value="">-- Seleccione una Marca --</option>';

        // 4. Rellenar el select con las marcas que NO están en la lista de IDs excluidas.
        allMarcas.forEach(marca => {
            const marcaIdString = String(marca.id);
            
            if (!selectedIds.includes(marcaIdString)) {
                const option = document.createElement('option');
                option.value = marca.id;
                option.textContent = marca.marca;
                targetSelect.appendChild(option);
            }
        });
        
        // 5. Intentar re-seleccionar la opción que estaba antes.
        if (currentSelectedId) {
             targetSelect.value = currentSelectedId;
             if (targetSelect.value !== currentSelectedId) {
                targetSelect.value = '';
            }
        }
        
        // **IMPORTANTE:** Llamar a la función de visibilidad después de cualquier cambio en el select
        updateButtonVisibility();
    }


    // ===============================================
    // EVENTOS DE CAMBIO (CHANGE)
    // ===============================================

    // Evento para SELECT 1:
    select1.addEventListener('change', function() {
        filterAndPopulateSelect(select2);
        filterAndPopulateSelect(select3);
        
        if (this.value === '') {
            // Si deseleccionamos el primero, limpiamos y ocultamos todo lo siguiente
            select2.value = '';
            select3.value = '';
            row2.style.display = 'none';
            row3.style.display = 'none';
        }
        updateButtonVisibility();
    });

    // Evento para SELECT 2:
    select2.addEventListener('change', function() {
        filterAndPopulateSelect(select3);
        
        if (this.value === '') {
            // Si deseleccionamos el segundo, limpiamos y ocultamos el tercero
            select3.value = '';
            row3.style.display = 'none';
        }
        updateButtonVisibility();
    });
    
    // Evento para SELECT 3:
    select3.addEventListener('change', function() {
        // Aunque no hay más selects, actualizamos la visibilidad por si se deselecciona.
        updateButtonVisibility(); 
    });
    
    // ===============================================
    // EVENTOS DE CLIC (CLICK)
    // ===============================================

    addBtn2.addEventListener('click', function() {
        row2.style.display = 'table-row';
        filterAndPopulateSelect(select2); // Asegurar que se filtre al mostrar
        updateButtonVisibility(); // Ocultar el botón 2
    });

    addBtn3.addEventListener('click', function() {
        row3.style.display = 'table-row';
        filterAndPopulateSelect(select3); // Asegurar que se filtre al mostrar
        updateButtonVisibility(); // Ocultar el botón 3
    });

    // Inicializar la visibilidad de los botones al cargar la página
    updateButtonVisibility();
});
</script>
<?php 
    include_once('../index/footer.php');
?>