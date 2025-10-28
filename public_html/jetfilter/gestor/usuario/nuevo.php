<?php 
$loc = "../../../";
$locj = "./../../";
$title = "Nuevo Usuario";
include_once('../index/header.php');
include_once('../../../config/conexion.php');

?>

<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="titulo">Nuevo Usuario Cliente</h1>
                    <form action="index.php" method="post" class="mb-0">
                        <button type="submit" class="btn btn-secondary" name="volver">Atrás</button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="crub.php" method="post">
                        <input type="hidden" name="accion" value="crear">
                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="nombre" class="form-label mb-0">Nombre:</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="rif" class="form-label mb-0">RIF:</label>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="rif" name="rif" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="email" class="form-label mb-0">Correo:</label>
                            </div>
                            <div class="col-8">
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <hr>  
                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="nuevaClave_nuevo" class="form-label mb-0">Clave:</label>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="nuevaClave_nuevo" name="nuevaClave" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('nuevaClave_nuevo', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <div class="col-4">
                                <label for="confirmarClave_nuevo" class="form-label mb-0">Confirmar Clave:</label>
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmarClave_nuevo" name="confirmarClave" onblur="validarClavesOnBlur_nuevo()" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('confirmarClave_nuevo', this)">
                                        <i class='bx bx-show-alt'></i>
                                    </button>
                                </div>
                                <div id="mensajeAlerta_nuevo" class="mt-2" style="display: none;">
                                    <div class="alert alert-danger p-2" role="alert">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/menutables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/calculoporprecios.js"></script>

<?php 
include("../index/script_usuario.php");
include("../index/footer.php");
?>
