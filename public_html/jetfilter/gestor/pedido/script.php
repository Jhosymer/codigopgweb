


<script>

//calculo del total de las filas cantidad por precio
const cantidadEl   = document.querySelector("#cantidad")
const precio_uEl   = document.querySelector("#precio_u")
const total_ppEl   =  document.getElementById("total_pp")
const stockEl   =  document.getElementById("stock")


const inputCP = document.getElementById("codigo_p")
const inputNP = document.getElementById("nombre_p")
const lista_NP = document.getElementById("lista_np")
const lista_CP = document.getElementById("lista_cp")
const inputidP = document.getElementById("id_pro")


cantidadEl.addEventListener("keyup",()=>{
    total_ppEl.value = (cantidadEl.value * precio_uEl.value );
   //console.log(cantidadEl.value * precio_uEl.value)
	
});

</script>
<script>

//buscar por nombre 
document.querySelector("#nombre_p").addEventListener("keyup",()=>{
        let inputNP = document.getElementById("nombre_p").value
    let lista_NP = document.getElementById("lista_np")
	//console.log(inputNP)
    if (inputNP.length > 0) {
    
        let url = "sistemas/combos/busquedas_nombre.php"
        let formData = new FormData()
        formData.append("nombre_p", inputNP)

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors" //Default cors, no-cors, same-origin
        }).then(response => response.json())
            .then(data => {
                lista_NP.style.display = 'block'
                lista_NP.innerHTML = data
            })
            .catch(err => console.log(err))
        } else {
			lista_NP.style.display = 'none'
    }

});


</script>
<script>
//buscar por codigo
document.querySelector("#codigo_p").addEventListener("keyup",()=>{

        let inputCP = document.getElementById("codigo_p").value
    let lista_CP = document.getElementById("lista_cp")
	//console.log(inputCP)
    if (inputCP.length > 0) {
		
        let url = "sistemas/combos/busqueda_codigo.php"
        let formData = new FormData()
        formData.append("codigo_pr", inputCP)

        fetch(url, {
            method: "POST",
            body: formData,
            mode: "cors" //Default cors, no-cors, same-origin
        }).then(response => response.json())
            .then(data => {
                lista_CP.style.display = 'block'
                lista_CP.innerHTML = data
            })
            .catch(err => console.log(err))
        } else {
        lista_CP.style.display = 'none'
    }

});
function mostrar(id,codigo,descripcion,precio,stock) {

    lista_CP.style.display = 'none'
	lista_NP.style.display = 'none'

	inputidP.value = `${id}`
	inputCP.value = `${codigo}`
	inputNP.value = `${descripcion}`
	precio_uEl.value = `${precio}`
	stockEl.value = `${stock}`

	//total_ppEl.value = (cantidadEl.value * precio_uEl.value );
    //alert(`nombre : ${nombre}  ,  codigo : ${codigo}  , id : ${id}  , precio : ${precio} `)
}

</script>