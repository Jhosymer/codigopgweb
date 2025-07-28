$('.button').on('click', function(){ 
    Swal.fire({
        title:'CONTÁCTENOS',
        html: `<form method='POST' action="AAA">
            <input  Class="input_cont" type="text" name="introducir_nombre" id="nombre" required="obligatorio" placeholder="Nombre">
            <input  Class="input_cont" type="email" name="introducir_email" id="email" required="obligatorio" placeholder="Email">
            <textarea class="textarea_msj" name="introducir_mensaje" class="texto_mensaje" id="mensaje" required="obligatorio" placeholder="Deja aquí tu comentario..."></textarea>
            </form>`,
        confirmButtonText:'Enviar',
        confirmButtonColor:'#E2001Ac8',
        footer:"Todos los campos son obligatorios",
        padding:'1rem',
        background:'true',
        showLoaderOnConfirm: true,
        cancelButtonText:'Cancelar',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            mensaje = document.getElementById('mensaje').value;
            correo = document.getElementById('email').value;
            nombre = document.getElementById('nombre').value;

            if( mensaje != '' && correo != '' && nombre != '' ){
                formData = new FormData(); 
                formData.append('mensaje', mensaje);
                formData.append('correo', correo);
                formData.append('nombre', nombre);

                fetch("./../correo.php", {
                    method: 'POST',
                    body: formData,
                })
                .then( response => response.json() )
                .then(
                    data => {
                        Swal.fire({
                            title: 'Exito',
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
                    title: 'No se pueden dejar campos vacios',
                    icon: 'warning',
                })
            }
        }
    })
});
