<?php

// Conexión a la base de datos (asegúrate de que $base_de_datos esté definido)
$wsqli_ver_user = "SELECT * FROM `users` WHERE rol = 1";
$result_ver_user = $base_de_datos->query($wsqli_ver_user);

if ($base_de_datos->errorCode() !== '00000') {
    $errorInfo = $base_de_datos->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}

while ($row_ver_user = $result_ver_user->fetch(PDO::FETCH_ASSOC)) {
    $id = $row_ver_user['id'];
    $nombre = $row_ver_user['name'];
    $email = $row_ver_user['email'];

    // 1. Consulta para obtener TODOS los permisos disponibles
    $wsqli_permisos = "SELECT * FROM `permiso_admin`";
    $result_permisos = $base_de_datos->query($wsqli_permisos);
    $permisos_disponibles = $result_permisos->fetchAll(PDO::FETCH_ASSOC);

    // 2. Consulta para obtener los permisos ASIGNADOS al usuario actual
    $wsqli_permisos_usuario = "SELECT id_permiso_admin FROM `users_permiso_admin` WHERE id_users = :user_id";
    $stmt_permisos_usuario = $base_de_datos->prepare($wsqli_permisos_usuario);
    $stmt_permisos_usuario->execute([':user_id' => $id]);
    $permisos_asignados = $stmt_permisos_usuario->fetchAll(PDO::FETCH_COLUMN, 0);

?>
<div class="modal fade" id="editarusuarioadminModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="editarusuarioadminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo-ms" id="editarusuarioadminModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- SE CAMBIA LA LLAMADA A LA FUNCIÓN CON EL ID DEL USUARIO -->
            <form method="POST" action="crub.php" onsubmit="return validarClaves('<?php echo $id; ?>')">
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?php echo $id; ?>">

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="nombre-<?php echo $id; ?>" class="form-label mb-0">Nombre:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="nombre-<?php echo $id; ?>" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-4">
                            <label for="email-<?php echo $id; ?>" class="form-label mb-0">Correo:</label>
                        </div>
                        <div class="col-8">
                            <input type="email" class="form-control" id="email-<?php echo $id; ?>" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>

                    <hr>

                     <a class="mb-3 btn btn-outline-primary" style="width: fit-content; text-align: left;" data-bs-toggle="collapse" href="#collapsePermisos-<?php echo $id; ?>" role="button" aria-expanded="false" aria-controls="collapsePermisos-<?php echo $id; ?>">
                        <i class='bx bx-check-square'></i> Permisos de Función
                    </a>
                    
                    <div class="collapse" id="collapsePermisos-<?php echo $id; ?>">
                        <div class="row mb-3">
                            <?php
                            $count = 0;
                            foreach ($permisos_disponibles as $permiso) {
                                $permiso_id = $permiso['id'];
                                $permiso_nombre = $permiso['nombre'];
                                $checked = in_array($permiso_id, $permisos_asignados) ? 'checked' : '';
                                $is_toggle_all = ($permiso_id == 1) ? 'onchange="toggleAll(this)"' : '';
                                $checkbox_id = ($permiso_id == 1) ? 'seleccionarTodos-' . $id : 'permiso-' . $permiso_id . '-' . $id;
                                $checkbox_class = ($permiso_id != 1) ? 'permiso-checkbox' : '';
                            ?>
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input <?php echo $checkbox_class; ?>" type="checkbox" name="permisos[]" value="<?php echo $permiso_id; ?>" id="<?php echo $checkbox_id; ?>" <?php echo $checked; ?> <?php echo $is_toggle_all; ?>>
                                        <label class="form-check-label" for="<?php echo $checkbox_id; ?>">
                                            <?php echo htmlspecialchars($permiso_nombre); ?>
                                        </label>
                                    </div>
                                </div>
                            <?php
                                $count++;
                                // Agrega una nueva fila cada 2 columnas
                                if ($count % 2 == 0) {
                                    echo '</div><div class="row mb-3">';
                                }
                            }
                            ?>
                        </div>
                    </div>

                   <hr>

                    <a class="mb-3 btn btn-outline-primary" style="width: fit-content; text-align: left;"  data-bs-toggle="collapse" href="#collapseClave-<?php echo $id; ?>" role="button" aria-expanded="false" aria-controls="collapseClave-<?php echo $id; ?>">
                        <i class='bx bx-key'></i> Cambiar clave
                    </a>

                    <div class="collapse" id="collapseClave-<?php echo $id; ?>">
                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="nuevaClave-<?php echo $id; ?>" class="form-label mb-0">Nueva Clave:</label>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <!-- AQUI SE USA EL ID 'nuevaClave-<?php echo $id; ?>' Y SE LLAMA A LA FUNCION CON EL ID DEL USUARIO -->
                                    <input type="password" class="form-control" id="nuevaClave-<?php echo $id; ?>" name="nuevaClave" onblur="validarClavesOnBlur('<?php echo $id; ?>')">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('nuevaClave-<?php echo $id; ?>', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="confirmarClave-<?php echo $id; ?>" class="form-label mb-0">Confirmar Clave:</label>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <!-- AQUI SE USA EL ID 'confirmarClave-<?php echo $id; ?>' Y SE LLAMA A LA FUNCION CON EL ID DEL USUARIO -->
                                    <input type="password" class="form-control" id="confirmarClave-<?php echo $id; ?>" name="confirmarClave" onblur="validarClavesOnBlur('<?php echo $id; ?>')">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirmarClave-<?php echo $id; ?>', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                                <div id="mensajeAlerta-<?php echo $id; ?>" class="mt-2" style="display: none;">
                                    <div class="alert alert-danger p-2" role="alert">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}
?>

