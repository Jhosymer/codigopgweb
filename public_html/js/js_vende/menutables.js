$(document).ready(function() {
    // 1. Guardamos la instancia en una variable 'table'
    var table = $('#example').DataTable({
        
        initComplete: function () {
            this.api().columns([1]).every(function () {
                var column = this;
                var select = $('<select class="form-select form-select-sm"><option value="">Seleccione una opción</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $(this).val();
                        // Búsqueda exacta para evitar cruces de datos
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
        },
        language: {
            decimal: ",",
            thousands: ".",
            lengthMenu: "Mostrar _MENU_ ",
            zeroRecords: "No se encontraron resultados",
            info: "_START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            oPaginate: {
                sFirst: "<<",
                sLast: ">>",
                sNext: ">",
                sPrevious: "<"
            },
            sProcessing: "Cargando...",
            emptyTable: "No hay datos disponibles en la tabla"
        }
    });

/*$('#btnImprimirPedido').on('click', function() {
    // 1. Mostrar todas las filas para imprimir el pedido completo
    table.page.len(-1).draw(false);
    
    // 2. Pequeña pausa para que el DOM se actualice
    setTimeout(function() {
        window.print();
        
        // 3. Volver a la paginación normal de 10
        table.page.len(10).draw(false);
    }, 500);
});*/

window.onbeforeprint = function() {
        console.log("Preparando tabla para impresión...");
        table.page.len(-1).draw(false); 
       
        // el DOM se actualice antes de generar la vista previa.
    };

    
    // Este evento ocurre SIEMPRE después de cerrar la ventana de impresión
    window.onafterprint = function() {
        console.log("Restaurando paginación...");
        table.page.len(10).draw(false);
    };

    // de arriba (onbeforeprint) se encargan de expandir la tabla.
    $('#btnImprimirPedido').on('click', function() {
        window.print();
    });


// Orden  Backorders cliente
     $('#tablaBackorders').DataTable({
        // Orden  Fecha (columna 0) descendente
        "order": [[ 2, "desc" ]], 
        
        
        "language": {
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "zeroRecords": "No se encontraron resultados",
            "sSearch": "Buscar:"
        },

        //  Comportamiento de la interfaz
        "dom": 't',          // Solo muestra la tabla ('t'), oculta buscador e info de DT
        "paging": false,     // Desactiva la paginacion (muestra todo de un solo golpe)
        "info": false,       // Oculta el texto de "Mostrando X de Y registros"
        "scrollY": false,    // Evita scroll interno para que use el del contenedor de Bootstrap
        
        // Configuracion especifica de columnas
        "columnDefs": [
            { 
                "targets": 0, 
                "type": "date-euro" // Ayuda a que ordene bien dd/mm/yyyy
            },
            { 
                "orderable": false, 
                "targets": 7        // Desactiva el orden solo en la columna de la barra de "Estado"
            }
        ]
    });


// Orden  Backorders gestor
        $('#tablaBackordersgestor').DataTable({
        "order": [[ 0, "desc" ]], 
        
        
        "language": {
            "emptyTable": "No hay datos disponibles en la tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "infoFiltered": "(filtrado de _MAX_ entradas totales)",
            "zeroRecords": "No se encontraron resultados",
            "sSearch": "Buscar:"
        },

        //  Comportamiento de la interfaz
        "dom": 't',          // Solo muestra la tabla ('t'), oculta buscador e info de DT
        "paging": false,     // Desactiva la paginacion (muestra todo de un solo golpe)
        "info": false,       // Oculta el texto de "Mostrando X de Y registros"
        "scrollY": false,    // Evita scroll interno para que use el del contenedor de Bootstrap
        
        // Configuracion especifica de columnas
        "columnDefs": [
            { 
                "targets": 0, 
                "type": "date-euro" // Ayuda a que ordene bien dd/mm/yyyy
            },
            { 
                "orderable": false, 
                "targets": 8      // Desactiva el orden solo en la columna de la barra de "Estado"
            }
        ]
    });

    


});