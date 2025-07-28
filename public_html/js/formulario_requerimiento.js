
function submitform(){

    Swal.fire({
        title:'CONTÁCTENOS',
        html: `<form method='POST' action="AAA">
            <input  Class="input_cont" type="text" name="codigo_fabricante" id="codigo_fabricante" required="obligatorio" placeholder="Codigo Filtro *"> 
            <input  Class="input_cont" type="text" name="fabricante" id="fabricante" required="obligatorio" placeholder="fabricante de filtro">
            <input  Class="input_cont" type="text" name="marca" id="marca" required="obligatorio" placeholder="Marca del vehículo o maquinaria">
            <input  Class="input_cont" type="text" name="modelo" id="modelo" required="obligatorio" placeholder="Modelo del vehículo o maquinaria ">
            <input  Class="input_cont" type="text" name="ano" id="ano" required="obligatorio" placeholder="Año de su vehículo o maquinaria">
            <input  Class="input_cont" type="text" name="motor" id="motor" required="obligatorio" placeholder="Ingrese el tipo de motor">
            <input  Class="input_cont" type="text" name="tlf" id="tlf" required="obligatorio" placeholder="Ingrese Tlf *">
            <input  Class="input_cont" type="email" name="introducir_email" id="email" required="obligatorio" placeholder="Email *">
            <textarea class="textarea_msj" name="comentario" class="texto_mensaje" id="comentario" required="obligatorio" placeholder="Deja aquí tu comentario... *"></textarea>
            </form>`,
        confirmButtonText:'Enviar',
        confirmButtonColor:'#E2001Ac8',
        padding:'1rem',
        background:'true',
        footer:' * Campos son obligatorios',
        showLoaderOnConfirm: true,
        cancelButtonText:'Cancelar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            
            codigo_fabricante= document.getElementById('codigo_fabricante').value;
            fabricante= document.getElementById('fabricante').value;
            marca= document.getElementById('marca').value;
            modelo= document.getElementById('modelo').value;
            ano= document.getElementById('ano').value;
            motor= document.getElementById('motor').value;
            tlf= document.getElementById('tlf').value;
            email= document.getElementById('email').value;
            comentario= document.getElementById('comentario').value; 
           

            if( codigo_fabricante != '' && tlf != '' && email != '' &&  comentario != '' ){
            codigo_fabricante= document.getElementById('codigo_fabricante').value;
            fabricante= document.getElementById('fabricante').value;
            marca= document.getElementById('marca').value;
            modelo= document.getElementById('modelo').value;
            ano= document.getElementById('ano').value;
            motor= document.getElementById('motor').value;
            tlf= document.getElementById('tlf').value;
            email= document.getElementById('email').value;
            comentario = document.getElementById('comentario').value; 

       
                $.ajax({
                    method: "POST",
                    url: "./../busqueda_codigo/crear_requerimiento.php",
                    data:{
                        codigo_fabricante: codigo_fabricante,
                         fabricante: fabricante,
                         marca: marca, 
                         modelo: modelo,
                         ano: ano,
                         motor: motor,
                         tlf: tlf,
                         email: email,
                         comentario: comentario,  
                    }
                }).done(function(){
                      result= {
                        codigo_fabricante: codigo_fabricante,
                        fabricante: fabricante,
                        marca: marca, 
                        modelo: modelo,
                        ano: ano,
                        motor: motor,
                        tlf: tlf,
                        email: email,
                        comentario: comentario,           
                    }
                    })
                .then(
                    data => {
                        Swal.fire({
                            title: 'Exito ',
                            icon: 'success',
                        })
                    }
                )
                .catch(
                    error => {
                        Swal.fire({
                            title: 'Error',
                            icon: 'error',
                        })
                    }
                )  
            }
            else {
                Swal.fire({
                    title: "Campos vacíos",
                    text: "Verifique código, tlf, email, comentario",
                    icon: 'warning',
                })
            }
        }
    })


   
};