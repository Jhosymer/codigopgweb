 let timeoutBusqueda = null;

    document.getElementById('campo_busqueda').addEventListener('input', function() {
        clearTimeout(timeoutBusqueda);
        timeoutBusqueda = setTimeout(() => {
            ejecutarBusqueda();
        }, 300);
    });

    document.getElementById('tipo_busqueda').addEventListener('change', ejecutarBusqueda);

    function ejecutarBusqueda() {
        const termino = document.getElementById('campo_busqueda').value;
        const tipo = document.getElementById('tipo_busqueda').value;
        const tabla = document.getElementById('tabla_resultados');
        const contenedor = document.getElementById('contenedor_resultados');

        if (termino.trim().length === 0) {
            contenedor.style.display = 'none';
            return;
        }

        contenedor.style.display = 'block';

        const formData = new FormData();
        formData.append('termino', termino);
        formData.append('tipo', tipo);

        fetch('sistemas/disponibilidad/buscar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        // Dentro de tu función ejecutarBusqueda(), en el .then(data => { ... })
.then(data => {
    const tabla = document.getElementById('tabla_resultados');
    const contenedor = document.getElementById('contenedor_resultados');

    if (data.trim() !== "") {
        tabla.innerHTML = data;

        // ESTO ACTIVA LOS MENSAJES AL PASAR EL MOUSE
       const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
    } else {
        tabla.innerHTML = '<tr><td colspan="5" class="text-center text-danger">No se encontraron resultados.</td></tr>';
    }
})
        .catch(error => {
            console.error('Error:', error);
        });
    }