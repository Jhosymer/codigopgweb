  $(document).ready(function() {
    $('#example').DataTable({
        initComplete: function () {
            this.api().columns([1]).every(function () {
                var column = this;
                var select = $('<select class="form-select form-select-sm"><option value="">Seleccione una opción</option></select>')
                    .appendTo($(column.footer()).empty())
                    .on('change', function () {
                        var val = $(this).val();
                        
                        // Aquí se permite la búsqueda de caracteres especiales
                        column
                            .search(val ? val : '', true, false)
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
            emptyTable: "No hay datos disponibles en la tabla" // Mensaje personalizado
        }
    });
});