//Se seleccionan los id´s de los botones para eliminar
arreglo = ['button-pdf1', 'button-pdf2'];
//Se hace un ciclo para que se muestre una alerta al pulsarlo
document.addEventListener('DOMContentLoaded', function(){
    arreglo.forEach( (element) => {
        botones = document.getElementById(element);
        if( botones !== null ){
            botones.addEventListener('click', () => {
                Swal.fire({
                    icon: "warning",
                    title: "Eliminar",
                    text: `¿Está seguro que desea eliminar la información?`,
                    showCancelButton: true,
                    cancelButtonColor: '#838383',
                    confirmButtonColor: '#E2001A',
                    confirmButtonText: 'Si, eliminalo',
                    buttonsStyling: true,
                    cancelButtonText: "Cancelar",
                    footer: "Si se elimina, no se podra recuperar el registro",
                }).then((result) => {
                    //Si se confirma que se quiere eliminar la imagen se borrará
                    if (result.isConfirmed) {
                        document.getElementById('pdf_eliminada').value = element;
                        document.getElementById('editar_pdf').submit();
                    }
                })
            })
        }
    });
});
