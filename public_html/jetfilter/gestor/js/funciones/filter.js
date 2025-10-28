//Funcion para verificar que el valor ingresado es entero positivo
function filter(__val__) {
    var preg = /^[0-9]*$/;
    return preg.test(__val__);
 }