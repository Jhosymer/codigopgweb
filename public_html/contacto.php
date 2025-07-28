<?php

include("./web/arriba.php");
?>
<link rel="stylesheet" href="./css/estilo_body_apli.css?t=<?php echo $rann?>">
<link rel="stylesheet" href="./css/estilo_contacto.css?t=<?php echo $rann?>">


 <div class="container">
 
                <div class="row">
                <h1 class="titulo_contacto">Contáctenos</h1>
                </div>
                <form method='POST' class="blueform" action="index.php">
                <div class="row">
                        <h4 class="sub_titulo_contacto">Todos los campos son obligatorios</h4>
                </div>
                <div class="row input-container">
                            <div class="col-xs-12">
                                    <div class="styled-input wide">
                                        <input type="text" name="nombre" id="nombre"  />
                                        <label>Nombre</label> 
                                    </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="styled-input">
                                    <input type="email" name="email" id="email"  />
                                    <label>Correo</label> 
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="styled-input" style="float:right;">
                                    <input type="text" name="n_tlf" id="n_tlf"  />
                                    <label>Numero de Telefono</label> 
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="styled-input wide">
                                    <textarea  name="mensaje" id="mensaje"></textarea>
                                    <label>Mensaje</label>
                                </div>
                            </div>
                            <div class="col-xs-12">
                             <button class="btn-lrg submit-btn " onclick="buscar('1')" id =""> Enviar</button>
                            </div>
                            </form>
                </div>
</div>


<script>
function buscar(historial = null){
            mensaje = document.getElementById('mensaje').value;
            email = document.getElementById('email').value;
            nombre = document.getElementById('nombre').value;
            tlf = document.getElementById('n_tlf').value;
            var validEmail =  /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            

                        if( mensaje != '' && email != '' && nombre != ''  && tlf != ''  ){

                                if( (validEmail.test(email))){

                                        Swal.fire({
                                                    title: 'Correo Enviado',
                                                    icon: 'success',
                                                }).then(function() {
                                                    window.location = "index.php";
                                                    formulario.reset();
                                                  });

                                }else{
                                            Swal.fire({
                                            title: 'Verificar Correo',
                                            icon: 'info',
                                    })
                                }
                            } else{
                            Swal.fire({
                                        title: 'Todos los campos son obligatorios',
                                        icon: 'error',
                                    })
                               }

           


     }



</script>
<script src="https://app.bluecaribu.com/conversion/integration"></script>
<script type='text/javascript'> 


                bctag({
                    config: {
                    token: '281f0e4e16b1b4dedf3185457aa12f03' , 
                    //redirect: "gracias.html"
                },
                forms : {
                     // Selector de Formulario puede ser una clase o ID o un elemento directo
                    ".blueform" : {
                        contact_name     : "nombre",
                        contact_email    : "email",
                        contact_phone    : "n_tlf",
                        lead_msg         : "mensaje",
                    
                    },
                 }
                });
              

</script> 


<?php
include("./web/abajo_carpeta.php");
?>