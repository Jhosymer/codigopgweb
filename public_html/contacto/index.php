<?php 
$loc = "../";
$title = "Contáctenos";
$description="Contáctanos para resolver tus dudas. Completa el formulario, ¡estamos aquí para ayudarte!";
include("../web/header.php");
?>

<div class="content py-5">
    <div class="container">
        <div class="card mx-auto" style="max-width: 800px;">
            <div class="card-body py-5">
                <h1 class="titulo text-center rojoweb">Contáctenos</h1>
                <p class="text-center text-secondary">Todos los campos son obligatorios</p>
                <form method="POST" class="blueform" id="contactForm" action="./../index.php">
                    <div class="mb-3">
                        <label for="nombre" class="subtito_ms form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="email" class="subtito_ms form-label">Correo</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                        <div class="col">
                            <label for="n_tlf" class="subtito_ms form-label">Teléfono</label>
                            <input type="text" class="form-control" name="n_tlf" id="n_tlf" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mensaje" class="subtito_ms form-label">Mensaje</label>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="4" required></textarea>
                    </div>
                    <div class="mb-3 mt-5">
                        <button type="submit" class="btn-icon">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./../js/js_vende/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.bluecaribu.com/conversion/integration"></script>
<script type='text/javascript'>
    bctag({
        config: {
            token: '281f0e4e16b1b4dedf3185457aa12f03', 
            redirect: "./../index.php"
        },
        forms : {
            ".blueform" : {
                contact_name     : "nombre",
                contact_email    : "email",
                contact_phone    : "n_tlf",
                lead_msg         : "mensaje"
            }
        }
    });

    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const mensaje = document.getElementById('mensaje').value.trim();
        const email = document.getElementById('email').value.trim();
        const nombre = document.getElementById('nombre').value.trim();
        const tlf = document.getElementById('n_tlf').value.trim();
        const validEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        if (mensaje === '' || email === '' || nombre === '' || tlf === '') {
            Swal.fire({
                title: 'Todos los campos son obligatorios',
                icon: 'error',
            });
            return;
        }

        if (!validEmail.test(email)) {
            Swal.fire({
                title: 'Verificar Correo',
                icon: 'info',
            });
            return;
        }

        Swal.fire({
            title: 'Correo enviado',
            icon: 'success'
        }).then(() => {
            event.target.submit(); // Permite el envío normal para que BlueCaribu lo capture
        });
    });
</script>

<?php
include("../web/footer.php");
?>
