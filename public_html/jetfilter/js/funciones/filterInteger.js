function filterInteger(evt, input) {
   //Recibe el evento y selecciona la tecla pulsada
   var key = window.Event ? evt.which : evt.keyCode;  
   //Se busca la letra con respecto al codigo de la tecla pulsada 
   var chark = String.fromCharCode(key);
   //Se combina la letra agregada con el resto del campo
   var tempValue = input.value + chark;
   // Si la tecla presionada es un número se verifica el numero, sino retornara para false
   if( ( key >= 48 && key <= 57 ) ) {
      return filter(tempValue);
   } else {
      return key == 8 || key == 13 || key == 0;
   }
 }