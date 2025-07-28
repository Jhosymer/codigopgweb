/*
    Cuando cambie el select de categoria, se hara una busqueda de los tipos de esa nueva categoria
    Retornara Data (string): Que va a ser codigo HTML contenido en una variable para colocar dentro
        del select de tipo
*/
categoria = document.getElementById('categoria');
categoria.addEventListener('change', function(){
    
    value = document.getElementById('categoria').value; //Se toma el valor de categoria
    formData = new FormData(); 
    formData.append('categoria', value); //Se agrega en el formData para enviarse en el API

    fetch("./../plantillas/cambiar_categoria.php", {
        method: 'POST',
        body: formData,
    })
    .then( response => response.json() )
    .then(
        data => {
            document.getElementById("tipo").innerHTML = "<option value='N/D'>N/D</option>" + data;
        }
    )
    .catch(
        error => console.log(error)
    )
});