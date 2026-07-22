
    const modal = document.getElementById('nuevoTicketModal');
    const form = document.getElementById('ticketForm');
    const selectTipo = document.getElementById('tipoSoporte');
    const contenedor = document.getElementById('contenedorFormulario');
    const divVentas = document.getElementById('divVentas');
    const divSoporte = document.getElementById('divSoporte');
    const inputAsunto = document.getElementById('inputAsunto');
    const selectAsunto = document.getElementById('selectAsunto');
    const inputBusqueda = document.getElementById('busquedaFiltro');
    const listaResultados = document.getElementById('listaResultados');
    const inputOculto = document.getElementById('codigoSeleccionado');
    const textoSeleccionado = document.getElementById('textoSeleccionado');

    selectTipo.addEventListener('change', function() {
        contenedor.classList.remove('d-none');
        if (this.value === '1') { // Soporte Técnico
            divSoporte.classList.remove('d-none');
            divVentas.classList.add('d-none');
            selectAsunto.setAttribute('required', 'true');
            inputAsunto.removeAttribute('required');
        } else { // Ventas
            divVentas.classList.remove('d-none');
            divSoporte.classList.add('d-none');
            inputAsunto.setAttribute('required', 'true');
            selectAsunto.removeAttribute('required');
        }
    });

    inputBusqueda.addEventListener('input', function() {
        if (this.value.length < 1) { listaResultados.innerHTML = ''; return; }
        fetch('sistemas/soporte/buscar_filtros.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'termino=' + encodeURIComponent(this.value) + '&tipo=codigo'
        })
        .then(res => res.text()).then(html => listaResultados.innerHTML = html);
    });

    listaResultados.addEventListener('click', function(e) {
        const btn = e.target.closest('.item-resultado');
        if (btn) {
        
        const idProducto = btn.getAttribute('data-id'); 
        const codigoProducto = btn.getAttribute('data-codigo');
        
        inputOculto.value = idProducto;
        inputBusqueda.value = codigoProducto; 
        
        textoSeleccionado.innerText = 'Producto seleccionado: ' + codigoProducto;
        listaResultados.innerHTML = '';
    }
    });

    form.addEventListener('submit', function() {
        inputAsunto.disabled = divVentas.classList.contains('d-none');
        selectAsunto.disabled = divSoporte.classList.contains('d-none');
    });

    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        inputAsunto.disabled = false;
        selectAsunto.disabled = false;
        contenedor.classList.add('d-none');
        divVentas.classList.add('d-none');
        divSoporte.classList.add('d-none');
        textoSeleccionado.innerText = '';
    });


document.getElementById('ticketForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('archivoAdjunto');
    const file = fileInput.files[0];

    if (!file) return; // Si no hay archivo, dejamos pasar

    // Tipos de contenido REAL que permitimos
    const tiposPermitidos = ['image/jpeg', 'image/png', 'application/pdf'];
    const nombre = file.name.toLowerCase();
    const extension = nombre.split('.').pop();
    const extensionesPermitidas = ['jpg', 'jpeg', 'png', 'pdf'];

    //  Extension visual
    if (!extensionesPermitidas.includes(extension)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Formato no permitido',
            text: 'Solo se aceptan archivos JPG, PNG o PDF.',
            confirmButtonColor: '#E2001A'
        });
        return;
    }

    //  Contenido REAL (MIME Type)
    // Si el archivo es un virus.exe renombrado a virus.jpg, file.type será "application/x-msdownload" o vacío.
    if (!tiposPermitidos.includes(file.type)) {
        e.preventDefault(); // DETIENE EL REGISTRO
        fileInput.value = ''; // Borra el archivo del campo
        
        Swal.fire({
            icon: 'warning',
            title: 'Archivo Sospechoso',
            text: 'El sistema detectó que este archivo no es una imagen real aunque tenga extensión .jpg. Por seguridad, el registro se ha cancelado.',
            confirmButtonColor: '#E2001A'
        });
        return;
    }
});