function toggleOC(isEditing) {
    const view = document.getElementById('oc_view');
    const edit = document.getElementById('oc_edit');
    
    if (isEditing) {
        view.classList.add('d-none');
        edit.classList.remove('d-none');
        document.getElementById('input_oc').focus();
    } else {
        view.classList.remove('d-none');
        edit.classList.add('d-none');
    }
}

function saveOC() {
    // 1. Buscamos el ID directamente desde el atributo data-id del HTML
    const container = document.getElementById('oc_view');
    const idPedido = container.getAttribute('data-id'); 
    
    // 2. Tomamos el valor del input
    const nuevoValor = document.getElementById('input_oc').value;

    if (!idPedido) {
        console.error("No se encontró el ID del pedido en el atributo data-id");
        return;
    }

    // 3. Creamos el formulario para el envío
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'sistemas/pedidos/crud.php';

    const fields = {
        'id': idPedido,
        'oc': nuevoValor,
        'modo': 'update_oc'
    };

    for (const key in fields) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = fields[key];
        form.appendChild(input);
    }

    document.body.appendChild(form);
    form.submit();
}
