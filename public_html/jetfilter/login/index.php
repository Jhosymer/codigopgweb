<?php
session_start();
// Si ya hay sesión, redirigir a la página principal del sistema
if(isset($_SESSION['email'])){ 
    header('location: sesion.php'); 
    exit; 
}

$loc = "./../../";
$locj = "./../../";
$title = "Login | Jet Filter ";
$description="Login | Jet Filter";

// Incluimos la cabecera que ya contiene tus metas y estilos base
include($loc . 'web/head_jf.php'); 
?>

<script src="https://www.google.com/recaptcha/api.js?render=6LdSxn8sAAAAAP_9zuRsszCrZy825dSdnpmraZP1"></script>

<main>
    <div class="container">
        <div class="login-container">
            <h2 class="titulo text-center text-white">Acceso</h2>
            
            <form action="../validar.php" method="POST" id="form_login_jf" class="form_login">
                
                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                
                <div class="mb-3">
                    <label for="email" class="form-label text-white">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label text-white">Contraseña</label>
                    <div class="input-group"> 
                        <input type="password" class="form-control" name="password" id="password" required>
                        <button class="btn btn-secondary" type="button" id="togglePassword">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>
                
                <?php
                // Manejo de alertas visuales
                if(isset($_GET["fallo"])) {
                    if($_GET["fallo"] == 'true') {
                        echo "<div class='alert alert-danger'>Correo o contraseña incorrectos.</div>";
                    } elseif($_GET["fallo"] == 'captcha') {
                        echo "<div class='alert alert-warning'>Se detectó actividad sospechosa. Intenta de nuevo.</div>";
                    }
                }
                ?>
                
                <button type="submit" class="btn-icon w-100">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</main>

<script>
document.getElementById('form_login_jf').addEventListener('submit', function(e) {
    e.preventDefault(); // Detenemos el envío normal para obtener el token primero
    
    grecaptcha.ready(function() {
        // Ejecutamos reCAPTCHA con tu CLAVE DE SITIO
        grecaptcha.execute('6LdSxn8sAAAAAP_9zuRsszCrZy825dSdnpmraZP1', {action: 'login'}).then(function(token) {
            // Asignamos el token al campo oculto
            document.getElementById('g-recaptcha-response').value = token;
            // Enviamos el formulario manualmente
            document.getElementById('form_login_jf').submit();
        });
    });
});
</script>

<script src="<?php echo $loc; ?>js/scrip_login.js"></script>

</body>
</html>