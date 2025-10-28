document.getElementById('ticketForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic form validation
    const asunto = document.getElementById('asunto').value;
    const tipoSoporte = document.getElementById('tipoSoporte').value;
    const archivo = document.getElementById('archivo').files[0];
    const detalle = document.getElementById('detalle').value;

    if (!asunto || !tipoSoporte || !detalle) {
        alert('Por favor complete todos los campos obligatorios');
        return;
    }

    // File type validation only if a file is selected
    if (archivo) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!allowedTypes.includes(archivo.type)) {
            alert('Tipo de archivo no permitido. Solo se permiten PDF, JPG y PNG');
            return;
        }
    }

    // Submit the form if validation passes
    this.submit();
});
