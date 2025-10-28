function orden(orden_modelo = 0, orden_cilindrada = 0, orden_ano = 0){
    var valorMarca = document.getElementById('lista2').value;
    var valorAplicacion = document.getElementById('lista1').value;
    let input = document.getElementById("texto").value;

    formData = new FormData(); 
    formData.append('campo', input);
    formData.append('pagina', 1);
    formData.append('id_marca', valorMarca);
    formData.append('id_aplicacion', valorAplicacion);
    if( orden_modelo == 1 ){
        formData.append('orden_modelo', 1); 
        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","inline-block");
        $('#igual_modelo').css("display","none");

        $('#imagen_descendente_cilindrada').css("display","none");
        $('#imagen_ascendente_cilindrada').css("display","none");
        $('#igual_cilindrada').css("display","inline-block");

        $('#imagen_descendente_ano').css("display","none");
        $('#imagen_ascendente_ano').css("display","none");
        $('#igual_ano').css("display","inline-block");
    }
    else if( orden_modelo == 2 ){
        formData.append('orden_modelo', 2); 
        $('#imagen_descendente_modelo').css("display","inline-block");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_modelo').css("display","none");

        $('#imagen_descendente_cilindrada').css("display","none");
        $('#imagen_ascendente_cilindrada').css("display","none");
        $('#igual_cilindrada').css("display","inline-block");

        $('#imagen_descendente_ano').css("display","none");
        $('#imagen_ascendente_ano').css("display","none");
        $('#igual_ano').css("display","inline-block");
    }
    else if( orden_cilindrada == 1 ){
        formData.append('orden_cilindrada', 1); 
        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_modelo').css("display","inline-block");

        $('#imagen_descendente_cilindrada').css("display","none");
        $('#imagen_ascendente_cilindrada').css("display","inline-block");
        $('#igual_cilindrada').css("display","none");

        $('#imagen_descendente_ano').css("display","none");
        $('#imagen_ascendente_ano').css("display","none");
        $('#igual_ano').css("display","inline-block");
    }
    else if( orden_cilindrada == 2 ){
        formData.append('orden_cilindrada', 2); 
        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_modelo').css("display","inline-block");

        $('#imagen_descendente_cilindrada').css("display","inline-block");
        $('#imagen_ascendente_cilindrada').css("display","none");
        $('#igual_cilindrada').css("display","none");

        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_ano').css("display","inline-block");
    }
    else if( orden_ano == 1 ){
        formData.append('orden_ano', 1); 
        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_modelo').css("display","inline-block");

        $('#imagen_descendente_cilindrada').css("display","none");
        $('#imagen_ascendente_cilindrada').css("display","none");
        $('#igual_cilindrada').css("display","inline-block");
        
        $('#imagen_descendente_ano').css("display","none");
        $('#imagen_ascendente_ano').css("display","inline-block");
        $('#igual_ano').css("display","none");
    }
    else if( orden_ano == 2 ){
        formData.append('orden_ano', 2); 
        $('#imagen_descendente_modelo').css("display","none");
        $('#imagen_ascendente_modelo').css("display","none");
        $('#igual_modelo').css("display","inline-block");

        $('#imagen_descendente_cilindrada').css("display","none");
        $('#imagen_ascendente_cilindrada').css("display","none");
        $('#igual_cilindrada').css("display","inline-block");

        $('#imagen_descendente_ano').css("display","inline-block");
        $('#imagen_ascendente_ano').css("display","none");
        $('#igual_ano').css("display","none");
    }

    fetch("./../../ajax_busquedas/ajax_marca.php", {
        method: 'POST',
        body: formData,
    })
    .then( response => response.json() )
    .then(
        data => {
            $('#contenido2').css("display","block");
            $('#tabla').css("display","block");
            $('#vehiculo_seleccionado').css("display","none");
            $('#resultado').html(data.datos);
            $('#registros').val('10');
            document.getElementById('totalResultado').innerHTML = "";
            document.getElementById('navegacion').innerHTML =  data.paginacion;
        }
    )
    .catch(
        error => alert(error)
    )
}
