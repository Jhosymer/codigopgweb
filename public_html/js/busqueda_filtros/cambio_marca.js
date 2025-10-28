window.addEventListener("DOMContentLoaded", (event) => {
    
    $('#lista2').change(function(){
        var valorMarca = $(this).val();

        $.ajax({
            data: {
                'id_marca': valorMarca,
                'id_aplicacion': $('#lista1').val(),
                'pagina': paginaActual,
            },
            url: './../../ajax_busquedas/ajax_marca.php',
            dataType: 'json',
            type: 'POST',
            success: function(response2){
                $('#contenido2').css("display","block");
                $('#tabla').css("display","block");
                $('#vehiculo_seleccionado').css("display","none");
                $('#resultado').html(response2.datos);
                $('#registros').val('10');
                document.getElementById('totalResultado').innerHTML = "";
                document.getElementById('navegacion').innerHTML =  response2.paginacion;

                $('#imagen_descendente_modelo').css("display","none");
                $('#imagen_ascendente_modelo').css("display","none");
                $('#imagen_descendente_cilindrada').css("display","none");
                $('#imagen_ascendente_cilindrada').css("display","none");
                $('#imagen_descendente_ano').css("display","none");
                $('#imagen_ascendente_ano').css("display","none");

                $('#igual_modelo').css("display","inline-block");
                $('#igual_exterior').css("display","inline-block");
                $('#igual_interior').css("display","inline-block");
                $('#igual_altura').css("display","inline-block");
            },
            error: function(){
                alert("Error");
            }
        });
    });
});