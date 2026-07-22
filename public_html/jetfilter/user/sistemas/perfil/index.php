<?php
if (!isset($linki) || !($linki instanceof mysqli)) {
    die('<div class="alert alert-danger" role="alert">Error: La conexión a la base de datos ($linki) no está disponible o no es válida.</div>');
}

if (!isset($id_users)) {
    die('<div class="alert alert-warning" role="alert">Error: ID de usuario ($id_users) no definida. No se puede cargar el formulario de edición.</div>');
}

// Ejecuta la consulta para obtener los datos del usuario específico (asumiendo rol = 2)
$safe_id = $linki->real_escape_string($id_users);
$wsqli_ver_user = "SELECT * FROM `users` WHERE id = '{$safe_id}' AND rol = 2";
$result_ver_user = $linki->query($wsqli_ver_user);

if (!$result_ver_user || $result_ver_user->num_rows === 0) {
    die('<div class="alert alert-danger" role="alert">Usuario con ID ' . htmlspecialchars($id_users) . ' no encontrado o no tiene el rol de edición permitido.</div>');
}

$row_ver_user = $result_ver_user->fetch_assoc();
$id = $row_ver_user['id'];
$nombre = $row_ver_user['name'];
$rif = $row_ver_user['rif'];
$email = $row_ver_user['email'];

// PROCESAMIENTO DEL RIF: Se usa ltrim para eliminar "C-" o cualquier prefijo de un carácter seguido de un guion
// Esto es para que solo se muestre el número (ej: de "C-J311592210" a "J311592210").
$display_rif = $rif;
if (strpos($rif, '-') !== false && strlen($rif) > 2) {
    $display_rif = substr($rif, strpos($rif, '-') + 1);
}


?>



<script>
    // Función para alternar la visibilidad de la contraseña
    function togglePasswordVisibility(id, button) {
        const input = document.getElementById(id);
        const icon = button.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bx-show-alt');
            icon.classList.add('bx-hide');
        } else {
            input.type = "password";
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show-alt');
        }
    }

    // Función principal de validación para el envío del formulario
    function validarClavesGlobal(id) {
        const nuevaClave = document.getElementById('nuevaClave-' + id).value;
        const confirmarClave = document.getElementById('confirmarClave-' + id).value;
        const mensajeAlerta = document.getElementById('mensajeAlerta-' + id);

        if (nuevaClave !== "" || confirmarClave !== "") {
            if (nuevaClave !== confirmarClave) {
                mensajeAlerta.style.display = 'block';
                return false; // Evita el envío del formulario
            }
        }
        
        mensajeAlerta.style.display = 'none';
        return true; 
    }

    // Función de validación para el evento onblur (feedback instantáneo)
    function validarClavesOnBlurGlobal(id) {
        const nuevaClave = document.getElementById('nuevaClave-' + id).value;
        const confirmarClave = document.getElementById('confirmarClave-' + id).value;
        const mensajeAlerta = document.getElementById('mensajeAlerta-' + id);

        if (nuevaClave !== "" && confirmarClave !== "") {
            if (nuevaClave !== confirmarClave) {
                mensajeAlerta.style.display = 'block';
            } else {
                mensajeAlerta.style.display = 'none';
            }
        } else {
            mensajeAlerta.style.display = 'none';
        }
    }
</script>

<!-- Contenedor que limita el ancho y centra el formulario -->
<div class="container-fluid py-4">
    <!-- Se aumenta el ancho máximo a 650px para dar más margen lateral y se mantiene centrado -->
    <div class="mx-auto" style="max-width: 650px;"> 
        <div class="card shadow-sm border-2 border-custom-primary">
            <div class="card-header bg-custom-primary text-white p-3">
                <h5 class="mb-0 text-white titulo-ms">
                    <i class='bx bx-user me-1'></i> Editando Usuario
                </h5>
            </div>
            <!-- Se cambia de p-2 a p-4 para dar más margen alrededor de los campos -->
            <div class="card-body p-4"> 
                
                <form method="POST" action="sistemas/perfil/crud.php" onsubmit="return validarClavesGlobal(<?php echo $id; ?>)">
                    <input type="hidden" name="user_id" id="user_id" value="<?php echo htmlspecialchars($id); ?>">
                  <input type="hidden" name="modo" value="actualizar">
                    <!-- Se incluye el RIF completo como un campo oculto si se necesitara para el backend -->
                    <input type="hidden" name="rif_completo" value="<?php echo htmlspecialchars($rif); ?>">


                    <!-- Campo Nombre: READONLY (Gris claro) -->
                    <!-- mb-3 para más espacio vertical. Se eliminó 'small' de la etiqueta -->
                    <div class="row mb-3 align-items-center"> 
                        <div class="col-md-3 col-sm-4">
                            <label for="nombre-<?php echo $id; ?>" class="form-label mb-0 fw-semibold">Nombre:</label>
                        </div>
                        <div class="col-md-9 col-sm-8">
                            <!-- Clases añadidas para apariencia de deshabilitado -->
                            <input type="text" class="form-control form-control-sm form-control-readonly-gray" id="nombre-<?php echo $id; ?>" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required readonly>
                        </div>
                    </div>

                    <!-- Campo RIF: READONLY (Gris claro) -->
                    <!-- Aquí se usa $display_rif que no tiene el prefijo C- -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-3 col-sm-4">
                            <label for="rif-<?php echo $id; ?>" class="form-label mb-0 fw-semibold">RIF:</label>
                        </div>
                        <div class="col-md-9 col-sm-8">
                            <!-- Clases añadidas para apariencia de deshabilitado -->
                            <input type="text" class="form-control form-control-sm form-control-readonly-gray" id="rif-<?php echo $id; ?>" name="rif" value="<?php echo htmlspecialchars($display_rif); ?>" required readonly>
                        </div>
                    </div>

                    <!-- Campo Correo (Editable) -->
                    <!-- mb-3 para más espacio vertical. Se eliminó 'small' de la etiqueta -->
                    <div class="row mb-3 align-items-center"> 
                        <div class="col-md-3 col-sm-4">
                            <label for="email-<?php echo $id; ?>" class="form-label mb-0 fw-semibold">Correo:</label>
                        </div>
                        <div class="col-md-9 col-sm-8">
                            <input type="email" class="form-control form-control-sm" id="email-<?php echo $id; ?>" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Sección de Cambio de Clave (Collapse) - Ahora es un enlace <a> -->
                    <!-- Se eliminó la etiqueta <p class="mb-3"> redundante que envolvía este enlace. -->
                    <a class="d-block mb-3" data-bs-toggle="collapse" href="#collapseClave-<?php echo $id; ?>" role="button" aria-expanded="false" aria-controls="collapseClave-<?php echo $id; ?>">
                        <i class="bx bx-key"></i> Cambiar clave
                    </a>
                    
                    <div class="collapse" id="collapseClave-<?php echo $id; ?>">
                        <!-- Nueva Clave -->
                        <!-- mb-3 para más espacio vertical. Se eliminó 'small' de la etiqueta -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3 col-sm-4">
                                <label for="nuevaClave-<?php echo $id; ?>" class="form-label mb-0">Nueva Clave:</label>
                            </div>
                            <div class="col-md-9 col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" id="nuevaClave-<?php echo $id; ?>" name="nuevaClave" onblur="validarClavesOnBlurGlobal(<?php echo $id; ?>)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('nuevaClave-<?php echo $id; ?>', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmar Clave -->
                        <!-- mb-3 para más espacio vertical. Se eliminó 'small' de la etiqueta -->
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3 col-sm-4">
                                <label for="confirmarClave-<?php echo $id; ?>" class="form-label mb-0">Confirmar Clave:</label>
                            </div>
                            <div class="col-md-9 col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="password" class="form-control" id="confirmarClave-<?php echo $id; ?>" name="confirmarClave" onblur="validarClavesOnBlurGlobal(<?php echo $id; ?>)">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirmarClave-<?php echo $id; ?>', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                                
                                <!-- Mensaje de Alerta de Coincidencia (Oculto por defecto) -->
                                <div id="mensajeAlerta-<?php echo $id; ?>" class="mt-2" style="display: none;">
                                    <div class="alert alert-danger p-2" role="alert">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción: Se restauran las clases de estilo personalizadas. -->
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="submit" class="btn-icon">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
if (isset($_SESSION['swal_type'])) {
    
    // Escapar el contenido para que JavaScript lo maneje sin errores de comillas
    $swal_title = addslashes($_SESSION['swal_title']);
    $swal_text = addslashes($_SESSION['swal_text']);
    $swal_type = $_SESSION['swal_type'];

    echo "<script>";
    // Llama a la función SweetAlert2
    echo "Swal.fire({";
    echo "  icon: '{$swal_type}',";
    echo "  title: '{$swal_title}',";
    // Usamos 'html' para que el <br> en el mensaje funcione correctamente
    echo "  html: '{$swal_text}',"; 
    echo "  confirmButtonText: 'Aceptar'";
    echo "});";
    echo "</script>";

    // CRÍTICO: Limpiar las variables de sesión para que la alerta solo se muestre una vez.
    unset($_SESSION['swal_type']);
    unset($_SESSION['swal_title']);
    unset($_SESSION['swal_text']);
}
?>