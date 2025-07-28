
$(".comp_redes").click(function(e){
    var URLactual = window.location;
	var ligthbox_redes =  '<div class="ligthbox_redes"><br>'+
	' <h2> <center>Compartir </center></h2><br><br>'+ 
    '<div class="about_boton_ligthbo_redes"> '+
	`<a href="https://www.twitter.com/intent/tweet?WebFiltros&url=${URLactual}" target="_blank" ><img src="./../img/redes_comp/tw.png"></a>`+
    `<a href="https://www.facebook.com/sharer.php?u=${URLactual}" target="_blank" ><img src="./../img/redes_comp/fb.png"></a>`+
    `<a href="https://www.linkedin.com/shareArticle?url=${URLactual}" target="_blank"><img src="./../img/redes_comp/in.png"></a>`+
    `<a href="https://api.whatsapp.com/send?text=${URLactual}" target="_blank" ><img src="./../img/redes_comp/wp.png"></a>`+
    `<a href="mailto:?subject=WebFiltros&body=${URLactual}" target="_blank" ><img src="./../img/redes_comp/gmail.png"></a>`+
    '<button onclick="boton_copiar()" class="bot_url"><img src="./../img/redes_comp/link.svg" ></button>'+
    '</div><div><a class="btn-close"></a></div></div>';

$("body").append(ligthbox_redes)


$(".btn-close").click(function(){
	$(".ligthbox_redes").remove();
})

  

})

 /*-----------FUNCION PARA COPIAR AL PORTAPAPELES--------------------*/ 
    function boton_copiar(){
        url_a_copiar = location.href;
        navigator.clipboard.writeText(url_a_copiar);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: '¡Enlace Copiado!',
            timer: 1750,
        })
    }


   function boton_correo(){
        codigo = document.getElementById('codigo_filtro').value;
        window.location.href = `mailto:?subject=WebFiltros&body=https://webfiltros.com/filtro/filtro.php?codigo&#61${codigo}`;
    }