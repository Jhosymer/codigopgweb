let position = 0;
const slides_linea = document.querySelector('.slides_linea');
const totalslides_linea = document.querySelectorAll('.slides_linea img').length;

function showSlide(index) {
    position = index;
    slides_linea.style.transform = `translateX(${-position * 50}%)`;

    // Actualizar el estilo de los botones indicadores
    const indicatorsl_linea = document.querySelectorAll('.indicatorsl_linea button');
    indicatorsl_linea.forEach((button, i) => {
        if (i === index) {
            button.classList.add('active');
        } else {
            button.classList.remove('active');
        }
    });
}

// Cambia la imagen cada 20 segundos para que se quede más tiempo
setInterval(() => {
    position = (position + 1) % totalslides_linea;
    slides_linea.style.transform = `translateX(${-position * 50}%)`;
    showSlide(position); // Actualizar el estilo de los botones indicadores automáticamente
}, 8000); // Cambia la imagen cada 8 segundos
