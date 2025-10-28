function comprobarImagen(imagen, i){
    var img = new Image();                
       img.src = URL.createObjectURL(imagen[0]);
       img.onload = function () {
          var ancho = img.width;
          var alto = img.height
          console.log(ancho + ' ' + alto);
          if(ancho <= 1600 && ancho >= 1400 && alto >= 1200 && alto <= 1300 ){
             $('#precaucion_imagen-'+i).css("display","none");
             if (document.getElementById("file-"+i).files && document.getElementById("file-"+i).files[0]) { //Revisamos que el input tenga contenido
                var reader = new FileReader(); //Leemos el contenido
                
                reader.onload = function(e) { //Al cargar el contenido lo pasamos como atributo de la imagen de arriba
                   $('#img-'+i).attr('src', e.target.result);
                }
                
                reader.readAsDataURL(document.getElementById("file-"+i).files[0]);
             }
          }
          else{
             $('#precaucion_imagen-'+i).css("display","block");
          }
       }
 }