const detalleEl  = document.querySelector("#detalle");
// para obtener la variable hora para la imagen
let getTime = new Date().getTime();

const operationButtons = document.querySelectorAll('[data-operation]');
// listener para obtener el dato cuando se hace click en el botón
operationButtons.forEach(button => {
  button.addEventListener('click', function() {
    // el valor se manda a la consola para verificar los valores
  // alert(this.innerText);
 // alert(rann);
   let codigo = (this.innerText);

   let valor=
   `
    <div>

    <img src="./../images/fichas-filtros/web/${codigo}-3.jpg?t=${getTime}" >
    
                </div>
</div>
<div class="modal-footer">
<a href="./../filtro/filtro.php?codigo=${codigo}" class="link resaltar btn_modal btn-primary">VER MÁS</a>
			
              <a  class="btn_modal btn-secondary" data-bs-dismiss="modal">CERRAR</a>
</div>`;
detalleEl.innerHTML=valor;
    
  });
 
});

