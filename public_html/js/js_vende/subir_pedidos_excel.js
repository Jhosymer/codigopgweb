$(document).ready(function() {
    let datosValidos = [];
    let erroresPendientes = [];
    let itemsDuplicados = {};

    // Listener for the event of closing the main modal
    $('#modalSubirArchivo').on('hidden.bs.modal', function() {
        $('#resultados-area').addClass('d-none');
        $('#form-upload-area').removeClass('d-none');
        $('#inputArchivo').val('');
        // Resets all temporary variables to their initial empty state
        datosValidos = [];
        erroresPendientes = [];
        itemsDuplicados = {};
    });

    // Listener for form submission
    $('#formSubirArchivo').on('submit', function(e) {
        e.preventDefault();

        // --- Validación agregada aquí ---
        const inputArchivo = $('#inputArchivo');
        if (inputArchivo.val() === "") {
            Swal.fire({
                icon: "warning",
                title: "Atención...",
                text: "Debes seleccionar un archivo para subir.",
                customClass: {
                    popup: 'swal-small',
                    title: 'swal2-title-small',
                    htmlContainer: 'swal2-html-small'
                }
            });
            return; // Detiene la ejecución si no hay archivo
        }

        $('#form-upload-area').addClass('d-none');
        $('#resultados-area').addClass('d-none');
        $('#loading-spinner').removeClass('d-none');

        let formData = new FormData(this);

        $.ajax({
            url: './sistemas/pedidos/ruta-de-subida.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                manejarRespuestaValidacion(response);
            },
            error: function(xhr, status, error) {
                $('#loading-spinner').addClass('d-none');
                console.log(xhr.responseText);
                $('#resultados-ajax').html('<div class="alert alert-danger">Ocurrió un error al subir el archivo. Revise la consola del navegador.</div>');
                $('#dynamic-footer-area').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>');
                $('#resultados-area').removeClass('d-none');
            }
        });
    });

    // Main function to handle the server validation response
    function manejarRespuestaValidacion(response) {
        $('#loading-spinner').addClass('d-none');

        if (!response.errores || !response.exitos) {
            $('#resultados-ajax').html('<div class="alert alert-danger">Error en la respuesta del servidor. Formato de datos incorrecto.</div>');
            $('#dynamic-footer-area').html(`<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`);
            $('#resultados-area').removeClass('d-none');
            return;
        }

        const groupedItems = {};
        response.exitos.forEach(item => {
            if (!groupedItems[item.codigo]) {
                groupedItems[item.codigo] = [];
            }
            groupedItems[item.codigo].push(item);
        });

        // Limpia los arrays para evitar duplicados en cada llamada
        datosValidos = [];
        erroresPendientes = response.errores;
        itemsDuplicados = {};

        // Itera sobre los ítems exitosos para identificar duplicados y moverlos a la lista de errores
        for (const codigo in groupedItems) {
            const items = groupedItems[codigo];
            if (items.length > 1) {
                const mensaje = `El código '${codigo}' está repetido ${items.length} veces.`;
                erroresPendientes.push({
                    tipo: 'duplicado',
                    codigo: codigo,
                    mensaje: mensaje,
                    items: items
                });
            } else {
                datosValidos.push(items[0]);
            }
        }

        let totalErrores = erroresPendientes.length;
        let totalExitos = datosValidos.length;

        let htmlContent = '';

        if (totalErrores > 0) {
            htmlContent += '<h2>Resultados de la Validación:</h2>';
            htmlContent += `<div class="alert alert-danger" role="alert"> Se encontraron ${totalErrores} errores en el archivo de ${totalErrores + totalExitos} ítems.</div>`;

            htmlContent += '<h4>Detalle de Errores:</h4>';
            htmlContent += `<div class="list-scrollable">
                                <ul class="list-group">`;
            erroresPendientes.forEach(function(error, index) {
                let actionContent = '';
                let statusIcon = '❌';
                let listClass = 'alert-danger';
                let smallText = '';

                if (error.tipo === 'duplicado') {
                    statusIcon = '⚠️';
                    listClass = 'alert-warning';
                    actionContent = `<button type="button" class="btn btn-primary btn-sm btn-corregir-duplicado" data-codigo="${error.codigo}">Corregir Repeticiones</button>`;
                    smallText = `<small class="text-muted d-block mt-1 alert alert-danger">Debe corregir este ítem o eliminar las repeticiones hasta dejar un solo ítem. Si no lo hace, el sistema sumará las cantidades de todas las repeticiones en un solo ítem.</small>`;
                } else if (error.mensaje.includes('no es múltiplo de la unidad de empaque')) {
                    actionContent = `<button type="button" class="btn btn-success btn-sm btn-corregir" data-index="${index}">Corregir</button>`;
                }

                htmlContent += `<li class="list-group-item ${listClass} d-flex justify-content-between align-items-center flex-wrap">
                                    <div class="d-flex align-items-center w-100 justify-content-between">
                                        <span>
                                            <span class="me-2">${statusIcon}</span>
                                            <strong>Código:</strong> ${error.codigo} |
                                            <strong>Mensaje:</strong> ${error.mensaje}
                                        </span>
                                        <div class="action-area d-flex align-items-center" data-index="${index}">
                                            ${actionContent}
                                            <button type="button" class="btn btn-danger btn-sm ms-2 btn-eliminar" data-list="errores" data-index="${index}">🗑️</button>
                                        </div>
                                    </div>
                                    ${smallText}
                                </li>`;
            });
            htmlContent += `</ul>
                            </div>`;

            if (totalExitos > 0 || erroresPendientes.length > 0) {
                const messageContinue = `Se encontraron ${totalExitos} ítems válidos que pueden ser procesados.<br>¿Deseas continuar?`;
                htmlContent += `<p class="mt-3">${messageContinue}</p>`;

                $('#dynamic-footer-area').html(`
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-success" id="btnContinuarValidos">Continuar</button>
                `);

                $('#btnContinuarValidos').off('click').on('click', function() {
                    mostrarItemsValidos();
                });
            } else {
                $('#dynamic-footer-area').html(`<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`);
            }

            $('#resultados-ajax').html(htmlContent);

        } else if (totalExitos > 0) {
            mostrarItemsValidos();
            return;
        } else {
            htmlContent = `<div class="alert alert-warning">No se encontraron ítems válidos en el archivo. Verificar un Archivo </div>`;
            $('#resultados-ajax').html(htmlContent);
            $('#dynamic-footer-area').html(`<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>`);
        }

        $('#resultados-area').removeClass('d-none');
    }
    
    // Listener for the inline "Corregir" button (regular error)
    $('#resultados-ajax').on('click', '.btn-corregir', function() {
        const itemIndex = $(this).data('index');
        const errorItem = erroresPendientes[itemIndex];
        const actionArea = $(this).parent();

        actionArea.html(`
            <div class="input-group input-group-sm">
                <input type="number" class="form-control" value="${errorItem.cantidad_solicitada}" data-index="${itemIndex}">
                <button class="btn btn-success btn-sm btn-confirmar-inline">✔</button>
                <button class="btn btn-danger btn-sm btn-cancelar-inline">✖</button>
            </div>
        `);
    });

    // Listener para el nuevo botón "Corregir Repeticiones"
    $('#resultados-ajax').on('click', '.btn-corregir-duplicado', function() {
        const codigo = $(this).data('codigo');
        const errorItem = erroresPendientes.find(e => e.tipo === 'duplicado' && e.codigo === codigo);
        const items = errorItem.items;

        let duplicateItemsHtml = ``;
        items.forEach((item) => {
            duplicateItemsHtml += `<li class="list-group-item d-flex align-items-center">
                                    <span class="text-success me-2">✅</span>
                                    <span>
                                        <strong>Código:</strong> ${item.codigo} | 
                                        <strong>Cantidad:</strong> ${item.cantidad_solicitada}
                                    </span>
                                </li>`;
        });

        Swal.fire({
            title: `Código Repetido`,
            html: `
                <div class="mb-3">
                    <p class="text-start">El código <strong>${codigo}</strong> se repite en el archivo.</p>
                     <p class="text-start" style="font-size: 0.8rem; color: #6c757d;">
            <i>La cantidad que indique debe ser un múltiplo de la  Unidad de Empaque (${items[0].und_empaque})</i>
        </p>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text">Cantidad a validar:</span>
                    <input type="number" class="form-control" value="${items[0].cantidad_solicitada}" id="cantidad-duplicado-0">
                </div>
                <div class="mb-3">
                    <p class="text-start text-muted" style="font-size: 0.9rem;">Se encontraron las siguientes coincidencias:</p>
                    <ul class="list-group list-group-flush text-start">
                        ${duplicateItemsHtml}
                    </ul>
                </div>
                <div class="mt-2 alert alert-info" role="alert">
                   <p class="text-muted" style="font-size: 0.8rem;"><b>Este código será validado una única vez con la cantidad que usted indique.</b></p>
               </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            cancelButtonText: 'Cancelar',
             confirmButtonColor: '#0d6efd',
            preConfirm: () => {
                const nuevaCantidad = parseInt($('#cantidad-duplicado-0').val());
                if (isNaN(nuevaCantidad) || nuevaCantidad <= 0 || nuevaCantidad % items[0].und_empaque !== 0) {
                    Swal.showValidationMessage(`La cantidad debe ser un número positivo y múltiplo de la unidad de empaque (${items[0].und_empaque}).`);
                    return false;
                }
                return nuevaCantidad;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const nuevaCantidad = result.value;
                const correctedItem = {
                    id: items[0].id,
                    codigo: codigo,
                    cantidad_solicitada: nuevaCantidad,
                    und_empaque: items[0].und_empaque,
                    precio: items[0].precio,
                    total: nuevaCantidad * items[0].precio
                };
                
                // Agrega el item corregido a la lista de válidos
                datosValidos.push(correctedItem);
                
                // Elimina el error de duplicado de la lista de errores pendientes
                const indexToRemove = erroresPendientes.findIndex(e => e.tipo === 'duplicado' && e.codigo === codigo);
                if (indexToRemove !== -1) {
                    erroresPendientes.splice(indexToRemove, 1);
                }
                
                // Vuelve a renderizar la vista para que el usuario vea los cambios
                manejarRespuestaValidacion({
                    status: 'success',
                    errores: erroresPendientes,
                    exitos: datosValidos
                });

                Swal.fire({
                    title: "¡Correcto!",
                    icon: "success",
                    html: `Se corrigieron todas las repeticiones del código <strong>${codigo}</strong>.`,
                    draggable: true
                });
            }
        });
    });

    // Listener for the inline "Confirmar" button
    $('#resultados-ajax').on('click', '.btn-confirmar-inline', function() {
        const inputField = $(this).siblings('input');
        const itemIndex = inputField.data('index');
        const errorItem = erroresPendientes[itemIndex];
        const nuevaCantidad = parseInt(inputField.val());
        const undEmpaque = parseInt(errorItem.und_empaque);

        const actionArea = $(this).parent().parent();
        actionArea.html(`<button type="button" class="btn btn-success btn-sm btn-corregir" data-index="${itemIndex}">Corregir</button>`);
        
        if (isNaN(nuevaCantidad) || nuevaCantidad <= 0 || nuevaCantidad % undEmpaque !== 0) {
            Swal.fire({
                icon: "error",
                title: "Alerta...",
                text: `La cantidad debe ser múltiplo de la unidad de empaque (${undEmpaque}).`,
                customClass: {
                    popup: 'swal-small',
                    title: 'swal2-title-small',
                    htmlContainer: 'swal2-html-small'
                }
            });
            return;
        }

        const correctedItem = {
            id: errorItem.id,
            codigo: errorItem.codigo,
            cantidad_solicitada: nuevaCantidad,
            und_empaque: undEmpaque,
            precio: errorItem.precio,
            total: nuevaCantidad * errorItem.precio
        };

        datosValidos.push(correctedItem);
        erroresPendientes.splice(itemIndex, 1);
        
        manejarRespuestaValidacion({
            status: 'success',
            errores: erroresPendientes,
            exitos: datosValidos
        });
    });

    // Listener for the inline "Cancelar" button
    $('#resultados-ajax').on('click', '.btn-cancelar-inline', function() {
        const itemIndex = $(this).siblings('input').data('index');
        const actionArea = $(this).parent().parent();
        
        actionArea.html(`<button type="button" class="btn btn-success btn-sm btn-corregir" data-index="${itemIndex}">Corregir</button>`);
        
    });

    // General listener for delete button
   $('#resultados-ajax').on('click', '.btn-eliminar', function() {
    const itemIndex = $(this).data('index');
    const listType = $(this).data('list');
    const codigo = $(this).data('codigo');

    Swal.fire({
      title: "¿Estás seguro de Eliminar?",
        html: `<div class="alert alert-danger small mt-2">
              ¡Esta acción no se puede revertir!
                   </div>`,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: 'Aceptar',
      cancelButtonText: "Cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        if (listType === 'errores') {
          erroresPendientes.splice(itemIndex, 1);
          manejarRespuestaValidacion({
            status: 'success',
            errores: erroresPendientes,
            exitos: datosValidos
          });
        } else if (listType === 'exitos') {
          datosValidos.splice(itemIndex, 1);
          mostrarItemsValidos();
        } else if (listType === 'duplicados') {
          const itemIdxToDelete = $(this).data('idx');
          
          itemsDuplicados[codigo].splice(itemIdxToDelete, 1);
          
          if (itemsDuplicados[codigo].length === 1) {
            datosValidos.push(itemsDuplicados[codigo][0]);
            delete itemsDuplicados[codigo];
          } else if (itemsDuplicados[codigo].length === 0) {
            delete itemsDuplicados[codigo];
          }
          
          mostrarItemsValidos();
        }

        Swal.fire(
          "¡Borrado!",
          "El ítem ha sido eliminado.",
          "success"
        );
      }
    });
  });

    // Función que muestra los ítems válidos después de corregir los errores
    function mostrarItemsValidos() {
        let htmlContent = '<h2>Confirmación de Pedido:</h2>';
        let totalPedido = 0;

        // **NUEVA LÓGICA DE CONSOLIDACIÓN**
        const consolidatedItems = {};
        
        // 1. Agrega los ítems que ya fueron corregidos y validados individualmente.
        datosValidos.forEach(item => {
            consolidatedItems[item.codigo] = {
                 id: item.id,
                codigo: item.codigo,
                cantidad_solicitada: (consolidatedItems[item.codigo]?.cantidad_solicitada || 0) + item.cantidad_solicitada,
                und_empaque: item.und_empaque,
                precio: item.precio,
                total: (consolidatedItems[item.codigo]?.total || 0) + item.total
            };
        });

        // 2. Agrega los ítems duplicados que aún están en la lista de errores (sin corregir).
        erroresPendientes.forEach(error => {
            if (error.tipo === 'duplicado') {
                const totalCantidad = error.items.reduce((sum, item) => sum + item.cantidad_solicitada, 0);
                const item = error.items[0]; 
                
                // Consolida en un solo ítem.
                consolidatedItems[item.codigo] = {
                    id: item.id,
                    codigo: item.codigo,
                    cantidad_solicitada: (consolidatedItems[item.codigo]?.cantidad_solicitada || 0) + totalCantidad,
                    und_empaque: item.und_empaque,
                    precio: item.precio,
                    total: (consolidatedItems[item.codigo]?.total || 0) + (totalCantidad * item.precio)
                };
            }
        });
        
        // 3. Convierte el objeto consolidado en un array para mostrarlo.
        const allItems = Object.values(consolidatedItems);

        if (allItems.length > 0) {
            htmlContent += `<div class="list-scrollable">
                                <ul class="list-group mt-3">`;

            allItems.forEach(function(item, index) {
                htmlContent += `<li class="list-group-item alert-success d-flex justify-content-between align-items-center">
                                    <span>
                                        ✅ <strong>Código:</strong> ${item.codigo} | 
                                        <strong>Cantidad:</strong> ${item.cantidad_solicitada} | 
                                        <strong>Precio:</strong> $${item.precio.toFixed(2)} | 
                                        <strong>Total:</strong> $${item.total.toFixed(2)}
                                    </span>
                                    <button type="button" class="btn btn-danger btn-sm btn-eliminar" data-list="exitos" data-index="${index}">🗑️</button>
                                </li>`;
                totalPedido += item.total;
            });

            htmlContent += `</ul>
                            </div>`;
            htmlContent += `<h3 class="mt-4">Total del Pedido: $${totalPedido.toFixed(2)}</h3>`;
        } else {
            htmlContent = `<div class="alert alert-info">No se encontraron ítems válidos para procesar.</div>`;
        }

        $('#resultados-ajax').html(htmlContent);
        
        const hasValidItems = allItems.length > 0;
        $('#dynamic-footer-area').html(`
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="btnConfirmarPedido" ${hasValidItems ? '' : 'disabled'}>Confirmar Pedido</button>
        `);

        $('#btnConfirmarPedido').off('click').on('click', function() {
            procesarPedido(allItems);
        });
        $('#resultados-area').removeClass('d-none');
    }

    // Función que procesa el pedido final
    function procesarPedido(itemsToProcess) {
        $('#loading-spinner').removeClass('d-none');
        $('#resultados-area').addClass('d-none');

        let formDataConfirm = new FormData();
        formDataConfirm.append('itemsValidos', JSON.stringify(itemsToProcess));

        $.ajax({
            url: './sistemas/pedidos/ruta-de-confirmacion.php',
            type: 'POST',
            data: formDataConfirm,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $('#loading-spinner').addClass('d-none');
                
                if (response.status === 'success') {
                    Swal.fire({
                        title: "¡El pedido se creó Exitosamente.!",
                        icon: "success",
                        html: `✅ ¡Pedido Generado y Guardado!<br>
                            <div class="alert alert-info small mt-2">
                                 Ya puedes ver los detalles y hacer cambios si lo necesitas. <b> Recuerda que no empezaremos a procesar tu pedido hasta que lo envíe </b>
                                </div>`,
                           draggable: true
                    }).then(() => {
                        window.location.href = `index.php?pag=pedido&id=${response.pedido_id}`;
                    });
                } else {
                    $('#resultados-area').removeClass('d-none');
                    $('#resultados-ajax').html(`<div class="alert alert-danger">❌ Ocurrió un error al confirmar el pedido: ${response.message}</div>`);
                    $('#dynamic-footer-area').html('<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>');
                }
            },
            error: function() {
                $('#loading-spinner').addClass('d-none');
                $('#resultados-area').removeClass('d-none');
                $('#resultados-ajax').html('<div class="alert alert-danger">Ocurrió un error en la comunicación con el servidor al confirmar. Intente de nuevo.</div>');
                $('#dynamic-footer-area').html('<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>');
            }
        });
    }
});