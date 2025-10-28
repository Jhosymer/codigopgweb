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

                    a.download = "WebFiltros_rojo.png";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros.png";

                    a.click();
                }
                else if( value == 'Blanco' ){
                    var a = document.createElement('a');

                    a.download = "WebFiltros_blanco.png";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros_b.png";

                    a.click();
                }
                else if( value == 'Negro' ){
                    var a = document.createElement('a');

                    a.download = "WebFiltros_negro.png";
                    a.target = '_blank';
                    a.href= "./../img/logo/logo/webfiltros_n.png";

                    a.click();
                }
            }
        });
    });


    /*----------------EVENTO PARA CLICK DESDARGAR tipo de formato----------- */
botonColoresCorporativos = document.getElementById('descargar_formato_corporativos');
botonColoresCorporativos.addEventListener('click', () => {
    const inputOptions = new Promise((resolve) => {
        //Colores a Escoger para Descargar
        resolve({
            'svg_web': 'web.svg',
            'png_web': 'web.png',
            'ai_web': 'web. ai',
            'svg_filtros': 'web filtros.svg',
            'ai_filtros': 'web filtros. ai',
        })
    });

    //Alerta que se va a mostrar al usuario cuando se pulse click en Descargar
    const { value: color } = Swal.fire({
        title: 'Selecciona el Logo y Formato',
        text: '¿qué Logo y Formato quieres descargar el logo?',
        input: 'radio',
        showCancelButton: true,
        inputOptions: inputOptions, //Los Formato que se colocaron como opciones
        inputValidator: (value) => { //Value -> Color escogido al pulsar el botón de confirmación
            //Si no se escogio, aparecera una alerta con el texto retornado
            if (!value) {
                return '¡Necesitar seleccionar un color!';
            }
            //En caso de que se escoja un color, se descargara la imagen correspondiente
            else if( value == 'svg_web' ){
                var a = document.createElement('a');

                a.download = "LOGO_web.svg";
                a.target = '_blank';
                a.href= "./../img/logo/logo/LOGO_web.svg";

                a.click();
            }
            else if( value == 'png_web' ){
                var a = document.createElement('a');

                a.download = "LOGO_web.png";
                a.target = '_blank';
                a.href= "./../img/logo/logo/LOGO_web.png";

                a.click();
            }
            else if( value == 'ai_web' ){
                var a = document.createElement('a');

                a.download = "LOGO_WEB.ai";
                a.target = '_blank';
                a.href= "./../img/logo/logo/LOGO_WEB.ai";

                a.click();
            }

            else if( value == 'svg_filtros' ){
                var a = document.createElement('a');

                a.download = "LOGO_webfiltros.svg";
                a.target = '_blank';
                a.href= "./../img/logo/logo/Logo_WEBfiltros.svg";

                a.click();
            }
            else if( value == 'ai_filtros' ){
                var a = document.createElement('a');

                a.download = "WebFiltros.ai";
                a.target = '_blank';
                a.href= "./../img/logo/logo/Logo_WEB_filtros.ai";

                a.click();
            }
        }
    });
});
