<?php 
    include_once("arriba.php");
?>
 <style>
.form-control {
    display: block;
    width: 100%;
    padding: .375rem .75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: #ff;
    background-clip: padding-box;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}
.form-control[type=file]:not(:disabled):not([readonly]) {
    cursor: pointer;
}

.form-label {
    margin-bottom: .5rem;
}

.mb-3 {
    margin-bottom: 1rem !important;
}
label {
    display: inline-block;
}
 </style>
<head>
<link rel="stylesheet" href="./../../css/modal.css">
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
<body>
 <title>Especificaciones</title>
<link rel="stylesheet" href="./css/estilos_especificaciones.css">
<script src="../js/sweetAlerta.js"></script>
        <table>
            <div>
                <h2 class="catalogo">Administración del Catálogo</h2>  
            </div>
            <tr>
                <td class="titulo">Cargar Especificaciones</td>
                <td class="titulo">Cargar Aplicaciones</td>
            </tr>
            <tr>
                <td>
                    <ul class="lista">
                        <li class="espe"><a href="./aire_automotriz/espec_aireautomotriz.php">Aire Automotriz</a></li>
                        <li class="espe"><a href="./aire_industrial/espec_aireindustrial.php">Aire Industrial</a></li>
                        <li class="espe"><a href="./combustible_linea/espec_combustiblelinea.php">Combustible en Linea</a></li>
                        <li class="espe"><a href="./elemento/espec_elemento.php">Elemento</a></li>
                        <li class="espe"><a href="./panel/espec_panel.php">Panel</a></li>
                        <li class="espe"><a href="./sellado/espec_sellado.php">Sellado</a></li>
                        <li class="espe"><a href="./fluidos/espec_fluidos.php">Fluidos</a></li>
                         <li class="espe"><a href="./cabina/espec_cabina.php">Cabina</a></li>
                    </ul>
                </td>
                <td>
                    <ul class="lista">
                        <li class="espe"><a href="./aplicacion_comercial/aplicacion_comercial.php">Cargar Aplicaciones Comerciales</a></li>
                        <li class="espe"><a href="./aplicacion_liviano/aplicacion_liviano.php">Cargar Aplicaciones Liviano</a></li>
                        <li class="espe"><a href="./aplicacion_agricola/aplicacion_agricola.php">Cargar Aplicaciones Agricola</a></li>
                        <li class="espe"><a href="./aplicacion_fueracarretera/aplicacion_fueracarretera.php">Cargar Aplicacion Fuera de Carretera</a></li>
                        <li class="espe"><a href="./vehiculos/vehiculos.php">Vehiculos</a></li>
                        <li class="espe"><a href="./marcas_aplicaciones/marcas_aplicaciones.php">Marcas</a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="titulo">Cargar Equivalencias</td>
                <td class="titulo">Menú de Reportes</td>
            </tr>
            <tr>
                <td>  
                    <ul class="lista">          
                        <li class="espe"><a href="./equivalencias/equivalencias.php">Cargar Datos de Equivalencias</a></li>
                         <li class="espe"><a href="./marcas_equivalencias/marcas_equivalencias.php">Marcas</a></li>
                         <li class="espe"><a href="./equivalencias/subirlote_equivalenci.php">Lote de Equivalencia</a></li>
                    </ul>
                </td>
                <td>
                    <ul class="lista">          
                        <li class="espe">
                            <form action="" method="POST">
                                <input type="hidden" name="generar-catalogo">
                                <input type="" value="Generador de Catalogo PDF" class="g_pdf button">
                            </form>
                        </li>
                         <li class="espe"><a id="descargar_excel">Generador de Aplicaciones excel </a></li>  
                         <li class="espe"><a href="./reporte_busqueda_filtros/espec_busqueda_filtro.php">Reporte Busqueda de Filtros</a></li>
                         <li class="espe"><a href="./reporte_requerimiento_filtros/espec_reporte_requerimiento_filtros.php">Reporte Requerimiento de Filtros</a></li> 
                          <li class="espe"><a data-bs-toggle="modal" data-bs-target="#uploadModal">Subir catalogo completo PDF</a></li>     
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="titulo">Gestión de Productos</td>
                <td class="titulo">Nuevo</td>
            </tr>
            <tr>
                <td>
                    <ul class="lista"> 
                        <li class="espe"><a href="./productos/productos.php">Clasificación Productos</a></li>         
                        <li class="espe"><a href="./categorias/categorias.php">Categorias</a></li>
                        <li class="espe"><a href="./tipos/tipo.php">Tipo</a></li>
                    </ul>
                </td>
                <td>
                    <ul class="lista">
                        <li class="espe"><a href="./nuevos/nuevos_filtros.php">Nuevos</a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td class="titulo">Generador de Codigo Qr</td>
                <td class="titulo"></td>
            </tr>
            <tr>
                <td>
                    <ul class="lista"> 
                        <li class="espe"><a href="./generar_qr/generador_qr.php">Codigo Qr</a></li>         
                        
                    </ul>
                </td>
                <td>
                    <ul class="lista">
                        <li class="espe"></li>
                    </ul>
                </td>
            </tr>
        </table>
</div>




<?php

include('./index/modal_pdf.php');
    include("./abajo.html");

    
?>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.js"></script>
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
           
            <select name="id" id="marca" >
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