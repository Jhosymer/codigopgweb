window.addEventListener('scroll', function() {
    const menu = document.querySelector('.menu');
    // Puedes ajustar '10' a la cantidad de píxeles que quieres que baje
    // el usuario antes de que el menú se fije.
    if (window.scrollY > 10) { 
        menu.classList.add('active');
    } else {
        menu.classList.remove('active');
    }
})