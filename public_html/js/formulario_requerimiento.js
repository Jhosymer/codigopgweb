function submitform() {
    Swal.fire({
        title: 'Requerimiento de Filtro',
        // El HTML del formulario debe ser una cadena limpia sin código JS
        html: `
            <form id="requerimientoForm" method='POST'>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="codigo_fabricante" id="codigo_fabricante" required placeholder="Código Filtro *"> 
                    </div>
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="fabricante" id="fabricante" placeholder="Fabricante del filtro">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="marca" id="marca" placeholder="Marca del vehículo o maquinaria">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="modelo" id="modelo" placeholder="Modelo del vehículo o maquinaria">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="ano" id="ano" placeholder="Año de su vehículo o maquinaria">
                    </div>
                    <div class="col-md-6 mb-3">
                        <input class="form-control" type="text" name="motor" id="motor" placeholder="Tipo de motor">
                    </div>
                </div>

                <div class="mb-3">
                    <input class="form-control" type="tel" name="tlf" id="tlf" required placeholder="Ingrese Teléfono *">
                </div>
                
                <div class="mb-3">
                    <input class="form-control" type="email" name="introducir_email" id="email" required placeholder="Email *">
                </div>

                <div class="mb-3">
                    <textarea class="form-control" name="comentario" id="comentario" required rows="3" placeholder="Deja aquí tu comentario... *"></textarea>
                </div>
                
                </form>
        `,
        confirmButtonText: 'Enviar',
        confirmButtonColor: '#E2001Ac8',
        padding: '1rem',
        background: 'true',
        footer: ' * Campos son obligatorios',
        showLoaderOnConfirm: true,
        cancelButtonText: 'Cancelar',
        showCancelButton: true,

        // **Función clave para validar y enviar el formulario antes de cerrar la alerta**
        preConfirm: () => {
            // 1. Obtener los valores del formulario que está actualmente visible en el modal
            const codigo_fabricante = document.getElementById('codigo_fabricante').value;
            const tlf = document.getElementById('tlf').value;
            const email = document.getElementById('email').value;
            const comentario = document.getElementById('comentario').value;

            // 2. Validación básica de campos obligatorios
            if (!codigo_fabricante || !tlf || !email || !comentario) {
                Swal.showValidationMessage("Por favor, rellena todos los campos obligatorios (*).");
                return false; // Evita que se cierre la alerta
            }
            
            // 3. Recolectar todos los datos para el envío AJAX
            const formData = {
                codigo_fabricante: codigo_fabricante,
                fabricante: document.getElementById('fabricante').value,
                marca: document.getElementById('marca').value,
                modelo: document.getElementById('modelo').value,
                ano: document.getElementById('ano').value,
                motor: document.getElementById('motor').value,
                tlf: tlf,
                email: email,
                comentario: comentario,
            };

            // 4. Retornar la promesa AJAX para manejar el envío
            return $.ajax({
                method: "POST",
                url: "./crear_requerimientos.php",
                data: formData,
            })
            // El resultado de .then() pasa a la siguiente promesa de SweetAlert
            .then(response => {
                // Aquí puedes inspeccionar la 'response' de tu PHP si es necesario
                return response;
            })
            .catch(error => {
                // Si hay un error de conexión o del servidor, se maneja aquí
                Swal.showValidationMessage(`Error al enviar el formulario: ${error.statusText || 'Error desconocido'}`);
            });
        }
        // El bloque .then() se ejecuta DESPUÉS de preConfirm (si preConfirm no falló)
    }).then((result) => {
        // 'result.value' contiene la respuesta del servidor (response de AJAX)
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Éxito',
                text: 'Tu requerimiento ha sido enviado correctamente.',
                icon: 'success',
            });
        } 
        // No se necesita un 'else' si preConfirm maneja los errores de validación y AJAX
    });
}