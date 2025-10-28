
<?php
session_start();
if( isset($_SESSION['email']) ){
    header('location: sesion.php');
}

$loc = "./../../";
$locj = "./../../";
$title = "Login | Jet Filter ";
$description="Login | Jet Filter";
include($loc . 'web/head_jf.php'); 
?>
<style>.btn-secondary:hover {
    background-color: #8c8c8c; /* Un gris más claro */
    border-color: #8c8c8c;
}</style>
<main>
    <div class="container">
        <div class="login-container">
            <h2 class=" titulo text-center text-white">Acceso</h2>
            <form action="../validar.php" method="POST" id="form" class="form_login">
                <div class="mb-3">
                    <label for="email" class="form-label text-white">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-white">Contraseña</label>
                    <div class="input-group"> <input type="password" class="form-control" name="password" id="password" required>
                       <button class="btn btn-secondary" type="button" id="togglePassword">
                        <i class='bx bx-show'></i>
                    </button>
                    </div>
                </div>
                <?php
                if(isset($_GET["fallo"]) && $_GET["fallo"] == 'true')
                {
                    echo "<div id='errorAlert' class='alert alert-danger' role='alert'>
                        Correo Electrónico o contraseña incorrectos.
                        </div>";
                }
            ?>
                <button type="submit" class=" btn-icon w-100">Iniciar Sesión</button>
            </form>
        </div>
    </div>

    
 
</main>

    <script src="<?php echo $loc; ?>js/scrip_login.js"></script><!-- login -->

</body>
</html>