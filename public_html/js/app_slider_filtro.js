window.addEventListener('load', function(){
let opciones = {
	    slidesToShow: 1,
        slidesToScroll: 1,
        rewind: true,
        arrows: {
			prev: '.carousel__anterior',
			next: '.carousel__siguiente'
        },
	responsive: [
		{
		  // screens greater than >= 775px
		  breakpoint: 450,
		  settings: {
			// Set to `auto` and provide item width to adjust to viewport
			slidesToShow: 2,
			slidesToScroll: 2
		  }
		},{
		  // screens greater than >= 1024px
		  breakpoint: 800,
		  settings: {
			slidesToShow: 4,
			slidesToScroll: 4
		  }
		}
	]
};
	
slider = new Glider(document.querySelector("#lista_producto"), opciones);

let actual = 0;
const conteo = slider.track.childElementCount;
let timeout = null;
slider.scrollItem(actual);
function deslizar(milisegs){
	timeout = setTimeout(()=>{
	   if(actual<conteo-4) actual++;
	 else actual = 0;
	   slider.scrollItem(actual);

   }, milisegs);
 }

	  slider.ele.addEventListener("glider-animated", function(){
		
		actual = slider.slide;
		window.clearInterval(timeout);
		deslizar(4000);
		
	
		let parent = document.getElementById('lista_producto');

		parent.addEventListener('mouseleave', function() {
			actual = slider.slide;
		window.clearInterval(timeout);
		deslizar(4000);
		});

		parent.addEventListener('mouseenter', function() {
			actual = slider.slide;
			window.clearInterval(timeout);
			});

		});

	});
