$(".overlay").click(function(e){
	
	//var enlaceImagen = e.target.src;
	var data = $(this).attr("data");
	codigo = e.target.getAttribute('data-codigo');
	let codigo_url = codigo.split(" ").join("%20") // >
	//var codigo_url = codigo.replaceAll("%", " ");
	//alert (codigo_url)
	
	var valor = `
	<div class="modal fade" id="modalp_calidad${codigo_url}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            
            <div class="modal-body">
                <div>
				<img src="${data}" alt="">
                </div>
            </div>
            <div class="modal-footer">
			<a href="./../filtro/filtro.php?codigo=${codigo}" class="link resaltar btn_modal btn-primary">VER MÁS</a>
			
			
              <a  class="btn_modal btn-secondary" data-bs-dismiss="modal">CERRAR</a>
            </div>
          </div>
        </div>
      </div>`;
	


	$("body").append(valor)
	


})
