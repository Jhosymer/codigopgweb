function getFiltro(codigo){
    document.getElementById('detalle').style.display = "none";
    document.getElementById('buscar').style.display = "none";
    document.getElementById('busquedas').style.display = "none";

    formData = new FormData();
    formData.append('codigo', codigo);
    fetch("./../ajax_busquedas/filtro_seleccionado.php", {
        method: 'POST',
        body: formData,
    })
    .then( response => response.json() )
    .then(
        data => {
            $('#detalle_producto').css("display","flex");
            document.getElementById('filtro_titulo').innerHTML = data.titulo;
            document.getElementById('filtro_carrusel').innerHTML = data.carrusel;
            document.getElementById('filtro_especificaciones').innerHTML = data.especificaciones;
            document.getElementById('filtro_aplicacion').innerHTML = data.aplicacion;
            document.getElementById('filtro_equivalencia').innerHTML = data.equivalencia;
        }
    )
    .catch(
        error => alert(error)
    )
}