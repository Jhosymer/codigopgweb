<link rel="stylesheet" href="./../css/estilo_body_apli.css">
<?php
    include("./../web/arriba_carpeta.php");
?>

<style>

    #paginacion {
        justify-content: center;
    }

</style>

<link rel="stylesheet" href="../css/estilos_links.css">

<title>Distribuidores Autorizados a Nivel Nacional</title>
<meta name="description" content="Listado completo e información de contacto de los distribuidores de productos WEB a nivel nacional">

<div class="distri_over">
<section class="grid_d_vnz ">
 <div>
   <h1 class="title_distr">Distribuidores Autorizados a Nivel Nacional </H1>
    <?php
        include("./mapa_vnz.php");
    ?>
    </div>
    <div class="grid_distribuidor" id="grid_distribuidor">

    </div>
    <div id="paginacion" class="links linka">

    </div>

</section>
</div>

<?php
    include("./../web/abajo_carpeta.php");
?>

<script>
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
</script>