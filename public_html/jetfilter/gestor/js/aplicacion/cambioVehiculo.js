document.getElementById("form-vehiculo").style.display = "none";
document.getElementById("vehiculo").addEventListener( 'change', () => {
    var valorCambiado =  document.getElementById("vehiculo").value;
    if(valorCambiado == 'Otro'){
        document.getElementById("form-vehiculo").style.display = "block";
    }
    else {
        document.getElementById("form-vehiculo").style.display = "none";
    }
});