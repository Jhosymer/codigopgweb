function zoomImagen() {
	imagenes = document.querySelectorAll('.imagen');
    imagenes.forEach((imagen) => {
        imagen.addEventListener('click', function(e){
            var enlaceImagen = e.target.src;
            var data = $(this).attr("data");
            var lightbox = '<div class="ligthbox">'+
                                '<img src="'+enlaceImagen+'" alt="" id="zoom_07" data-zoom-image="'+data+'">'+
                            '</div>';
					    
            $('body').append(lightbox)
            $(".ligthbox").click(function(){
                $(".ligthbox").remove();
                $(".zoomContainer").remove();
                $(".zoomLens").remove();
                $(".zoomWindowContainer").remove();
            })

        });
    });
}