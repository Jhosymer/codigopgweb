<?php 
     $loc = "../../";
     $locj = "../";
     $title = "Especificaciones";
     require_once("./index/header.php");
   
?>
<head>
    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
    <script>
        $(document).ready(function() {    
            $('.button').on('click', function(){
                
                //Añadimos la imagen de carga en el contenedor
                //1.1 PDF
                cargando('equivalencias', 1),
                
                $.ajax({
                    type: "POST",
                    url: "./creacion_PDF/equivalenciasPDF.php?generar-catalogo=true",
                    success: function(data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'El PDF se ha generado',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(
                           /* //1.2 PDF
                            cargando('equivalencias', 1.2),

                            $.ajax({
                                type: "POST",
                                url: "./creacion_PDF/equivalenciasPDF2.php?generar-catalogo=true",
                                success: function(data) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'El PDF se ha generado',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(*/
                                        //2do PDF
                                        cargando('vehiculos agricolas', 2),

                                        $.ajax({
                                            type: "POST",
                                            url: "./creacion_PDF/agricolaPDF.php?generar-catalogo=true",
                                            success: function(data) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'El PDF se ha generado',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                }).then(

                                                    //3er PDF
                                                    cargando('vehiculos de pasajeros', 3),

                                                    $.ajax({
                                                        type: "POST",
                                                        url: "./creacion_PDF/pasajeroPDF.php?generar-catalogo=true",
                                                        success: function(data) {
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'El PDF se ha generado',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            }).then(
                                                                //4to PDF
                                                                cargando('vehiculos comerciales', 4),
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "./creacion_PDF/comercialPDF.php?generar-catalogo=true",
                                                                    success: function(data) {
                                                                        Swal.fire({
                                                                            icon: 'success',
                                                                            title: 'El PDF se ha generado',
                                                                            showConfirmButton: false,
                                                                            timer: 1500
                                                                        }).then(
                                                                            //5TO PDF
                                                                            cargando('especificaciones', 5),
                                                                            $.ajax({
                                                                                type: "POST",
                                                                                url: "./creacion_PDF/especificacionesPDF.php?generar-catalogo=true",
                                                                                success: function(data) {
                                                                                    Swal.fire({
                                                                                        icon: 'success',
                                                                                        title: 'El PDF se ha generado',
                                                                                        showConfirmButton: false,
                                                                                        timer: 1500
                                                                                    }).then(
                                                                                        //6to PDF
                                                                                        cargando('vehiculos fuera de carretera', 6.1),
                                                                                        $.ajax({
                                                                                            type: "POST",
                                                                                            url: "./creacion_PDF/fueraCarreteraPDF1.php?generar-catalogo=true",
                                                                                            success: function(data) {
                                                                                                Swal.fire({
                                                                                                    icon: 'success',
                                                                                                    title: 'El PDF se ha generado',
                                                                                                    showConfirmButton: false,
                                                                                                    timer: 1500
                                                                                                }).then(
                                                                                                    //6.2 PDF
                                                                                                    cargando('vehiculos fuera de carretera', 6.2),
                                                                                                    $.ajax({
                                                                                                        type: "POST",
                                                                                                        url: "./creacion_PDF/fueraCarreteraPDF2.php?generar-catalogo=true",
                                                                                                        success: function(data) {
                                                                                                            Swal.fire({
                                                                                                                icon: 'success',
                                                                                                                title: 'El PDF se ha generado',
                                                                                                                showConfirmButton: false,
                                                                                                                timer: 1500
                                                                                                            }).then(
                                                                                                                //6.3 PDF
                                                                                                                cargando('vehiculos fuera de carretera', 6.3),
                                                                                                                $.ajax({
                                                                                                                    type: "POST",
                                                                                                                    url: "./creacion_PDF/fueraCarreteraPDF3.php?generar-catalogo=true",
                                                                                                                    success: function(data) {
                                                                                                                        Swal.fire({
                                                                                                                            icon: 'success',
                                                                                                                            title: 'El PDF se ha generado',
                                                                                                                            showConfirmButton: false,
                                                                                                                            timer: 1500
                                                                                                                        }).then(
                                                                                                                            //7mo PDF
                                                                                                                            cargando('catalogo completo', 7),
                                                                                                                            $.ajax({
                                                                                                                                type: "POST",
                                                                                                                                url: "./creacion_PDF/completoPDF.php?generar-catalogo=true",
                                                                                                                                success: function(data) {
                                                                                                                                    Swal.fire({
                                                                                                                                        icon: 'success',
                                                                                                                                        title: 'El PDF se ha generado',
                                                                                                                                        showConfirmButton: false,
                                                                                                                                        timer: 1500
                                                                                                                                    }).then(
                                                                                                                                        
                                                                                                                                    )
                                                                                                                                },
                                                                                                                                error:function(){
                                                                                                                                    error();
                                                                                                                                }
                                                                                                                            })
                                                                                                                        )
                                                                                                                    },
                                                                                                                    error:function(){
                                                                                                                        error();
                                                                                                                    }
                                                                                                                })
                                                                                                            )
                                                                                                        },
                                                                                                        error:function(){
                                                                                                            error();
                                                                                                        }
                                                                                                    })
                                                                                                )
                                                                                                //Fin 7mo PDF
                                                                                            },
                                                                                            error:function(){
                                                                                                error();
                                                                                            }
                                                                                        })
                                                                                    )
                                                                                    //Fin 6to PDF
                                                                                },
                                                                                error:function(){
                                                                                    error();
                                                                                }
                                                                            })
                                                                            //Fin 5to PDF
                                                                        )
                                                                    },
                                                                    error:function(){
                                                                        error();
                                                                    }
                                                                })
                                                                //Fin 4to PDF
                                                            )
                                                        },
                                                        error:function(){
                                                            error();
                                                        }
                                                    })
                                                    //Fin 3er PDF
                                                )
                                            },
                                            error:function(){
                                                error();
                                            }
                                        })
                                 /*   )
                                    //Fin 2do PDF
                                },
                                error:function(){
                                    error();
                                }
                            })*/
                        )
                        //Fin 1.1 PDF
                    },
                    error:function(){
                        error();
                    }
                });
                //Fin 1er PDF

                return false;
            }); 
            
            
        });    
    </script>
</head>


       <div class="container  mb-2 mt-5" >

   <h1 class="titulo text-uppercase text-center  py-5"> Administrador del Catálogo </h1>

  
   <div class="row">
        <!-- Card para Cargar Especificaciones -->
        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                <h5 class="Roboto-Bold text-center mb-0">Cargar Especificaciones</h5>
                </div>
                <div class="card-body">
                    
                    <a href="./aire_automotriz/espec_aireautomotriz.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Aire Automotriz</p> 
                    </a>
                    <a href="./aire_industrial/espec_aireindustrial.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Aire Industrial</p> 
                    </a>
                    <a href="./cabina/espec_cabina.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Cabina</p> 
                    </a>
                    <a href="./combustible_linea/espec_combustiblelinea.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Combustible en Línea</p> 
                    </a>
                    <a href="./elemento/espec_elemento.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Elemento</p> 
                    </a>
                    <a href="./fluidos/espec_fluidos.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Fluidos</p> 
                    </a>
                    <a href="./panel/espec_panel.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Panel</p> 
                    </a>
                    <a href="./sellado/espec_sellado.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Sellado</p> 
                    </a>
                </div>
            </div>
        </div>

    
        <div class="col-12 col-sm-6 col-md-4 mb-5">
                <div class="card  h-100">
                    <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Cargar Aplicaciones</h5>
                    </div>
                    <div class="card-body">
                        
                        
                        <a href="./aplicacion_agricola/aplicacion_agricola.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Cargar Aplicaciones Agrícolas</p> 
                        </a>
                        <a href="./aplicacion_comercial/aplicacion_comercial.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Cargar Aplicaciones Comerciales</p> 
                        </a>
                        <a href="./aplicacion_fueracarretera/aplicacion_fueracarretera.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Cargar Aplicación Fuera de Carretera</p> 
                        </a>
                        <a href="./aplicacion_liviano/aplicacion_liviano.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Cargar Aplicaciones Liviano</p> 
                        </a>
                        <a href="./marcas_aplicaciones/marcas_aplicaciones.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Marcas</p> 
                        </a>
                        <a href="./vehiculos/vehiculos.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                            <p class="Roboto-Bold">Vehículos</p> 
                        </a>
                    </div>
                </div>
            </div>



        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Cargar Equivalencias</h5>
                </div>
                <div class="card-body">
                    <a href="./equivalencias/equivalencias.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Cargar Datos de Equivalencias</p> 
                    </a>
                    <a href="./marcas_equivalencias/marcas_equivalencias.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Marcas</p> 
                    </a>

                    <a href="./equivalencias/subirlote_equivalenci.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Lote de Equivalencia</p> 
                    </a>
                </div>
            </div>
        </div>
        

        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Gestión de Productos</h5>
                </div>
                <div class="card-body">
                    <a href="./productos/productos.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Clasificación Productos</p> 
                    </a>
                    <a href="./categorias/categorias.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Categorias</p> 
                    </a>
                    <a href="./tipos/tipo.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Tipo</p> 
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Nuevos de Productos</h5>
                </div>
                <div class="card-body">
                    <a href="./nuevos/nuevos_filtros.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Nuevos de Productos</p> 
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Generador de Codigo Qr</h5>
                </div>
                <div class="card-body">
                    <a href="./generar_qr/generador_qr.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Codigo Qr Ficha Tecnica</p> 
                    </a>
                </div>
            </div>
        </div>


        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">PDF</h5>
                </div>
                <div class="card-body">

                <!--<form action="" method="POST" >
                                <input type="hidden" name="generar-catalogo">
                                <input type="" value="Generador de Catalogo PDF" class="g_pdf button">
                     </form>-->

                    <form action="" method="POST" >
                        <input type="hidden" name="generar-catalogo">
                    <a  class="button text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Generador de Catalogo PDF</p> 
                    </a>
                    </form>

                    <a href="" class="text-decoration-none text-black d-flex mt-2 link-hover" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <p class="Roboto-Bold">Subir catalogo completo PDF</p> 
                    </a>

                </div>
            </div>
        </div>
    

        <div class="col-12 col-sm-6 col-md-4 mb-5">
            <div class="card  h-100">
                <div class="card-header card-header-web" >
                    <h5 class="Roboto-Bold text-center mb-0">Reportes</h5>
                </div>
                <div class="card-body">

                    <a id="descargar_excel" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Generador de Aplicaciones excel</p> 
                    </a>
                    <a href="./reporte_busqueda_filtros/espec_busqueda_filtro.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Reporte Busqueda de Filtros</p> 
                    </a>
                    <a href="./reporte_requerimiento_filtros/espec_reporte_requerimiento_filtros.php" class="text-decoration-none text-black d-flex mt-2 link-hover">
                        <p class="Roboto-Bold">Reporte Requerimiento de Filtros</p> 
                    </a>
                </div>
            </div>
        </div>


    </div>


    
</div>





</div>
<?php include('./index/modal_pdf.php'); ?>



<script>
   /* Swal.fire({
        title: 'Pendiente',
        text: 'El PDF de vehiculos fuera de carretera se está generando 6/6',
        imageUrl: './img/icon/loading.gif',
        imageWidth: 100,
        imageHeight: 75,
        imageAlt: 'Custom image',
        padding: '2em 1em 1em 1em',
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonText: "Cancelar",
    }).then((result) => {
        if(result.isConfirmed){
            location.reload()
        }
    }),
    $.ajax({
        type: "POST",
        url: "./creacion_PDF/fueraCarreteraPDF3.php?generar-catalogo=true",
        success: function(data) {
            Swal.fire({
                icon: 'success',
                title: 'El PDF se ha generado',
                showConfirmButton: false,
                timer: 1500
            }).then(

            )
        },
        
        error:function(){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '¡Hubo un error en la generación del PDF!',
            })
        }
    })*/

    function cargando(string, int){
        Swal.fire({
            title: 'Pendiente',
            text: `El PDF de ${string} se está generando ${int}/7`,
            imageUrl: '../img/icon/loading.gif',
            imageWidth: 100,
            imageHeight: 75,
            imageAlt: 'Custom image',
            padding: '2em 1em 1em 1em',
            allowOutsideClick: false,
            showConfirmButton: true,
            confirmButtonText: "Cancelar",
        }).then((result) => {
            if(result.isConfirmed){
                location.reload()
            }
        })
    }

    function error(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '¡Hubo un error en la generación del PDF!',
            timer: 1000
        })
    }
                                                                        
</script>

<script>

// Cargar el select con los datos obtenidos de la consulta
function cargarSelect() {
    // Realizar una petición AJAX al archivo PHP que realiza la consulta
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "plantillas/combo_marca.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Obtener los datos en formato JSON
            var data = JSON.parse(xhr.responseText);

            // Obtener el select
            var select = document.getElementById("marca");

            // Limpiar el select
            select.innerHTML = "";

             // Agregar las opcionen principal 
            var defaultOption = document.createElement("option");
                  defaultOption.value = "";
                 defaultOption.text = "Seleccione una Marca";
                 select.appendChild(defaultOption);

            // Agregar las opciones al select
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].id;
                option.text = data[i].marca;
                select.appendChild(option);
            }
        }
    };
    xhr.send();
}

// Llamar a la función para cargar el select

    
</script>

<script>

    
     /*----------------EVENTO PARA CLICK DESDARGAR----------- */
    botonColoresCorporativos = document.getElementById('descargar_excel');
    botonColoresCorporativos.addEventListener('click', () => {
        cargarSelect();

        let tipo = "";
       
        const inputOptions = new Promise((resolve) => {
            //Colores a Escoger para Descargar
            resolve({

                //'todo': 'Todas',
                'Agricola': 'Agricola',
                'Comercial': 'Comercial',
                'Carretera': 'Fuera de Carretera',
                'liviano': 'liviano'
                
            })
        });

        //Alerta que se va a mostrar al usuario cuando se pulse click en Descargar
        const { value: color } = Swal.fire({

    
            input: 'radio',
            html: `<h2 class="swal2-title" id="swal2-title" style="display: block;">Indique</h2>
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;">marca</div>
           
            <select name="id" id="marca" class="form-select" >
             </select>
            <div class="swal2-html-container" id="swal2-html-container" style="display: block;">¿tipo de Aplicacion?</div> `,

            showCancelButton: true,
            inputOptions: inputOptions, //Los colores que se colocaron como opciones
            inputValidator: (value) => { //Value -> Color escogido al pulsar el botón de confirmación
                //Si no se escogio, aparecera una alerta con el texto retornado
                marca = document.getElementById('marca').value;
             
                if (!value) {
                    return '¡Todos los campos son obligatorios!';
                }
                //En caso de que se escoja un color, se descargara la imagen correspondiente
                else if( value == 'Agricola' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./reporte_aplicacion_excel/aplicaciones_excel.php?tipo=${tipo}&marcaset=${marca}`;

                    a.click();
                }
                else if( value == 'Comercial' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./reporte_aplicacion_excel/aplicaciones_excel.php?tipo=${tipo}&marcaset=${marca}`;

                    a.click();
                }else if( value == 'Carretera' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./reporte_aplicacion_excel/aplicaciones_excel.php?tipo=${tipo}&marcaset=${marca}`;

                    a.click();
                } else if( value == 'liviano' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./reporte_aplicacion_excel/aplicaciones_excel.php?tipo=${tipo}&marcaset=${marca}`;

                    a.click();
                }
                else if( value == 'todo' ){
                    tipo =value;
                    var a = document.createElement('a');
                    a.target = '_blank';
                    window.location.href = `./reporte_aplicacion_excel/aplicaciones_excel.php?tipo=${tipo}&marcaset=${marca}`;

                    a.click();
                }
            }
        });
    });
</script>

<?php

require_once("./index/footer.php");
?>