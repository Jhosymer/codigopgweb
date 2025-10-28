function getRegistro(id_vehiculo,id_tipo,id_marca){
    document.getElementById('contenido2').style.display = "none";
    document.getElementById('tabla').style.display = "none";
    $.ajax({
        data: {
            'id_vehiculo': id_vehiculo,
            'id_tipo': id_tipo,
            'id_marca': id_marca,
        },
        url: "./../../ajax_busquedas/aplicacion_seleccionada.php",
        type: "POST",
        dataType: 'json',
        success: function (response){
            $('#vehiculo_seleccionado').css("display","block");
            $('#boton_volver').css("display","block");
            document.getElementById('vehiculo_seleccionado').innerHTML = response;
        }
    });
}