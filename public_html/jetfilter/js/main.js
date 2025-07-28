$(".imagen").click(function(e){
	var enlaceImagen = e.target.src;
	var data = $(this).attr("data");
	var lightbox = '<div class="ligthbox">'+
						'<img src="'+enlaceImagen+'" alt="" id="zoom_07" data-zoom-image="'+data+'">'+
					'</div>';

$("body").append(lightbox)
			$('#zoom_07').ezPlus({
    zoomType: 'lens',
    lensShape: 'round',
    lensSize: 250
});
	$(".ligthbox").click(function(){
		$(".ligthbox").remove();
		$(".zoomContainer").remove();
		$(".zoomLens").remove();
		$(".zoomWindowContainer").remove();

	})

})