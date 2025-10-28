   document.addEventListener('DOMContentLoaded', function() {
        var splide = new Splide('#nuevosProductosSplide', {
            type: 'loop', // El carrusel se repite sin fin
            perPage: 4, // Muestra 4 tarjetas a la vez
            perMove: 1, // Se mueve 1 tarjeta a la vez
            autoplay: true, // Activa la reproducción automática
            interval: 3000, // Cambia cada 3 segundos (3000ms)
            rewind: true,
            gap: '1rem', // Espacio entre las tarjetas
            pagination: false,
            arrows: false, // Ocultamos las flechas nativas
            breakpoints: {
                991: {
                    perPage: 3,
                },
                767: {
                    perPage: 2,
                },
                575: {
                    perPage: 1,
                },
            }
        });
        
        // Conectamos los botones de Bootstrap a Splide
        document.querySelector('.carousel-control-prev').addEventListener('click', function() {
            splide.go('<');
        });
        document.querySelector('.carousel-control-next').addEventListener('click', function() {
            splide.go('>');
        });
        
        splide.mount();
    });