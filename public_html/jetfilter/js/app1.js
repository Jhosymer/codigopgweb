const menun = document.getElementById('menu_s');
const indicador= document.getElementById('indicador');
const secciones = document.querySelectorAll('seccion');

let tamanoIndicador = menun.querySelector('a').offsetWidth;
indicador.style.width = (tamanoIndicador + 50 )+ 'px';

let  indexSeccionActiva ;

const observer = new IntersectionObserver((entradas,observer ) => {
entradas.forEach(entrada=> {
 
    if(entrada.isIntersecting){
        indexSeccionActiva = [...secciones].indexOf(entrada.target);
        
        console.log( indicador.style.transform = `translateX(${(  (tamanoIndicador + 100 )* indexSeccionActiva )}px)`);
        
} });
 },{
    rootMargin: '-80px 0px 0px 0px',
    threshold: 0.3
} );

 secciones.forEach(seccion => observer.observe(seccion) );

// Evento para cuando la pantalla cambie de tamaño.
const onResize = () => {
	// Calculamos el nuevo tamaño que deberia tener el indicador.
	tamanoIndicador = menun.querySelector('a').offsetWidth;
    // Cambiamos el tamaño del indicador.
	indicador.style.width = `${(tamanoIndicador* 2) }px`;

	// Volvemos a posicionar el indicador.
	indicador.style.transform = `translateX(${(tamanoIndicador +58 ) * indexSeccionActiva}px)`;
}
    window.addEventListener('resize', onResize);