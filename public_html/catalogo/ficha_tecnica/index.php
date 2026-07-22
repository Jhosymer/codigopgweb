<?php 
$loc = "./../../";
$title = "Ficha tecnica";
$description="Ficha tecnica de productos WEB. ";
include("./../../web/header.php");
$url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$svgGmail= '<svg xmlns="http://www.w3.org/2000/svg" class="me-2 x="0px" y="0px" width="100" height="100" viewBox="0 0 48 48">
<path fill="#e0e0e0" d="M5.5,40.5h37c1.933,0,3.5-1.567,3.5-3.5V11c0-1.933-1.567-3.5-3.5-3.5h-37C3.567,7.5,2,9.067,2,11v26C2,38.933,3.567,40.5,5.5,40.5z"></path><path fill="#d9d9d9" d="M26,40.5h16.5c1.933,0,3.5-1.567,3.5-3.5V11c0-1.933-1.567-3.5-3.5-3.5h-37C3.567,7.5,2,9.067,2,11L26,40.5z"></path><path fill="#eee" d="M6.745,40.5H42.5c1.933,0,3.5-1.567,3.5-3.5V11.5L6.745,40.5z"></path><path fill="#e0e0e0" d="M25.745,40.5H42.5c1.933,0,3.5-1.567,3.5-3.5V11.5L18.771,31.616L25.745,40.5z"></path><path fill="#ca3737" d="M42.5,9.5h-37C3.567,9.5,2,9.067,2,11v26c0,1.933,1.567,3.5,3.5,3.5H7V12h34v28.5h1.5c1.933,0,3.5-1.567,3.5-3.5V11C46,9.067,44.433,9.5,42.5,9.5z"></path><path fill="#f5f5f5" d="M42.5,7.5H24H5.5C3.567,7.5,2,9.036,2,11c0,1.206,1.518,2.258,1.518,2.258L24,27.756l20.482-14.497c0,0,1.518-1.053,1.518-2.258C46,9.036,44.433,7.5,42.5,7.5z"></path><path fill="#e84f4b" d="M43.246,7.582L24,21L4.754,7.582C3.18,7.919,2,9.297,2,11c0,1.206,1.518,2.258,1.518,2.258L24,27.756l20.482-14.497c0,0,1.518-1.053,1.518-2.258C46,9.297,44.82,7.919,43.246,7.582z"></path>
</svg>';
$svgX='<svg xmlns="http://www.w3.org/2000/svg" class="me-2" viewBox="0 0 640 640"><!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M453.2 112L523.8 112L369.6 288.2L551 528L409 528L297.7 382.6L170.5 528L99.8 528L264.7 339.5L90.8 112L236.4 112L336.9 244.9L453.2 112zM428.4 485.8L467.5 485.8L215.1 152L173.1 152L428.4 485.8z"/></svg>';

?>

<style>

</style>
    <input type="hidden" id="codigo_filtro" value="">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <section class="card p-4" >
                    <div class="row mt-4" >
                        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
                            <div id="filtro_titulo" class="h4 mb-0">
                               
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <a class="btn-share btn-group-height  me-2" onclick="boton_descargarpdf()">
                                    <i class="bx bx-printer fs-5"></i>
                                </a>    
                                <div class="dropdown">
                                    <a class="btn-share btn-group-height dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-share-alt fs-5"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="shareOnX()">
                                            <?php echo $svgX; ?>X
                                        </a></li>
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="shareOnFacebook()">
                                            <i class='bx bxl-facebook-circle me-2'></i> Facebook
                                        </a></li>
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="shareOnLinkedIn()">
                                            <i class='bx bxl-linkedin-square me-2'></i> LinkedIn
                                        </a></li>
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="shareOnWhatsApp()">
                                            <i class='bx bxl-whatsapp me-2'></i> WhatsApp
                                        </a></li>
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="shareOnGmail()">
                                            <?php echo $svgGmail; ?> Gmail
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item d-flex align-items-center a_web" href="#" onclick="copyLink()">
                                            <i class='bx bx-link me-2'></i> Copiar Enlace
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4" id="detalle_producto" style="display: none;">
                        
                       
                       
                        
                        <div class="col-12 col-lg-6 mb-4 mt-4">
                         <div  id="filtro_carrusel">
                         </div>
                        <div>
                            <button class="btn-icon mt-3" onclick="window.history.back()">
                                Volver
                            </button>
                        </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-4 mt-4" id="filtro_especificaciones">
                        </div>

                        <div class="col-12 col-lg-6 mb-4 table-responsive" id="filtro_aplicacion">
                        </div>
                        <div class="col-12 col-lg-6 mb-4" id="filtro_equivalencia">
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

  

   
    <script>
        <?php 
            if( isset( $_GET['codigoVehiculo'] ) ){
                ?> getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', codigoVehiculo: '<?php echo $_GET['codigoVehiculo'] ?>'}); <?php
            } else if ( isset( $_GET['cod'] ) ) {
                ?> getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', cod: '1'}); <?php
            } else if ( isset( $_GET['esp'] ) ) {
                ?> getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', esp: '1'}); <?php
            } else if ( isset( $_GET['tip'] ) ) {
                ?> getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', tip: '<?php echo $_GET['tip']; ?>'}); <?php
            } else {
                ?> getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>'}); <?php
            }
        ?>

        function getFiltroCodigo({codigo, esp = null, tip = null, cod = null, codigoVehiculo = null}){
            let formData = new FormData();
            if( esp != null ){ formData.append('buscarEspecificacion', 1); }
            else if( cod != null ){ formData.append('buscarCodigo', 1); }
            else if( tip != null ){ formData.append('buscarTipo', tip); }
            else if( codigoVehiculo != null ){ formData.append('codigoVehiculo', codigoVehiculo); }
            formData.append('codigo', codigo);

            fetch("./../../ajax_busquedas/filtro_seleccionado.php", {
                method: 'POST',
                body: formData,
            })
            .then( response => response.json() )
            .then(
                data => {
                    // Actualizar elementos HTML con los datos de la respuesta
                    document.getElementById('codigo_filtro').value = codigo;
                    document.getElementById('detalle_producto').style.display = 'flex';
                    document.getElementById('filtro_titulo').innerHTML = data.titulo;
                    document.getElementById('filtro_carrusel').innerHTML = data.carrusel;
                    
                    // Asignar el HTML de especificaciones primero
                    document.getElementById('filtro_especificaciones').innerHTML = data.especificaciones;
                    
                    document.getElementById('filtro_aplicacion').innerHTML = data.aplicacion;
                    document.getElementById('filtro_equivalencia').innerHTML = data.equivalencia;
                    
                    // Buscar todas las celdas con la clase 'barcode-cell'
                    const barcodeCells = document.querySelectorAll('.barcode-cell');
                    
                    // Iterar sobre cada celda encontrada
                    barcodeCells.forEach(cell => {
                        const barcodeValue = cell.getAttribute('data-barcode-value');
                        if (barcodeValue) {
                    // Limpiar el contenido de la celda
                    cell.innerHTML = '';
                    
                    // Crea un elemento SVG en lugar de un <img>
                    const svgElement = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                  
                    
                    // Dibuja el código de barras en el nuevo elemento SVG
                    JsBarcode(svgElement, barcodeValue, {
                        format: "EAN13",
                        // Ajusta estos valores para el tamaño inicial
                        width: 1.3,
                        height: 30,
                        fontSize: 12,
                        displayValue: true
                    });
                    
                    // Añade el SVG generado a la celda
                    cell.appendChild(svgElement);
                }
                    });
                } 
            )
            .catch(
                error => console.log(error) 
            );
        }

        function boton_descargarpdf(){
            let codigo_enviar = document.getElementById('codigo_filtro').value;
            let win = window.open( `./filtro_descarga.php?codigo=${codigo_enviar}`, '_blank');
            win.focus();
        }

        // Función para cargar la imagen en el modal de Bootstrap
        function loadModalImage(src) {
            document.getElementById('modal-image').src = src;
        }
    </script>

    <script>
    // Función para cargar la imagen en el modal cuando se hace clic
    function loadModalImage(imageSrc) {
        const modalImage = document.getElementById('modal-image');
        modalImage.src = imageSrc;
    }

    document.addEventListener("DOMContentLoaded", function() {
        let position = 0;
        const slides_linea = document.querySelector('.slides_linea');
        const indicatorsl_linea = document.querySelectorAll('.indicatorsl_linea button');
        const totalSlides = slides_linea ? slides_linea.children.length : 0;

        function showSlide(index) {
            if (!slides_linea || totalSlides === 0) return;

            position = index;
            // Asegura que el índice esté dentro del rango
            if (position >= totalSlides) {
                position = 0;
            } else if (position < 0) {
                position = totalSlides - 1;
            }

            slides_linea.style.transform = `translateX(${-position * 100}%)`;

            // Actualiza el estado activo de los indicadores
            indicatorsl_linea.forEach((button, i) => {
                button.classList.remove('active');
            });
            if (indicatorsl_linea[position]) {
                indicatorsl_linea[position].classList.add('active');
            }
        }

        // Inicializa el carrusel en la primera diapositiva
        showSlide(0);

        // Si hay un contenedor de slides, inicializamos los eventos
        if (slides_linea) {
            // Ciclo automático con un intervalo de 8 segundos
            let autoSlideInterval = setInterval(() => {
                showSlide(position + 1);
            }, 8000);

            // Pausa el carrusel al pasar el mouse por encima
            slides_linea.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });

            // Reanuda el carrusel cuando el mouse se va
            slides_linea.addEventListener('mouseleave', () => {
                autoSlideInterval = setInterval(() => {
                    showSlide(position + 1);
                }, 8000);
            });

            // Navegación con los indicadores
            indicatorsl_linea.forEach((button, index) => {
                button.addEventListener('click', () => {
                    showSlide(index);
                });
            });
        }
    });
</script>


    
<div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Imagen en zoom">
            </div>
        </div>
    </div>
</div>

 <?php   
        include("../../web/footer.php");    
    ?>