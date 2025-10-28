$(document).ready(function(){
    $('#precaucion_imagen-1').css("display","none");
    $('#precaucion_imagen-2').css("display","none");
    $('#precaucion_imagen-3').css("display","none");
    $('#precaucion_imagen-4').css("display","none");

    $('#file-1').change(function(){
        var imagen = document.getElementById("file-1").files;
        comprobarImagen(imagen, 1);
    });

    $('#file-2').change(function(){
        var imagen = document.getElementById("file-2").files;
        comprobarImagen(imagen, 2);
    });

    $('#file-3').change(function(){
        var imagen = document.getElementById("file-3").files;
        comprobarImagen(imagen, 3);
    });

    $('#file-4').change(function(){
        var imagen = document.getElementById("file-4").files;
        comprobarImagen(imagen, 4);
    });
})