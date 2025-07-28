$(".overlay").click(function(e){
	var enlaceImagen = e.target.src;
	var data = $(this).attr("data");
	var lightbox = '<div class="ligthbox">'+
						'<img src="'+data+'" alt="">'+
					'</div>';


	$("body").append(lightbox)
	$(".ligthbox").click(function(){
		$(".ligthbox").remove();
		$(".zoomContainer").remove();
		$(".zoomLens").remove();
		$(".zoomWindowContainer").remove();

	})

})