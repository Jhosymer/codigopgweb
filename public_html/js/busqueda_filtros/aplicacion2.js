function getAplicacion2(aplicacion, marca){
    $.ajax({
        data: {
            'id_marca': marca,
            'id_aplicacion': aplicacion,
            'pagina': paginaActual,
            'regreso': 1,
        },
        url: './../ajax_busquedas/ajax_marca.php',
        dataType: 'json',
        type: 'POST',
        success: function(response2){
            $('#detalle_producto').css("display","none");
            $('#detalle').css("display","block");
            $('#lista2').css("display","block");
            $('#label_lista2').css("display","block");
            $('#contenido2').css("display","block");
            $('#tabla').css("display","block");
            $('#vehiculo_seleccionado').css("display","none");
            $('#boton_volver').css("display","none");
            $('#resultado').html(response2.datos);
                if(response2.totalFiltro != response2.totalRegistros){
                    document.getElementById('totalResultado').innerHTML = '<p>Mostrando ' + response2.totalFiltro + ' de ' + response2.totalRegistros + ' registros</p>';
                }
                else {
                    document.getElementById('totalResultado').innerHTML = "";
                }
                document.getElementById('navegacion').innerHTML = response2.paginacion;
                document.getElementById("lista1").value = response2.tipo;
                
                $.ajax({
                    data: { 
                        id: aplicacion,
                        id_marca: marca,
                     },
                    url: './../ajax_busquedas/ajax_aplicacion.php',
                    type: 'POST',
                success: function(responseFinal){
                    document.getElementById('texto').value = "";
                    document.getElementById('lista2').innerHTML = "";
                    document.getElementById('lista2').innerHTML = responseFinal;
                },
                error: function(){
                    alert("Error");
                }
            });
        },
        error: function(){
            alert("Error");
        }
    });
}