$(".overlay").click(function(e){
	var enlaceImagen = e.target.src;
	var data = $(this).attr("data");
	codigo = e.target.getAttribute('data-codigo');
	
	var lightbox = '<div class="ligthbox">'+
						'<img src="'+data+'" alt="">'+
						 `<div class="vermas_lightbox"><a href="./../filtro/filtro.php?codigo=${codigo}" class="link resaltar"> <img src="./../img/tipo/bt.png" class="ver_mas" alt=""> </a></div>`+
					'</div>';


	$("body").append(lightbox)
	$(".ligthbox").click(function(){
		$(".ligthbox").remove();
		$(".zoomContainer").remove();
		$(".zoomLens").remove();
		$(".zoomWindowContainer").remove();

	})
})
