<script>
    // Función para mostrar/ocultar la contraseña
    function togglePasswordVisibility(fieldId, button) {
        var input = document.getElementById(fieldId);
        var icon = button.querySelector('i');
        
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

    // Un script para cada modal con su propia función de validación
    <?php
    $result_ver_user_js = $base_de_datos->query("SELECT id FROM `users` where rol = 2");
    while ($row_js = $result_ver_user_js->fetch(PDO::FETCH_ASSOC)) {
        $id_js = $row_js['id'];
    ?>
    
    // Función de validación al perder el foco del campo
    function validarClavesOnBlur_<?php echo $id_js; ?>() {
        var nuevaClave = document.getElementById('nuevaClave-<?php echo $id_js; ?>').value;
        var confirmarClave = document.getElementById('confirmarClave-<?php echo $id_js; ?>').value;
        var mensajeAlerta = document.getElementById('mensajeAlerta-<?php echo $id_js; ?>');

        if (nuevaClave.trim() === "" && confirmarClave.trim() === "") {
            mensajeAlerta.style.display = 'none';
        } else if (nuevaClave !== confirmarClave) {
            mensajeAlerta.style.display = 'block';
        } else {
            mensajeAlerta.style.display = 'none';
        }
    }

    // Función de validación al enviar el formulario
    function validarClaves_<?php echo $id_js; ?>() {
        var nuevaClave = document.getElementById('nuevaClave-<?php echo $id_js; ?>').value;
        var confirmarClave = document.getElementById('confirmarClave-<?php echo $id_js; ?>').value;
        var mensajeAlerta = document.getElementById('mensajeAlerta-<?php echo $id_js; ?>');

        if (nuevaClave.trim() === "" && confirmarClave.trim() === "") {
            return true; // No se ingresaron contraseñas, se permite el envío
        }

        if (nuevaClave !== confirmarClave) {
            mensajeAlerta.style.display = 'block';
            return false; // Las contraseñas no coinciden, se detiene el envío
        } else {
            mensajeAlerta.style.display = 'none';
            return true; // Las contraseñas coinciden, se permite el envío
        }
    }
    <?php
    }
    ?>

    // Funciones específicas para el formulario de creación de usuario
    function validarClavesOnBlur_nuevo() {
        var nuevaClave = document.getElementById('nuevaClave_nuevo').value;
        var confirmarClave = document.getElementById('confirmarClave_nuevo').value;
        var mensajeAlerta = document.getElementById('mensajeAlerta_nuevo');

        if (nuevaClave !== confirmarClave) {
            mensajeAlerta.style.display = 'block';
        } else {
            mensajeAlerta.style.display = 'none';
        }
    }

    // Función para manejar el checkbox "Todos"
    function toggleAll(checkbox) {
        const collapsePermisos = checkbox.closest('.collapse');
        const isChecked = checkbox.checked;
        const checkboxes = collapsePermisos.querySelectorAll('.permiso-checkbox');
        
        checkboxes.forEach(cb => {
            cb.checked = isChecked;
        });
    }

    // Esta es una versión mejorada que también actualiza el estado de "Todos" si se marcan/desmarcan individualmente los permisos.
    // Esta parte es importante para la experiencia de usuario
    document.addEventListener('DOMContentLoaded', () => {
        const modalContainer = document.getElementById('collapsePermisos-nuevo');
        if (modalContainer) {
            modalContainer.addEventListener('change', (event) => {
                if (event.target.classList.contains('permiso-checkbox')) {
                    const allCheckboxes = modalContainer.querySelectorAll('.permiso-checkbox');
                    const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                    const toggleCheckbox = modalContainer.querySelector('#seleccionarTodos-nuevo');
                    if (toggleCheckbox) {
                        toggleCheckbox.checked = allChecked;
                    }
                }
            });
        }
    });

    // Función para alternar la visibilidad de la contraseña
    function togglePasswordVisibility(fieldId, button) {
        const field = document.getElementById(fieldId);
        const icon = button.querySelector('i');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('bx-show-alt');
            icon.classList.add('bx-hide');
        } else {
            field.type = 'password';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show-alt');
        }
    }

    // Función para validar las contraseñas al enviar el formulario
    function validarClaves_nuevo() {
        const clave = document.getElementById('nuevaClave_nuevo').value;
        const confirmarClave = document.getElementById('confirmarClave_nuevo').value;
        const alerta = document.getElementById('mensajeAlerta_nuevo');
        
        if (clave !== confirmarClave) {
            alerta.style.display = 'block';
            return false;
        } else {
            alerta.style.display = 'none';
            return true;
        }
    }

    // Función para validar las contraseñas al salir del campo
    function validarClavesOnBlur_nuevo() {
        const clave = document.getElementById('nuevaClave_nuevo').value;
        const confirmarClave = document.getElementById('confirmarClave_nuevo').value;
        const alerta = document.getElementById('mensajeAlerta_nuevo');
        
        if (clave && confirmarClave && clave !== confirmarClave) {
            alerta.style.display = 'block';
        } else {
            alerta.style.display = 'none';
        }
    }

    // scrip validadcion clave administrador

     function validarClaves(id) {
    // Obtiene los elementos de los campos de contraseña y el mensaje de alerta
    const nuevaClave = document.getElementById('nuevaClave-' + id);
    const confirmarClave = document.getElementById('confirmarClave-' + id);
    const mensajeAlerta = document.getElementById('mensajeAlerta-' + id);

    // Si los campos de contraseña están vacíos, no se requiere validación
    // El formulario se envía sin problemas
    if (nuevaClave.value === "" && confirmarClave.value === "") {
        return true; // No hay necesidad de validar, el formulario es válido
    }

    // Comprueba si los valores de los campos de contraseña son diferentes
    if (nuevaClave.value !== confirmarClave.value) {
        // Muestra el mensaje de alerta si las contraseñas no coinciden
        mensajeAlerta.style.display = 'block';
        return false; // Evita que el formulario se envíe
    } else {
        // Oculta el mensaje de alerta si las contraseñas coinciden
        mensajeAlerta.style.display = 'none';
        return true; // Permite que el formulario se envíe
    }
}

// Función que se activa cuando los campos de contraseña pierden el foco (onblur)
// Esto proporciona una validación en tiempo real sin tener que enviar el formulario
function validarClavesOnBlur(id) {
    // Obtiene los elementos de los campos de contraseña y el mensaje de alerta
    const nuevaClave = document.getElementById('nuevaClave-' + id);
    const confirmarClave = document.getElementById('confirmarClave-' + id);
    const mensajeAlerta = document.getElementById('mensajeAlerta-' + id);

    // Si ambos campos no están vacíos, realiza la validación
    if (nuevaClave.value !== "" && confirmarClave.value !== "") {
        if (nuevaClave.value !== confirmarClave.value) {
            // Muestra el mensaje de alerta si las contraseñas no coinciden
            mensajeAlerta.style.display = 'block';
        } else {
            // Oculta el mensaje de alerta si las contraseñas coinciden
            mensajeAlerta.style.display = 'none';
        }
    }
}

// Función para mostrar/ocultar la contraseña
function togglePasswordVisibility(id, btn) {
    // Obtiene el campo de la contraseña y el icono del botón
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    
    // Cambia el tipo del input entre 'password' y 'text'
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bx-show-alt');
        icon.classList.add('bx-hide');
    } else {
        input.type = 'password';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show-alt');
    }
}
</script>