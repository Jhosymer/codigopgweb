// eliminar_producto.js
function eliminado(event, formId) {
    event.preventDefault(); // Evita que el formulario se envíe inmediatamente

    Swal.fire({
        icon: "warning",
        title: "Eliminar",
        text: `¿Está seguro que desea eliminar la información?`,
        showCancelButton: true,
        cancelButtonColor: '#838383',
        confirmButtonColor: '#E2001A',
        confirmButtonText: 'Sí, elimínalo',
        cancelButtonText: "Cancelar",
        footer: "Si se elimina, no se podrá recuperar el registro",
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, se envía el formulario
            document.getElementById('formulario-eliminar-' + formId).submit();
        }
    });
}
