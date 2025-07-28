 document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("modalImagen");
    // Cambiamos a la nueva clase del botón de cierre
    var spanCerrar = document.getElementsByClassName("modal-close-btn")[0]; 
    var enlaceImagen = document.getElementById("enlaceImagen");
    var imagenEmergente = document.getElementById("imagenEmergente");

    // Define la URL a la que se abrirá la imagen al hacer clic
    var urlRedireccion = "https://apps.apple.com/us/app/web-filtros/id6747077581?platform=iphone"; // ¡REEMPLAZA con tu URL deseada!
    enlaceImagen.href = urlRedireccion;

    // Define la ruta de la imagen que se mostrará
    var rutaImagen = "img/app/App_App_Store.png"; // ¡REEMPLAZA con la ruta de tu imagen!
    imagenEmergente.src = rutaImagen;

    // Mostrar el modal al cargar la página
    modal.style.display = "flex";

    // Función para cerrar el modal al hacer clic en la "X"
    spanCerrar.onclick = function(event) {
        event.stopPropagation();
        modal.style.display = "none";
    }

    // Cerrar el modal si se hace clic en el fondo gris (fuera del contenido central)
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});