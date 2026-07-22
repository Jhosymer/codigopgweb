<?php 
function alerta_actualizado() {
    // Verificar si existe la variable de sesión 'alerta_activa'
    if (isset($_SESSION["alerta_activa"])) {
        // Obtener el mensaje de la sesión, si no existe, usar un mensaje por defecto
        $mensaje = $_SESSION['mensaje_alerta'] ?? 'La información fue procesada.';
        
        // Obtener el tipo de ícono (success, error, warning, info)
        $icono = $_SESSION['icono_alerta'] ?? 'success';
        $title =  $_SESSION['title'] ?? '¡Operación Exitosa!';
?>

        <script>
            Swal.fire({
                icon: '<?php echo htmlspecialchars($icono, ENT_QUOTES, 'UTF-8'); ?>',
                title: '<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>',
                text: '<?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Puedes agregar acciones adicionales aquí
                }
            });
        </script>
<?php
        // Eliminar las variables de sesión para que la alerta no se vuelva a mostrar
        unset($_SESSION['alerta_activa']);
        unset($_SESSION['mensaje_alerta']);
        unset($_SESSION['icono_alerta']);
        unset($_SESSION['title']);
    }
}

function alerta_pedido_abierto() {
?>
  <script>
    Swal.fire({
      title: 'Pedido Abierto',
        html: `<div class="alert alert-info small mt-2">
                       Tienes un pedido activo. Para poder continuar, debes guardarlo o enviarlo.  <b>¿Qué deseas hacer?</b>
                   </div>`,
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Guardar Pedido',
      cancelButtonText: 'Continuar en el Pedido',
      showDenyButton: true,
      denyButtonText: 'Enviar Pedido',
      allowOutsideClick: false,
      confirmButtonColor: '#4b74e1', 
      denyButtonColor: '#39bf86', 
       cancelButtonColor: '#808080',
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirecciona para guardar el pedido.
        document.getElementById('btn_guardar').click();
      } else if (result.isDenied) {
        // Redirecciona para enviar el pedido.
        document.getElementById('btn_enviar').click();
      } else if (result.isDismissed) {
        // Si el usuario hace clic en "Continuar en el Pedido"
       
      }
    });
  </script>
<?php
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const formulario = document.getElementById('pedido_form');

    // Lógica para el botón "Guardar"
    const btnGuardar = document.getElementById('btn_guardar');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function() {
            Swal.fire({
                title: '¿Quieres Guardar el pedido?',
                html: `<div class="alert alert-info small mt-2">
                      Tu pedido se guardará y podrás editarlo cuando quieras. <b> Recuerda que no será procesado hasta que lo envíes</b>
                   </div>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#E2001A',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear un campo oculto para el modo "guardar"
                    const modoInput = document.createElement('input');
                    modoInput.type = 'hidden';
                    modoInput.name = 'modo';
                    modoInput.value = 'guardar';
                    formulario.appendChild(modoInput);

                    // Enviar el formulario con el modo "guardar"
                    formulario.submit();
                }
            });
        });
    }

    // Lógica para el botón "Enviar"
  const btnEnviar = document.getElementById('btn_enviar');
if (btnEnviar) {
    btnEnviar.addEventListener('click', function() {
        
        // Buscamos filas que tengan la clase 'item-row' y que NO sean la fila de entrada de datos
        const itemsEnTabla = document.querySelectorAll('#invoiceItem tbody tr.item-row:not(.gris)');

        if (itemsEnTabla.length === 0) {
            Swal.fire({
                title: '¡Pedido Vacío!',
                text: 'No puedes enviar un pedido que no tiene artículos. Por favor, agrega al menos uno.',
                icon: 'error',
                confirmButtonColor: '#E2001A',
                confirmButtonText: 'Entendido'
            });
            return; // Detiene la ejecución aquí mismo
        }
        // --- FIN DE VALIDACIÓN ---

        // Si pasó la validación, mostramos la confirmación de envío
        Swal.fire({
            title: '¿Quieres Enviar el pedido?',
            html: `<div class="alert alert-primary small mt-2">
            Al enviar, el pedido pasará a ser procesado y <b> no podrás hacer más cambios.</b>
                </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E2001A',
            cancelButtonColor: '#808080',
            confirmButtonText: 'Enviar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const modoInput = document.createElement('input');
                modoInput.type = 'hidden';
                modoInput.name = 'modo';
                modoInput.value = 'cerrar';
                formulario.appendChild(modoInput);
                formulario.submit();
            }
        });
    });
}
    
    
    // Lógica para el botón de borrar un pedido completo
    const botonesBorrarPedido = document.querySelectorAll('.borrar-pedido');
    botonesBorrarPedido.forEach(boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault(); // Evita la acción por defecto del enlace

            const idPedido = boton.getAttribute('data-id');

            Swal.fire({
                title: '¿Seguro que quieres eliminar el pedido?',
                 html: `<div class="alert alert-primary small mt-2">
               Si lo eliminas, no podrás recuperarlo. <b>Esta acción es permanente.</b>
                   </div>`,
              
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E2001A',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'sistemas/pedidos/crud.php?id=' + idPedido;
                }
            });
        });
    });

    // Lógica para el botón de borrar un ítem de la lista de pedidos
   const botonesBorrarItem = document.querySelectorAll('.borrar-item-pedido');
        botonesBorrarItem.forEach(boton => {
            boton.addEventListener('click', (e) => {
                e.preventDefault();

            const idItem = boton.getAttribute('data-id');
             const codPro = boton.getAttribute('data-codpro');

            Swal.fire({
                title: '¿Eliminar Artículo?',
                 html: `<div class="alert alert-danger small mt-2">
               El artículo con código <b> ${codPro} </b> será eliminado del pedido.
                   </div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E2001A',
                cancelButtonColor: '#808080',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'sistemas/pedidos/crud.php?id_lista_pedido=' + idItem;
                }
            });
        });
    });



    const downloadLink = document.getElementById('descargar-excel-btn');

    downloadLink.addEventListener('click', async function(e) {
        e.preventDefault();

        const result = await Swal.fire({
            title: '¿Quieres descargar el archivo de ejemplo?',
            text: "El archivo será generado y descargado. Puedes cancelarlo en cualquier momento.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#E2001A',
            cancelButtonColor: '#808080',
            confirmButtonText: 'Sí, descargar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            const loadingSwal = Swal.fire({
                title: 'Generando archivo...',
                text: 'Por favor, espera un momento. La descarga comenzará pronto.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch("./sistemas/combos/excelgenerado.php");

                if (!response.ok) {
                    throw new Error('Error al generar el archivo. Por favor, inténtalo de nuevo.');
                }
                
                // Crea un enlace para la descarga
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = response.headers.get('Content-Disposition').split('filename=')[1].replace(/['"]/g, '');
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                a.remove();
                
                // Muestra la alerta de éxito
                await Swal.fire({
                    title: '¡Descarga completa!',
                    html: '<p>Tu archivo se ha descargado correctamente.</p>' +
                          '<p class="small text-danger mt-3">' +
                          '<b>Advertencias:</b><br/>' +
                          '1. La cantidad solicitada debe ser un <b>múltiplo de la unidad de empaque</b>. ' +
                          'Por ejemplo, si un producto viene en cajas de 12 unidades, la cantidad ' +
                          'solicitada puede ser 12, 24, 36, etc.<br/>' +
                          '2. <b>No modifiques los encabezados de las columnas.</b> Solo cambia los datos que se encuentran debajo de ellos.<br/>' +
                          '3. <b>No cambies los valores</b> de la columna <b>"Ud.Emp"</b>.<br/>' +
                          '4. <b>Solo puedes ingresar códigos de productos</b> que estén en el menú desplegable. ' +
                          'Si no encuentras el código, es porque no está disponible.<br/>' +
                          '</p>' +
                          '<hr>' +
                          '<p class="small mt-2">Una vez que edites el archivo, asegúrate de <b>guardarlo y subirlo</b>, usando la opción <b>"Subir Archivo"</b> para generar tu pedido.</p>',
                    icon: 'success',
                    confirmButtonText: 'Entendido'
                });

            } catch (error) {
                console.error(error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al generar el archivo. Por favor, inténtalo de nuevo.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            } finally {
                Swal.close(loadingSwal);
            }
        }
    });

 
});
</script>


