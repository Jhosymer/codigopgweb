(function(){
    const listElements = document.querySelectorAll('.menu__item--show');
    const list = document.querySelector('.menu__links');
    const menu = document.querySelector('.menu__hamburguer');
    
    // Asignamos la función del menú principal aquí para que SIEMPRE esté disponible.
    // La visibilidad debe controlarla el CSS con media queries.
    if (menu) {
        menu.addEventListener('click', () => {
            // Esta es la línea clave que debe ejecutarse al hacer clic
            list.classList.toggle('menu__links--show');
        });
    }

    // Almacena la función anónima para poder eliminarla más tarde
    const subMenuToggle = (element) => {
        let subMenu = element.children[1];
        let height = 0;
        element.classList.toggle('menu__item--active');

        if (subMenu.clientHeight === 0) {
            height = subMenu.scrollHeight;
        }
        
        subMenu.style.height = `${height}px`;
    };

    // Función para activar el menú desplegable en móviles
    const enableMobileMenu = () => {
        listElements.forEach(element => {
            // Usamos una función que llama a subMenuToggle para pasar el 'element'
            element._mobileClickListener = () => subMenuToggle(element);
            element.addEventListener('click', element._mobileClickListener);
        });
    };

    // Función para limpiar los estilos y clases de móviles
    const disableMobileMenu = () => {
        listElements.forEach(element => {
            // 💡 Ahora podemos eliminar el listener correctamente
            if (element._mobileClickListener) {
                element.removeEventListener('click', element._mobileClickListener);
                delete element._mobileClickListener;
            }
            if (element.children[1]) {
                element.children[1].removeAttribute('style');
            }
            element.classList.remove('menu__item--active');
        });
    };

    // Lógica para manejar el redimensionamiento de la ventana
    // Solo aplica la lógica de submenús en resoluciones móviles (<= 968px)
    window.addEventListener('resize', () => {
        if (window.innerWidth > 968) {
            disableMobileMenu();
            // Esto asegura que si el menú estaba abierto en móvil, se cierra en desktop
            if (list.classList.contains('menu__links--show')) {
                 list.classList.remove('menu__links--show');
            }
        } else {
            // IMPORTANTE: Asegúrate de que esta función solo se ejecute una vez
            // para evitar múltiples listeners
            if (listElements.length > 0 && !listElements[0]._mobileClickListener) {
                enableMobileMenu();
            }
        }
    });

    // Cargar la lógica al inicio, según el tamaño de la pantalla
    if (window.innerWidth <= 968) {
        enableMobileMenu();
    }
})();