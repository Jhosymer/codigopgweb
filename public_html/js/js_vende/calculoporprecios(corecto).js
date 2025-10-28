
//calculo del total de las filas cantidad por precio
const cantidadEl   = document.querySelector("#cantidad")
const precio_uEl   = document.querySelector("#precio_u")
const total_ppEl   =  document.getElementById("total_pp")
const stockEl   =  document.getElementById("stock")
const undempEl   =  document.getElementById("undemp")
const botonDeEnvio = document.getElementById("miBotonDeEnvio");

const inputCP = document.getElementById("codigo_p")
const inputNP = document.getElementById("nombre_p")
const lista_NP = document.getElementById("lista_np")
const lista_CP = document.getElementById("lista_cp")
const inputidP = document.getElementById("id_pro")



botonDeEnvio.disabled = true;

// Seleccionamos el contenedor donde se mostrará el mensaje de alerta
const alertContainer = document.getElementById("alert-container"); 
cantidadEl.addEventListener("input", validarYCalcular);
precio_uEl.addEventListener("input", validarYCalcular);

function validarYCalcular() {
    // Limpiamos cualquier alerta anterior
    alertContainer.innerHTML = "";
    botonDeEnvio.disabled = true; // Inhabilitamos por defecto

    const cantidad = parseFloat(cantidadEl.value);
    const undEmpaque = parseFloat(undempEl.value);
    const precioUnitario = parseFloat(precio_uEl.value);

    // Verificamos que todos los campos necesarios tengan un valor numérico válido
    if (isNaN(cantidad) || isNaN(undEmpaque) || isNaN(precioUnitario) || cantidad <= 0) {
        total_ppEl.value = "";
        return; // Salimos de la función si algún valor no es válido
    }

    // Validamos que la cantidad sea un múltiplo de la unidad de empaque
    if (cantidad % undEmpaque !== 0) {
        const mensaje = `¡Atención! La cantidad (${cantidad}) debe ser un múltiplo de la unidad de empaque (${undEmpaque}).`;
        const alertHTML = `<div class="alert alert-danger" role="alert">${mensaje}</div>`;
        alertContainer.innerHTML = alertHTML;
        total_ppEl.value = "";
        return;
    }

    // Realizamos el cálculo y habilitamos el botón
    const resultado = cantidad * precioUnitario;
    total_ppEl.value = resultado.toFixed(2);
    botonDeEnvio.disabled = false;
}

// Función para configurar la lógica de búsqueda para un campo específico
function configurarBuscador(inputEl, listaEl, otraListaEl, url, paramName) {
    let items = [];
    let currentIndex = -1;

    // Evento 'keyup' para la búsqueda y navegación con flechas
    inputEl.addEventListener("keyup", (event) => {
        // Si la tecla es Flecha Arriba o Abajo, manejamos la navegación
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            manejarNavegacionConFlechas(event, items, currentIndex);
            return;
        }

        // Si la tecla es Enter o Tab, salimos de la función
        if (event.key === 'Enter' || event.key === 'Tab') {
            return;
        }

        // Lógica para mostrar la lista de resultados al escribir
        if (inputEl.value.length > 0) {
            // Ocultar la otra lista antes de mostrar la actual
            otraListaEl.style.display = 'none';
            
            let formData = new FormData();
            formData.append(paramName, inputEl.value);

            fetch(url, {
                method: "POST",
                body: formData,
                mode: "cors"
            }).then(response => response.json())
              .then(data => {
                  listaEl.style.display = 'block';
                  listaEl.innerHTML = data;
                  
                  // Una vez que se obtienen los datos, actualizamos el estado
                  items = listaEl.querySelectorAll('li');
                  currentIndex = -1; 
                  
                  // LÓGICA CORREGIDA: Si el PHP ya pintó uno, lo respetamos, sino, pintamos el primero
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

    // Evento 'keydown' para Enter y Tab (crucial para que funcione)
    inputEl.addEventListener("keydown", (event) => {
        if (event.key === 'Enter' || event.key === 'Tab') {
            event.preventDefault();
            
            const elementoActivo = listaEl.querySelector('li.active');
            if (elementoActivo) {
                elementoActivo.click();
            }
        }
    });

    // Función para manejar la navegación con flechas
    function manejarNavegacionConFlechas(event) {
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

// Inicializar la funcionalidad para ambos campos de búsqueda
// Cada uno sabe cuál es su lista y cuál es la otra que debe cerrar
configurarBuscador(inputCP, lista_CP, lista_NP, 'sistemas/combos/busqueda_codigo.php', 'codigo_pr');
configurarBuscador(inputNP, lista_NP, lista_CP, 'sistemas/combos/busquedas_nombre.php', 'nombre_p');


// Tu función para manejar la selección del elemento y cerrar la lista
function mostrar(id, codigo, descripcion, und_empaque, precio, disponibilidad) {
    // Aquí va la lógica para llenar los inputs con los datos del producto
    // Asegúrate de que las variables inputidP, inputCP, inputNP, etc. estén declaradas
    // globalmente o accesibles desde aquí.
    inputidP.value = id;
    inputCP.value = codigo;
    inputNP.value = descripcion;
    stockEl.value = disponibilidad ;
    precio_uEl.value = precio;
    undempEl.value = und_empaque;

    // Oculta ambas listas después de una selección
    document.getElementById("lista_cp").style.display = 'none';
    document.getElementById("lista_np").style.display = 'none';

    
}