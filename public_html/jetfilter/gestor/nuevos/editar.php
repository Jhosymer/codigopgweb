<?php 
     $loc = "../../../";
     $locj = "../../";
     $title = "Editar | Producto Nuevos";   
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
                        title: '¡Error Limite!',
                        text: 'Excede 21 registros',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['error_limite'] );
        }

    $id = $_REQUEST['id'];

    $sql = "SELECT codigo, id_marca, id_marca1, id_marca2 FROM nuevos_filtros WHERE id = :id";
    $seleccionar_filtro = $base_de_datos->prepare($sql);
    $seleccionar_filtro->bindParam(':id', $id, PDO::PARAM_INT);
    $seleccionar_filtro->execute();
    $filtro = $seleccionar_filtro->fetch(PDO::FETCH_ASSOC);

    $marcas_totales = [];
try {
    $sentencia = $base_de_datos->prepare("SELECT id, marca FROM `aplicacion_marca` WHERE `deleted_at` IS NULL ORDER BY marca ASC");
    $sentencia->execute();
    $marcas_totales = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Manejo de error si la consulta falla
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
?>

  

        
<div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Editar Producto Nuevo</h1>
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
            <form action="update.php" method="POST" class="formulario_aire">
                <input type="hidden" value="<?php echo $id; ?>" name="id">
                <table class="table table-striped table-hover table-responsive dataTable mt-5" id="example">
                    <tr>
                        <th>Codigo</th>
                        <td>
                            <input type="text" value="<?php echo $filtro['codigo'] ?>" id="codigo_filtro" name="codigo_filtro" class="form-control" required>
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
                                    <select id="marca_filtro_1" name="marca_filtro_1" class="form-select">
                                        <option value="">-- Seleccione una Marca --</option>
                                        <?php 
                                            foreach ($marcas_totales as $marca) {
                                                $selected = ($filtro['id_marca'] == $marca['id']) ? 'selected' : '';
                                                echo "<option value='{$marca['id']}' {$selected}>{$marca['marca']}</option>";
                                            }
                                        ?>
                                    </select>
                                    <button type="button" class="btn-icon mt-2" id="add-marca-btn-2" 
                                            style="display: <?php echo empty($filtro['id_marca1']) ? 'inline-block' : 'none'; ?>;">Agregar otra Marca</button>
                                </td>
                            </tr>

                            <tr id="marca-row-2" style="display: <?php echo empty($filtro['id_marca1']) ? 'none' : 'table-row'; ?>;">
                                <th>Marca Aplicación II</th>
                                <td>
                                    <select id="marca_filtro_2" name="marca_filtro_2" class="form-select">
                                        <option value="">-- Seleccione una Marca --</option>
                                    </select>
                                    <button type="button" class="btn-icon mt-2" id="add-marca-btn-3" 
                                            style="display: <?php echo empty($filtro['id_marca2']) ? 'inline-block' : 'none'; ?>;">Agregar otra Marca</button>
                                </td>
                            </tr>
                            
                            <tr id="marca-row-3" style="display: <?php echo empty($filtro['id_marca2']) ? 'none' : 'table-row'; ?>;">
                                <th>Marca Aplicación III</th>
                                <td>
                                    <select id="marca_filtro_3" name="marca_filtro_3" class="form-select">
                                        <option value="">-- Seleccione una Marca --</option>
                                    </select>
                                </td>
                            </tr>
                    <tr> 
                        <td class="b_td"> 
                            <input class="btn-icon" type="submit" value="Enviar" name="editar_principal">
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
    // Lista completa de marcas (usada por el JS)
    const allMarcas = <?php echo json_encode($marcas_totales); ?>;
    
    // Marcas actualmente seleccionadas (usadas para inicializar)
    const currentMarca1 = '<?php echo $filtro['id_marca'] ?? ''; ?>';
    const currentMarca2 = '<?php echo $filtro['id_marca1'] ?? ''; ?>';
    const currentMarca3 = '<?php echo $filtro['id_marca2'] ?? ''; ?>';

    const select1 = document.getElementById('marca_filtro_1');
    const select2 = document.getElementById('marca_filtro_2');
    const select3 = document.getElementById('marca_filtro_3');

    const row2 = document.getElementById('marca-row-2');
    const row3 = document.getElementById('marca-row-3');

    const addBtn2 = document.getElementById('add-marca-btn-2');
    const addBtn3 = document.getElementById('add-marca-btn-3');

    /**
     * Actualiza la visibilidad de los botones "Agregar otra Marca".
     */
    function updateButtonVisibility() {
        const isRow2Visible = window.getComputedStyle(row2).display !== 'none';
        const isRow3Visible = window.getComputedStyle(row3).display !== 'none';
        
        // Botón 2: visible si Select 1 tiene valor Y Select 2 está oculto.
        addBtn2.style.display = (select1.value !== '' && !isRow2Visible) ? 'inline-block' : 'none';

        // Botón 3: visible si Select 2 tiene valor Y Select 3 está oculto.
        addBtn3.style.display = (select2.value !== '' && !isRow3Visible) ? 'inline-block' : 'none';
    }

    /**
     * Filtra y rellena un select, excluyendo las marcas ya seleccionadas en otros.
     * @param {HTMLElement} targetSelect - El select a rellenar (select2 o select3).
     * @param {string} preselectedValue - Valor que se intenta seleccionar (solo se usa en la inicialización).
     */
    function filterAndPopulateSelect(targetSelect, preselectedValue = '') {
        
        let selectedIds = [];
        
        // IDs seleccionadas en los OTROS selects
        if (targetSelect !== select1 && select1.value !== '') { selectedIds.push(String(select1.value)); }
        if (targetSelect !== select2 && select2.value !== '') { selectedIds.push(String(select2.value)); }
        if (targetSelect !== select3 && select3.value !== '') { selectedIds.push(String(select3.value)); }
        
        let actualSelectedId = preselectedValue || targetSelect.value;
        
        targetSelect.innerHTML = '<option value="">-- Seleccione una Marca --</option>';

        allMarcas.forEach(marca => {
            const marcaIdString = String(marca.id);
            
            // Incluir si NO está seleccionada en otro select O si es la marca que DEBE estar seleccionada.
            if (!selectedIds.includes(marcaIdString) || marcaIdString === actualSelectedId) {
                const option = document.createElement('option');
                option.value = marca.id;
                option.textContent = marca.marca;
                targetSelect.appendChild(option);
            }
        });
        
        // Intentar seleccionar el valor actual/predeterminado
        if (actualSelectedId) {
             targetSelect.value = actualSelectedId;
             if (targetSelect.value !== actualSelectedId) {
                targetSelect.value = ''; // Limpiar si la opción fue filtrada (duplicada)
            }
        }
        
        updateButtonVisibility();
    }
    
    // Lógica de Inicialización: Rellenar y seleccionar el valor guardado
    // Lo hacemos con setTimeout para asegurar que todos los selects se hayan cargado.
    setTimeout(() => {
        // Inicializar select 2 (le pasamos el valor guardado de id_marca1)
        filterAndPopulateSelect(select2, currentMarca2); 
        
        // Inicializar select 3 (le pasamos el valor guardado de id_marca2)
        filterAndPopulateSelect(select3, currentMarca3);
        
        updateButtonVisibility();
    }, 50);


    // ===============================================
    // EVENTOS DE CAMBIO (CHANGE)
    // ===============================================

    // Select 1 cambia -> Refiltra 2 y 3, y controla visibilidad de 2 y 3
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

    // Select 2 cambia -> Refiltra 3, y controla visibilidad de 3
    select2.addEventListener('change', function() {
        filterAndPopulateSelect(select3);
        
        if (this.value === '') {
            // Si deseleccionamos el segundo, limpiamos y ocultamos el tercero
            select3.value = '';
            row3.style.display = 'none';
        }
        updateButtonVisibility();
    });
    
    // Select 3 cambia -> Solo actualiza la visibilidad de botones
    select3.addEventListener('change', updateButtonVisibility);
    
    // ===============================================
    // EVENTOS DE CLIC (CLICK)
    // ===============================================

    addBtn2.addEventListener('click', function() {
        row2.style.display = 'table-row';
        filterAndPopulateSelect(select2); // Rellenar/filtrar al mostrar
        updateButtonVisibility(); // Ocultar el botón 2
    });

    addBtn3.addEventListener('click', function() {
        row3.style.display = 'table-row';
        filterAndPopulateSelect(select3); // Rellenar/filtrar al mostrar
        updateButtonVisibility(); // Ocultar el botón 3
    });
});
</script>
<?php 
     include('../index/footer.php');
?>