<?php
include("./../web/arriba_carpeta.php");
//header ("Expires: Fri, 14 Mar 1980 20:53:00 GMT"); //la pagina expira en fecha pasada 
//header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); //ultima actualizacion ahora cuando la cargamos 
//header ("Cache-Control: no-cache, must-revalidate"); //no guardar en CACHE 
//header ("Pragma: no-cache"); //PARANOIA, NO GUARDAR EN CACHE 

?>
<title>Logos</title>
<section class="lineas_prod color_f">
    <div class="container_text">
        <h3 class="title_descarg_logo"> Colores Corporativos Monocromático</h3>
        <div class="div_logo">
            <div class="div_rojo" >
                <img src="./../img/logo/logo/webfiltros_b.png" alt="Logo Blanco con Fondo Rojo">
            </div>
            <div class="div_blanco">
                <img src="./../img/logo/logo/webfiltros.png" alt="Logo Rojo con Fondo Blanco">
            </div>
            <div class="div_rojo">
                <img src="./../img/logo/logo/webfiltros_n.png" alt="Logo Negro con Fondo Rojo">
            </div>
            <div class="div_negro">
                <img src="./../img/logo/logo/webfiltros.png" alt="Logo Rojo con Fondo Negro">
            </div>
        </div>
        <a id="descargar_colores_corporativos" class="bt_desc_log">Descargar</a>
        
        <p class="text__des_logo">Nos permite el uso del emblema sobre diversos medios publicitarios como revistas, flayers, vallas, foleos y papelería en general</p>

        <h3 class="title_descarg_logo">Control Fondos Planos</h3>
        <div class="div_logo">
            <div class="div_amarillo">
                <img src="./../img/logo/logo/webfiltros_fondo.png" alt="Logo de WebFiltros Blanco con Fondo Amarillo" >
            </div>
            <div class="div_azul">
                <img src="./../img/logo/logo/webfiltros_fondo.png" alt="Logo de WebFiltros Blanco con Fondo Azul" >
            </div>
            <div class="div_rojo_fb">
                <img src="./../img/logo/logo/webfiltros_fondo.png" alt="Logo de WebFiltros Blanco con Fondo Rojo">
            </div>
        </div>
       
       
        <a  href="./../img/logo/logo/webfiltros_fondo.png" download="" class="bt_desc_log">Descargar</a>

        <p class="text__des_logo">
            Muestra el funcionamiento de la marca sobre fondos planos, permitiendo así su aplicación con 
            áreas de inviolabilidad sobre colores sólidos
        </p>

       

       
    
    </div>
    <br><br><br><br>
</section>

<?php
 include ("./../web/abajo_carpeta.php");
?>

<script>
    /*----------------EVENTO PARA CLICK DESDARGAR----------- */
    botonColoresCorporativos = document.getElementById('descargar_colores_corporativos');
    botonColoresCorporativos.addEventListener('click', () => {
        const inputOptions = new Promise((resolve) => {
            //Colores a Escoger para Descargar
            resolve({
                'Rojo': 'Rojo',
                'Blanco': 'Blanco',
                'Negro': 'Negro'
            })
        });

        //Alerta que se va a mostrar al usuario cuando se pulse click en Descargar
        const { value: color } = Swal.fire({
            title: 'Selecciona el color',
            text: '¿De qué color quieres descargar el logo?',
            input: 'radio',
            showCancelButton: true,
            inputOptions: inputOptions, //Los colores que se colocaron como opciones
            inputValidator: (value) => { //Value -> Color escogido al pulsar el botón de confirmación
                //Si no se escogio, aparecera una alerta con el texto retornado
                if (!value) {
                    return '¡Necesitar seleccionar un color!';
                }
                //En caso de que se escoja un color, se descargara la imagen correspondiente
                else if( value == 'Rojo' ){
                    var a = document.createElement('a');

                    a.download = "WebFiltros";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros.png";

                    a.click();
                }
                else if( value == 'Blanco' ){
                    var a = document.createElement('a');

                    a.download = "WebFiltros";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros_b.png";

                    a.click();
                }
                else if( value == 'Negro' ){
                    var a = document.createElement('a');

                    a.download = "WebFiltros";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros_n.png";

                    a.click();
                }
            }
        });
    });
</script>
