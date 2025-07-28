function getData(pagina){
    let input = document.getElementById("texto").value;
    let registros = document.getElementById("registros").value;

    $.ajax({
        data: {
            'campo': input,
            'id_aplicacion': $('#lista1').val(),
            'id_marca': $('#lista2').val(),
            'registros': registros,
            'pagina': pagina,
        },
        dataType: 'json',
        url: "./../ajax_busquedas/ajax_marca.php",
        type: 'POST',
        success: function(response2){
                $('#resultado').html(response2.datos);
                if(response2.totalFiltro != response2.totalRegistros){
                    document.getElementById('totalResultado').innerHTML = '<p>Mostrando ' + response2.totalFiltro + ' de ' + response2.totalRegistros + ' registros</p>';
                }
                else {
                    document.getElementById('totalResultado').innerHTML = "";
                }
                document.getElementById('navegacion').innerHTML = response2.paginacion;
            }
    });
}