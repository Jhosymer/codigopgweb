//Si El formulario de agregar marca se estaba mostrando se deja de mostrar
document.getElementById("form-marca").style.display = "none";
document.getElementById('marca').addEventListener('change', () => {
    var valorCambiado = document.getElementById('marca').value; //Valor de la marca que se seleccionó

    //Si es Otro, es que quieres agregar una nueva marca y se muestra el formulario
    if(valorCambiado == 'Otro'){
        document.getElementById("form-marca").style.display = "";
    }
    else{
        document.getElementById("form-marca").style.display = "none";
        /*----------API PARA TRAER LOS VEHICULOS DE CIERTA MARCA---------- */
        //Parametro 1: La marca de los vehículos
        //Devuelve: El codigo HTML para el select de los vehiculos, que contiene el modelo y el motor del vehículo
        formData = new FormData();
        formData.append('marca', valorCambiado);

        fetch("./../plantillas/seleccionar_vehiculo.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                document.getElementById('vehiculo').innerHTML = data.vehiculo;
            }
        )
        .catch(
            error => alert(error)
        )
    }
});