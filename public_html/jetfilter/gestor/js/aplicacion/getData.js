function getData(pagina, tipo_aplicacion){
    let input = document.getElementById("campo").value;
    let content = document.getElementById("contenido");
    let num_registros = document.getElementById("num_registros").value;

    formData = new FormData();
    formData.append('campo', input);
    formData.append('pagina', pagina);
    formData.append('num_registros', num_registros);
    formData.append('tipo', tipo_aplicacion);

    fetch("./../plantillas/cargar_aplicacion.php", {
        method: 'POST',
        body: formData,
    })
    .then( response => response.json() )
    .then(
        data => {
            content.innerHTML = data.data;
            if( data.totalFiltro != data.totalRegistros ){
                document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
            }
            else {
                document.getElementById('lbl-total').innerHTML = "";
            }
            document.getElementById('paginacion').innerHTML =  data.paginacion;
        }
    )
    .catch(
        error => alert('error')
    )
}