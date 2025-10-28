<?php

$wsqli_ver_user = "SELECT * FROM `users` where rol = 2";
$result_ver_user = $base_de_datos->query($wsqli_ver_user);

if ($base_de_datos->errorCode() !== '00000') {
    $errorInfo = $base_de_datos->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}

while ($row_ver_user = $result_ver_user->fetch(PDO::FETCH_ASSOC)) {
    $id = $row_ver_user['id'];
    $nombre = $row_ver_user['name'];
    $rif = $row_ver_user['rif'];
    $email = $row_ver_user['email'];
?>
<div class="modal fade" id="editarusuarioModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="editarusuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo-ms" id="editarusuarioModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="crub.php" onsubmit="return validarClaves_<?php echo $id; ?>()">
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
                            <label for="rif-<?php echo $id; ?>" class="form-label mb-0">RIF:</label>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="rif-<?php echo $id; ?>" name="rif" value="<?php echo htmlspecialchars($rif); ?>" required>
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

                    <a class="d-block mb-3" data-bs-toggle="collapse" href="#collapseClave-<?php echo $id; ?>" role="button" aria-expanded="false" aria-controls="collapseClave-<?php echo $id; ?>">
                        <i class='bx bx-key'></i> Cambiar clave
                    </a>

                    <div class="collapse" id="collapseClave-<?php echo $id; ?>">
                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="nuevaClave-<?php echo $id; ?>" class="form-label mb-0">Nueva Clave:</label>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="nuevaClave-<?php echo $id; ?>" name="nuevaClave" onblur="validarClavesOnBlur_<?php echo $id; ?>()">
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
                                    <input type="password" class="form-control" id="confirmarClave-<?php echo $id; ?>" name="confirmarClave" onblur="validarClavesOnBlur_<?php echo $id; ?>()">
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