// Obtén todos los elementos con la clase "slide" (videos e imágenes)
const slides = document.querySelectorAll('.slides');
let currentIndex = 0; // Índice del elemento actual

// Función para mostrar el siguiente elemento
function showNextSlide() {
    // Oculta el elemento actual
    slides[currentIndex].style.opacity = 0;

    // Incrementa el índice para pasar al siguiente elemento
    currentIndex = (currentIndex + 1) % slides.length;

    // Si el siguiente elemento es un video, lo reproducimos
    if (slides[currentIndex].tagName === 'VIDEO') {
        slides[currentIndex].play();
        slides[currentIndex].onended = showNextSlide; // Cuando termine el video, pasa al siguiente
    } else {
        // Si es una imagen, mostramos la imagen durante 6 segundos
        setTimeout(showNextSlide, 6000);
    }

    // Muestra el siguiente elemento
    slides[currentIndex].style.opacity = 1;
}

// Inicia mostrando el primer elemento
slides[currentIndex].style.opacity = 1;

// Si el primer elemento es un video, configura su evento "onended"
if (slides[currentIndex].tagName === 'VIDEO') {
    slides[currentIndex].onended = showNextSlide;
} else {
    // Si es una imagen, configura un temporizador de 6 segundos
    setTimeout(showNextSlide, 6000);
}