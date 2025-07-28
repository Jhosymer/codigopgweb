window.addEventListener("DOMContentLoaded", (event) => {
    $('#lista1').change(function(){
        var valorCambiado = "id="+$(this).val();
        $.ajax({
            data: valorCambiado,
            url: './../ajax_busquedas/ajax_aplicacion.php',
            type: 'POST',
            success: function(response){
                $('#lista2').css("display", "block");
                $('#label_lista2').css("display", "block");
                $('#lista2').html(response);
            },
            error: function(){
                alert("Error");
            }
        });
    });
});