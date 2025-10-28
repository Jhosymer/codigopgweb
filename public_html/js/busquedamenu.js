
    // Declarar variables correctamente
    const boton_busqueda = document.getElementById('boton_busqueda-2');

    boton_busqueda.addEventListener('click', function(event) {
        event.preventDefault(); // Evitar el envío automático del formulario
        const codigo = document.getElementById('campo_busqueda-2');

        if (codigo.value.trim() !== "") {
            document.getElementById('form-submit-2').submit();
        } else {
            alert("Por favor, ingresa un código para buscar."); // Mensaje de error
        }
    });
 
    const select = document.getElementById('aplicacion-select-2');

    select.addEventListener('change', function() {
        if (select.value) {
            document.getElementById('form-submit-aplicacion-2').submit();
        }
    });
