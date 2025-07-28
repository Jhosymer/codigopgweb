$(".bot1").click(function(e){
	var ligthbox_text = '<div class="ligthbox_text"><br><br>'+
	' <p class="titulos_informac ">POLÍTICAS DE CALIDAD </p>'+ 
    ' <p class="bont_inform">Jet-Filter, C.A. es una empresa fabricante  <b> de filtros para aire, refrigerante, aceites y combustible  </b>tanto de vehículos de servicios liviano como vehículo y maquinaria de servicio pesado, trabaja siempre en función del cliente, la seguridad de sus trabajadores el medio ambiente y el cumplimiento en general de leyes y requerimientos de todas las partes interesadas. Para ello se tienen como principios básicos: </p>'+ 
  
	'<ol class="informacion"><li class="informacion_li">Hacer las cosas bien desde el principio. </li>'+
	'<li class="informacion_li">Satisfacer continuamente a nuestros clientes, superando sus expectativas de calidad a través del oportuno suministro de productos, asistencia técnica y mejor relación precio / valor</li>'+
	'<li class="informacion_li">Capacitar a los trabajadores para que desarrollen habilidades y conocimientos, que le permitan, a través del camino de la productividad, mejorar su calidad de vida y ser parte del proceso integral de la organización y del país. </li>'+
	'<li class="informacion_li">Mejora continua de los procesos y productos para ajustarnos a las expectativas de las partes interesadas y a las oportunidades que se generen en el tiempo. De esta manera se asegura la permanencia de la empresa en el mercado como fuente generadora de riqueza.</li>'+
	'</ol>'+
    '<div class="btn-close">x</div>'+
	'</div>';

$("body").append(ligthbox_text)
	$(".ligthbox_text").click(function(){
		$(".ligthbox_text").remove();
	})
	$(".btn-close").click(function(){
		$(".ligthbox").remove();
	})
  

})

$(".bot2").click(function(e){
	var ligthbox_text = '<div class="ligthbox_text"><br><br>'+
    ' <p class="titulos_informac ">OBJETIVOS DE LA CALIDAD </p>'+ 
  
	'<ol class="informacion"><li class="informacion_li">Mantener la participación planificada en el mercado cumpliendo con las exigencias del mismo.</li>'+
	'<li class="informacion_li">Elaborar productos de acuerdo  al  plan anual de ventas con la finalidad de realizar entregas a tiempo de las unidades solicitadas por  los clientes.</li>'+
	'<li class="informacion_li">Mejorar continuamente los productos y procesos  con el objetivo de lograr  mayor productividad para  satisfacer y exceder las necesidades y expectativas de los clientes.</li>'+
	'<li class="informacion_li">Capacitar al personal de acuerdo al plan de formación establecido anualmente en la organización.</li>'+
	'</ol>'+
    '<div class="btn-close">x</div>'+
	'</div>';

$("body").append(ligthbox_text)
$(".ligthbox_text").click(function(){
	$(".ligthbox_text").remove();
})
$(".btn-close").click(function(){
	$(".ligthbox").remove();
})

  

})


$(".bot3").click(function(e){
	var ligthbox_text =  '<div class="ligthbox_text"><br><br>'+
	' <p class="titulos_informac ">GARANTÍA DE FILTROS WEB</p>'+ 
    ' <p class="bont_inform"><b> JET-FILTER C.A, Garantiza que todos los Filtros WEB están fabricados contra cualquier defecto de material o mano de obra. La garantía abarca todo vehículo o maquinaria que se le haya instalado un Nuevo Filtro WEB según el manual de especificaciones y que lleve un adecuado uso y mantenimiento recomendado por los fabricantes del equipo. </b> Si se encuentra un filtro WEB con defecto de material o fabricación, el mismo se reemplazará por un Filtro Nuevo.</p>'+ 
	'<p class="bont_inform">En caso de un daño al motor atribuible en forma directa a la falla de un Filtro WEB nuevo que ha sido instalado en forma correcta, se pagará la reparación a una condición equivalente a la que existía antes de la falla. El daño deberá ser comprobado por un técnico autorizado por Filtros WEB antes de cualquier reparación del motor.<p>'+
    '<p class="bont_inform">La garantía no cubre condiciones resultantes del uso impropio, negligente o incumplimiento de las instrucciones de instalación de un filtro.<p>'+
	'<p class="bont_inform">Las garantías para automóviles y equipos nuevos permanecen en vigencia cuando se utilizan Filtros WEB, siguiendo las mismas recomendaciones del fabricante en cuanto al tiempo de uso o el kilometraje.  <p>'+
	'<p class="bont_inform">Los Filtros WEB deben estar almacenados en un lugar apropiado considerándose como tal cualquier local que los resguarde de los efectos del sol, del agua, del polvo, caídas, golpes entre otros.  JET-FILTER  C.A, no asume responsabilidad por daños o desperfectos en el funcionamiento a consecuencia de un mal almacenamiento o manipulación de los filtros.<p>'+
	'<p class="bont_inform">Esta garantía podrá hacerse valida en cualquiera de los fabricantes o distribuidores autorizados. <p>'+
	'<div class="btn-close">x</div><div class="btn-impr"><a href="./PDF/GARANTIA_WEBFILTROS.pdf" target="_blank" ><img src="./img/home/printer.svg?t=<?php echo $rann?>" alt=""></a></div>'+
	'</div>';

$("body").append(ligthbox_text)
$(".ligthbox_text").click(function(){
	$(".ligthbox_text").remove();
})
$(".btn-close").click(function(){
	$(".ligthbox").remove();
})

  

})