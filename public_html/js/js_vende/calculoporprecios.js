document.addEventListener("DOMContentLoaded", function() {
    const table = document.getElementById("invoiceItem");
    const alertContainer = document.getElementById("alert-container");
    const agregarItemRow = document.getElementById("agregar-item-row");
    const miBotonDeEnvio = document.getElementById("miBotonDeEnvio");
    
    let filaEditando = null;

    // Lógica para la fila de agregar
    function configurarFilaAgregar() {
        const cantidadEl = agregarItemRow.querySelector(".quantity");
        const precio_uEl = agregarItemRow.querySelector(".precio_u");
        const total_ppEl = agregarItemRow.querySelector(".total_pp");
        const undempEl = agregarItemRow.querySelector(".und_empaque");
        
        function validarYCalcular() {
            if (!alertContainer.innerHTML.includes("El producto con código")) {
                alertContainer.innerHTML = "";
            }
            miBotonDeEnvio.disabled = true;
            
            const cantidad = parseFloat(cantidadEl.value);
            const undEmpaque = parseFloat(undempEl.value);
            const precioUnitario = parseFloat(precio_uEl.value);
            const codigoProducto = agregarItemRow.querySelector(".item_code").value;

            if (isNaN(cantidad) || isNaN(undEmpaque) || isNaN(precioUnitario) || cantidad <= 0) {
                total_ppEl.value = "";
                return;
            }

            if (cantidad % undEmpaque !== 0) {
                const ejemplo1 = undEmpaque * 1;
                const ejemplo2 = undEmpaque * 2;
                const ejemplo3 = undEmpaque * 3;
                const mensaje = `<b>¡Atención!</b><br> La cantidad (${cantidad}) para el producto "${codigoProducto}" debe ser un múltiplo de la unidad de empaque (${undEmpaque}).<br>Ejemplos de múltiplos válidos: ${ejemplo1}, ${ejemplo2}, ${ejemplo3}, etc.`;
                alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">${mensaje}</div>`;
                total_ppEl.value = "";
                return;
            }

            const resultado = cantidad * precioUnitario;
            total_ppEl.value = resultado.toFixed(2);
            miBotonDeEnvio.disabled = false;
        }

        cantidadEl.addEventListener("input", validarYCalcular);
        precio_uEl.addEventListener("input", validarYCalcular);
    }
    
    configurarFilaAgregar();

    // Lógica del buscador
    function configurarBuscador(inputEl, listaEl, otraListaEl, url, paramName) {
        let items = [];
        let currentIndex = -1;

        inputEl.addEventListener("keyup", (event) => {
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                manejarNavegacionConFlechas(event, items);
                return;
            }

            if (event.key === 'Enter' || event.key === 'Tab') {
                return;
            }

            if (inputEl.value.length > 0) {
                otraListaEl.style.display = 'none';
                
                let formData = new FormData();
                formData.append(paramName, inputEl.value);

                fetch(url, {
                    method: "POST",
                    body: formData,
                    mode: "cors"
                })
                .then(response => response.json())
                .then(data => {
                    listaEl.style.display = 'block';
                    listaEl.innerHTML = data;
                    
                    items = listaEl.querySelectorAll('li');
                    currentIndex = -1; 
                    
                    const elementoActivoDelPHP = listaEl.querySelector('li.active');
                    if (elementoActivoDelPHP) {
                        currentIndex = Array.from(items).indexOf(elementoActivoDelPHP);
                    } else if (items.length > 0) {
                        items[0].classList.add('active');
                        currentIndex = 0;
                    }
                })
                .catch(err => console.log(err));
            } else {
                listaEl.style.display = 'none';
                items = [];
                currentIndex = -1;
            }
        });

        inputEl.addEventListener("keydown", (event) => {
            if (event.key === 'Enter' || event.key === 'Tab') {
                event.preventDefault();
                const elementoActivo = listaEl.querySelector('li.active');
                if (elementoActivo) {
                    elementoActivo.click();
                }
            }
        });

        function manejarNavegacionConFlechas(event, items) {
            if (!items || items.length === 0) return;

            if (event.key === 'ArrowDown') {
                if (currentIndex !== -1) {
                    items[currentIndex].classList.remove('active');
                }
                currentIndex = (currentIndex + 1) % items.length;
                items[currentIndex].classList.add('active');
            } else if (event.key === 'ArrowUp') {
                if (currentIndex !== -1) {
                    items[currentIndex].classList.remove('active');
                }
                if (currentIndex <= 0) {
                    currentIndex = items.length - 1;
                } else {
                    currentIndex--;
                }
                items[currentIndex].classList.add('active');
            }
        }
    }
    
    const inputCP = agregarItemRow.querySelector(".item_code");
    const inputNP = agregarItemRow.querySelector(".item_name");
    const lista_CP = agregarItemRow.querySelector(".item_code_list");
    const lista_NP = agregarItemRow.querySelector(".item_name_list");

    configurarBuscador(inputCP, lista_CP, lista_NP, 'sistemas/combos/busqueda_codigo.php', 'codigo_pr');
    configurarBuscador(inputNP, lista_NP, lista_CP, 'sistemas/combos/busquedas_nombre.php', 'nombre_p');
    
    window.mostrar = function(id, codigo, descripcion, und_empaque, precio, disponibilidad) {
        const fila = agregarItemRow;
        const cantidadEl = fila.querySelector(".quantity");
        
        lista_CP.style.display = 'none';
        lista_NP.style.display = 'none';

        const codigosExistentes = Array.from(document.querySelectorAll('#invoiceItem tbody tr:not(.editing-row, #agregar-item-row)')).map(row => {
            const cells = row.querySelectorAll('td, th');
            if (cells.length > 1) {
                return cells[1].textContent.trim();
            }
            return null;
        }).filter(code => code !== null);

        if (codigosExistentes.includes(codigo)) {
            alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">
                <b>¡Atención!</b><br> El producto con código "${codigo}" ya se encuentra en la lista.
            </div>`;
            cantidadEl.disabled = true;
        } else {
            alertContainer.innerHTML = '';
            cantidadEl.disabled = false;
        }
        
        fila.querySelector(".id_pro").value = id;
        fila.querySelector(".item_code").value = codigo;
        fila.querySelector(".item_name").value = descripcion;
        fila.querySelector(".stock").value = disponibilidad;
        fila.querySelector(".precio_u").value = precio;
        fila.querySelector(".und_empaque").value = und_empaque;
        
        const event = new Event('input');
        fila.querySelector(".quantity").dispatchEvent(event);
    }

    // Lógica para el manejo de clics en la tabla
    table.addEventListener('click', function(event) {
        const editarBtn = event.target.closest('.editar-item');
        const guardarBtn = event.target.closest('.guardar-item');
        const cancelarBtn = event.target.closest('.cancelar-item');
        const agregarBtn = event.target.closest('.agregar-mas');
        const borrarBtn = event.target.closest('.borrar-item-pedido');

        if (editarBtn) {
            event.preventDefault();
            const row = editarBtn.closest('tr');
            if (filaEditando && filaEditando !== row) {
                alertContainer.innerHTML = `<div class="alert alert-warning" role="alert">Por favor, guarda o cancela la edición de la fila actual primero.</div>`;
                return;
            }
            filaEditando = row;
            hacerFilaEditable(row);
        } else if (guardarBtn) {
            event.preventDefault();
            const row = guardarBtn.closest('tr');
            guardarEdicion(row);
        } else if (cancelarBtn) {
            event.preventDefault();
            const row = cancelarBtn.closest('tr');
            cancelarEdicion(row);
        } else if (agregarBtn) {
            event.preventDefault();
            const fila = agregarItemRow;
            const id_pro = fila.querySelector(".id_pro").value;
            const codigoProductoNuevo = fila.querySelector(".item_code").value;
            const cantidad = fila.querySelector(".quantity").value;
            const precio_u = fila.querySelector(".precio_u").value;
            const total_pp = fila.querySelector(".total_pp").value;
            const undEmpaque = parseFloat(fila.querySelector(".und_empaque").value);
            // El modo ahora es "agregarmas"
            const modo = "agregarmas";
            
            if (!id_pro || !cantidad || !precio_u || !total_pp || parseFloat(cantidad) % undEmpaque !== 0 || parseFloat(cantidad) <= 0) {
                 alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">Debe seleccionar un producto y completar todos los campos de la fila correctamente. La cantidad debe ser un múltiplo de la unidad de empaque.</div>`;
                 return;
            }

            const codigosExistentes = Array.from(document.querySelectorAll('#invoiceItem tbody tr:not(.input-row, .editing-row)')).map(row => {
                const cells = row.querySelectorAll('td, th');
                if (cells.length > 1) {
                    return cells[1].textContent.trim();
                }
                return null;
            }).filter(code => code !== null);

            if (codigosExistentes.includes(codigoProductoNuevo)) {
                alertContainer.innerHTML = `<div class="alert alert-danger" role="alert"><b>¡Atención!</b><br> El producto con código "${codigoProductoNuevo}" ya se encuentra en la lista.</div>`;
                return;
            }
            
            const id_pedido = document.getElementById("id_pedido").value;
            
            const formData = new FormData();
            formData.append('modo', modo);
            formData.append('id_pro', id_pro);
            formData.append('cantidad', cantidad);
            formData.append('precio_u', precio_u);
            formData.append('total_pp', total_pp);
            formData.append('id_pedido', id_pedido);

            fetch('sistemas/pedidos/crud.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                window.location.reload(); 
            })
            .catch(error => {
                alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">Hubo un problema al agregar el producto.</div>`;
            });
        } if (borrarBtn) { 
            event.preventDefault();

            const idItem = borrarBtn.getAttribute('data-id');
            const codPro = borrarBtn.getAttribute('data-codpro');

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
        }


    });

    // Lógica para hacer la fila editable
    function hacerFilaEditable(row) {
        if (filaEditando) {
            row.classList.add('editable');
            agregarItemRow.style.display = 'none';
            row.querySelector('.editar-item').style.display = 'none';
            row.querySelector('.borrar-item-pedido').style.display = 'none';
            alertContainer.innerHTML = '';
            
            row.classList.add('editing-row'); 
            
            const quantityCell = row.querySelector('.item-quantity');
            const priceCell = row.querySelector('.item-precio');
            const totalCell = row.querySelector('.item-total');
            const actionCell = row.querySelector('td.text-center');
            
            const undemp = row.getAttribute('data-undemp');
            const originalQuantity = quantityCell.textContent.trim();
            const originalPrice = priceCell.textContent.replace(',', '.').trim().slice(0, -1);
            
            quantityCell.innerHTML = `<input type="number" class="form-control" value="${originalQuantity}" data-original-value="${originalQuantity}">`;
            priceCell.innerHTML = `<input type="number" step="0.01" class="precio_u inavi" value="${originalPrice}" data-original-value="${originalPrice}">`;
            
            actionCell.innerHTML = `
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-success guardar-item"><i class="align-middle" data-feather="check"></i></button>
                    <button type="button" class="btn btn-danger cancelar-item"><i class="align-middle" data-feather="x"></i></button>
                </div>
            `;
            
            feather.replace();

            const quantityInput = quantityCell.querySelector('input');
            const priceInput = priceCell.querySelector('input');
            
            function calcularTotal() {
                const newQuantity = parseFloat(quantityInput.value);
                const newPrice = parseFloat(priceInput.value);
                if (!isNaN(newQuantity) && !isNaN(newPrice) && newQuantity > 0) {
                    totalCell.textContent = (newQuantity * newPrice).toFixed(2) + '$';
                } else {
                    totalCell.textContent = '0,00$';
                }
            }
            
            quantityInput.addEventListener('input', () => {
                const newQuantity = parseFloat(quantityInput.value);
                const codigoProducto = row.querySelector('.item-code').textContent.trim();
                
                if (newQuantity % undemp !== 0) {
                    const ejemplo1 = undemp * 1;
                    const ejemplo2 = undemp * 2;
                    const ejemplo3 = undemp * 3;
                    const mensaje = `<b>¡Atención!</b><br> La cantidad (${newQuantity}) para el producto "${codigoProducto}" debe ser un múltiplo de la unidad de empaque (${undemp}).<br>Ejemplos de múltiplos válidos: ${ejemplo1}, ${ejemplo2}, ${ejemplo3}, etc.`;
                    alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">${mensaje}</div>`;
                    totalCell.textContent = '0,00$';
                } else {
                    alertContainer.innerHTML = '';
                    calcularTotal();
                }
            });
            
            priceInput.addEventListener('input', calcularTotal);
        }
    }

    // Lógica para guardar la edición
    function guardarEdicion(row) {
        const idListaPedido = row.getAttribute('data-id-lista-pedido');
        const quantityInput = row.querySelector('.item-quantity input');
        const priceInput = row.querySelector('.item-precio input');
        
        const newQuantity = parseFloat(quantityInput.value);
        const newPrice = parseFloat(priceInput.value);
        const undemp = row.getAttribute('data-undemp');
        const codigoProducto = row.querySelector('.item-code').textContent.trim();

        if (newQuantity % undemp !== 0 || newQuantity <= 0 || isNaN(newQuantity) || isNaN(newPrice)) {
            const ejemplo1 = undemp * 1;
            const ejemplo2 = undemp * 2;
            const ejemplo3 = undemp * 3;
            const mensaje = `<b>¡Atención!</b><br> La cantidad (${newQuantity}) para el producto "${codigoProducto}" debe ser un múltiplo de la unidad de empaque (${undemp}).<br>Ejemplos de múltiplos válidos: ${ejemplo1}, ${ejemplo2}, ${ejemplo3}, etc.`;
            alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">${mensaje}</div>`;
            return;
        }

        const formData = new FormData();
        formData.append('modo', 'actualizar_agregar_linea');
        formData.append('id_lista_pedido', idListaPedido);
        formData.append('id_pro', row.getAttribute('data-id-pro'));
        formData.append('cantidad', newQuantity);
        formData.append('precio_u', newPrice);
        formData.append('total_pp', (newQuantity * newPrice).toFixed(2));
        formData.append('id_pedido', document.getElementById("id_pedido").value);

        fetch('sistemas/pedidos/crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            window.location.reload();
        })
        .catch(error => {
            alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">Hubo un error al actualizar el producto.</div>`;
        });
    }

    // Lógica para cancelar la edición
    function cancelarEdicion(row) {
        const originalQuantity = row.querySelector('.item-quantity input').getAttribute('data-original-value');
        const originalPrice = row.querySelector('.item-precio input').getAttribute('data-original-value');
        
        const actionCell = row.querySelector('td.text-center');
        
        row.querySelector('.item-quantity').textContent = originalQuantity;
        row.querySelector('.item-precio').textContent = parseFloat(originalPrice).toFixed(2) + '$';
        
        const total = parseFloat(originalQuantity) * parseFloat(originalPrice);
        row.querySelector('.item-total').textContent = total.toFixed(2) + '$';

        actionCell.innerHTML = `
            <div class="d-flex justify-content-center gap-2">
                <a href="#" class="btn btn-info editar-item">
                    <i class="align-middle" data-feather="edit"></i>
                </a>
                <a href="#" class="btn btn-danger borrar-item-pedido" data-id="${row.getAttribute('data-id-lista-pedido')}" data-codpro="${row.querySelector('.item-code').textContent.trim()}">
                    <i class="align-middle" data-feather="trash"></i>
                </a>
            </div>
        `;
        
        feather.replace();

        agregarItemRow.style.display = 'table-row';
        alertContainer.innerHTML = '';
        filaEditando = null;
        row.classList.remove('editing-row');
    }
});