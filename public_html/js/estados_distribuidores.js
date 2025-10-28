paginaActual = 1;
document.addEventListener('DOMContentLoaded', () => {
    getData(1, '');
});

/*------------------FUNCION PARA PEDIR LAS DISTRIBUIDORAS POR ESTADOS------------- */
function getData(pagina, estado){
    formData = new FormData();
    formData.append('estado', estado);
    formData.append('pagina', pagina);

    fetch("./../ajax_busquedas/busqueda_distribuidoras.php", {
        method: 'POST',
        body: formData,
    })
    .then( response => response.json() )
    .then(
        data => {
            document.getElementById("grid_distribuidor").innerHTML = data.distribuidoras;
            document.getElementById("paginacion").innerHTML = data.paginacion;
        }
    )
    .catch(
        error => alert(error)
    )
}

document.querySelectorAll(".estado").forEach(el => {
    el.addEventListener("click", e => {
        const name = e.target.getAttribute("name");
        const id = e.target.getAttribute("id");

        mapadiv_seleccionado = document.querySelector('.mapadiv_seleccionado');
        
        if( mapadiv_seleccionado != null ){
            mapadiv_seleccionado.classList.remove("mapadiv_seleccionado");
        }
        document.getElementById(id).classList.add("mapadiv_seleccionado");

        getData(1, name);
    });
}, false);