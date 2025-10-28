
    <div id="chatbot-icon">
        <img src="img/robot_chat/avatar.png" alt="Avatar" id="chatbot-avatar">
    </div>

    <!-- Ventana del chatbot -->
    <div id="chatbot-window" class="hidden">
        <div id="chat-header">
            <img src="img/robot_chat/avatar.png" alt="Avatar" id="chat-header-avatar">
          WEBPANA
            <button id="minimize-btn">−</button>
        </div>
        <div id="chat-body">
            <div id="messages"></div>
        </div>
        <div id="chat-footer">
            <input type="text" id="user-input" placeholder="Escribe tu mensaje...">
            <button id="send-btn">Enviar</button>
        </div>
    </div>
   <?php require 'config/conexion_robotchat.php'; ?>


    <script >
        // Referencias a los elementos
const chatbotIcon = document.getElementById("chatbot-icon");
const chatbotWindow = document.getElementById("chatbot-window");
const minimizeBtn = document.getElementById("minimize-btn");
const sendBtn = document.getElementById("send-btn");
const userInput = document.getElementById("user-input");
const messages = document.getElementById("messages");

// Contador para las respuestas "No entiendo tu mensaje."
let noUnderstandCount = 0;

// Variable para controlar si el formulario está activo
let isFormActive = false;

// Mostrar/ocultar el chatbot al hacer clic en el ícono del robot
chatbotIcon.addEventListener("click", () => {
    chatbotWindow.classList.remove('hidden'); // Mostrar ventana
    chatbotIcon.style.display = 'none'; // Ocultar botón
});

// Minimizar el chatbot al hacer clic en el botón de minimizar
minimizeBtn.addEventListener("click", () => {
    chatbotWindow.classList.add('hidden'); // Ocultar ventana
    chatbotIcon.style.display = 'flex'; // Mostrar botón
});

// Enviar mensaje al presionar "Enter" o al hacer clic en el botón de enviar
userInput.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
        if (isFormActive) {
            // Si el formulario está activo, evitar cualquier acción
            event.preventDefault();
        } else {
            sendMessage(); // Enviar mensaje del chat si el formulario no está activo
        }
    }
});

sendBtn.addEventListener("click", () => {
    if (isFormActive) {
        // Si el formulario está activo, no hacer nada
        return;
    } else {
        sendMessage(); // Enviar mensaje del chat si el formulario no está activo
    }
});

// Función para enviar el mensaje
function sendMessage() {
    const userMessage = userInput.value.trim();
    if (userMessage !== "") {
        addMessage(userMessage, "user-message");
        getBotResponse(userMessage);
        userInput.value = ""; // Limpiar el campo de entrada
    }
}

// Agregar mensaje al chat
function addMessage(text, className) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `message ${className}`;
    messageDiv.textContent = text;
    messages.appendChild(messageDiv);
    scrollToBottom(); // Desplazar hacia abajo automáticamente

    // Ocultar el botón del chatbot cuando se muestra un mensaje
    chatbotIcon.style.display = 'none';
}

// Agregar mensaje con HTML (para enlaces o formularios)
function addHTMLMessage(html, className) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `message ${className}`;
    messageDiv.innerHTML = html;
    messages.appendChild(messageDiv);
    scrollToBottom(); // Desplazar hacia abajo automáticamente

    // Ocultar el botón del chatbot cuando se muestra un mensaje
    chatbotIcon.style.display = 'none';
}


// Variables globales para manejar el estado del flujo
let waitingForMarca = false;
let waitingForCodigo = false;
let marca = ""; // Variable para almacenar la marca
let codigo = ""; // Variable para almacenar el código

// Respuesta del bot
function getBotResponse(userMessage) {
    const lowerCaseMessage = userMessage.toLowerCase();

    // Manejar el flujo de entrada de marca y código
    if (waitingForMarca) {
        marca = userMessage.trim(); // Guardar la marca ingresada por el usuario
        waitingForMarca = false; // Ya no estamos esperando la marca
        waitingForCodigo = true; // Ahora esperamos el código
        addMessage("Perfecto. Ahora, por favor indícame el código del filtro.", "bot-message");
        return;
    }

    if (waitingForCodigo) {
        codigo = userMessage.trim(); // Guardar el código ingresado por el usuario
        waitingForCodigo = false; // Ya no estamos esperando el código
        // Enviar los datos al archivo procesar_codigo.php
        sendToProcesarCodigo(marca, codigo);
        return;
    }

     let foundResponse = false; // Variable para verificar si se encontró una respuesta

    preguntas.forEach(pregunta => {
        const palabraBase = pregunta.palabra_clave;
        const terminacionesList = terminaciones[palabraBase] || [];
        const terminacionesRegex = terminacionesList.join('|');
        const regex = new RegExp(`${palabraBase}(${terminacionesRegex})?`, 'i');

        if (regex.test(lowerCaseMessage)) {
            const respuesta = respuestas[pregunta.id];
            let botResponse = respuesta.respuesta;

            if (respuesta.url_link) {
                botResponse += ` Puedes consultarlos en el siguiente enlace: 
                <a href="${respuesta.url_link}" target="_blank" class="a_link">${respuesta.link}</a>`;
            }

            addHTMLMessage(botResponse, "bot-message");
            noUnderstandCount = 0; // Reiniciar el contador si hay una respuesta válida
            foundResponse = true; // Se encontró una respuesta
            return; // Salir del bucle
        }
    });

    // Si se encontró una respuesta, no continuar con respuestas genéricas
    if (foundResponse) {
        return; // Salir de la función si se encontró una respuesta
    }
    

    if (lowerCaseMessage.includes("equivalencias") || lowerCaseMessage.includes("equivalencia")|| lowerCaseMessage.includes("equivalente")) {
        askEquivalencia();
        noUnderstandCount = 0; // Reiniciar el contador si hay una respuesta válida
        return;
    }

    // Respuesta genérica
    let botResponse = "No entiendo tu mensaje.";
    if (lowerCaseMessage.includes("hola")|| lowerCaseMessage.includes("Buen dia") ) {
        botResponse = "¿En qué puedo ayudarte?";
        noUnderstandCount = 0; // Reiniciar el contador si hay una respuesta válida
    } else if (lowerCaseMessage.includes("adiós")) {
        botResponse = "¡Adiós! Que tengas un buen día.";
        noUnderstandCount = 0; // Reiniciar el contador si hay una respuesta válida
    } else {
        noUnderstandCount++; // Incrementar el contador de respuestas no entendidas
    }

    addMessage(botResponse, "bot-message");

    // Si el bot no entiende el mensaje más de 3 veces, preguntar si desea ser contactado
    if (noUnderstandCount >= 3) {
        askForContact();
        noUnderstandCount = 0; // Reiniciar el contador después de mostrar la pregunta
    }
}


// Función para preguntar si desea equivalencias
function askEquivalencia() {
    const questionHTML = `
       
            <p>¿Me podrías indicar la marca del filtro?</p>
            <div class="flex-d"><button onclick="handleEquivalencia(true)" class="btn btn-outline-danger mb-2 ms-2 me-2">SI </button>
            <button onclick="handleEquivalencia(false)" class="btn btn-outline-danger mb-2">NO</button></div>
     
    `;
    addHTMLMessage(questionHTML, "bot-message");
}

// Manejar la respuesta del usuario a la pregunta de equivalencia
function handleEquivalencia(response) {
    if (response) {
        addMessage("Gracias. ¿Me podrías Indicar el Nombre la Marca del filtro que buscas?", "bot-message");
        waitingForMarca = true; // Activar el estado de espera para la marca
    } else {
        addMessage("No te preocupes. Por favor, indícame el código del filtro.", "bot-message");
        waitingForCodigo = true; // Activar el estado de espera para el código
        marca = ""; // Dejar la marca vacía
    }
}

// Función para enviar los datos al archivo procesar_codigo.php
function sendToProcesarCodigo(marca, codigo) {
    const data = { marca, codigo };

    fetch("procesar_codigo.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                const equivalencias = result.equivalencias
                    .map(eq => `
                        Código de marca: ${eq.codigo_marca}<br>
                        Marca: ${eq.marca}<br>
                        Código WEB: <a href="./../ficha_tecnica/index.php?codigo=${eq.codigo}&amp;clase=${eq.clase || ''}&amp;cod=1" class="a_link">${eq.codigo}</a>
                    `)
                    .join('<br><br>');

                const botResponse = `Gracias. Has seleccionado la marca "${marca || "no especificada"}" y el código del filtro "${codigo}". <br><br> <b>Equivalencias encontradas:</b><br><br>${equivalencias}`;
                addHTMLMessage(botResponse, "bot-message");
            } else {
                addMessage(result.message, "bot-message");
            }
        })
        .catch((error) => {
            console.error("Error al procesar el código:", error);
            addMessage("Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo.", "bot-message");
        });
}

// Función para preguntar si desea ser contactado
function askForContact() {
    const questionHTML = `
        
            <p>¿Desea ser contactado por alguno de nuestros operadores?</p>
        <div class="flex-d">
            <button onclick="handleContactResponse(true)" class="btn btn-outline-danger mb-2 ms-2 me-2">Sí</button>
            <button onclick="handleContactResponse(false)" class="btn btn-outline-danger mb-2">No</button>
        </div>
    `;
    addHTMLMessage(questionHTML, "bot-message");
}

// Manejar la respuesta del usuario a la pregunta de contacto
function handleContactResponse(response) {
    if (response) {
        showForm(); // Mostrar el formulario si la respuesta es "Sí"
    } else {
        addMessage("Gracias. Esperamos poder ayudarte en algo más.", "bot-message");
    }
}


// Función para desplazar automáticamente hacia abajo
function scrollToBottom() {
    messages.scrollTop = messages.scrollHeight;
}



// Función para enviar el formulario mediante AJAX
function submitForm() {
    const nombre = document.getElementById("nombre").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const mensaje = document.getElementById("mensaje").value.trim();

    if (nombre && correo && mensaje) {
        // Crear un objeto con los datos del formulario
        const formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("correo", correo);
        formData.append("mensaje", mensaje);

        // Enviar los datos al servidor usando AJAX
        fetch("chatbot.php", {
            method: "POST",
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data === "Exito") {
                    addMessage("¡Gracias! Tu información ha sido enviada correctamente. Nos pondremos en contacto contigo pronto.", "bot-message");
                } else {
                    addMessage("Lo sentimos, hubo un error al enviar tu información. Por favor, inténtalo de nuevo más tarde.", "bot-message");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                addMessage("Lo sentimos, hubo un error al enviar tu información. Por favor, inténtalo de nuevo más tarde.", "bot-message");
            });
    } else {
        addMessage("Por favor, completa todos los campos antes de enviar.", "bot-message");
    }
}

// Función para mostrar el formulario en el chat
function showForm() {
    const formHTML = `
        <form id="contact-form" action="chatbot.php" method="POST">
            <p>La información proporcionada será utilizada para que nuestros operadores puedan comunicarse con usted.</p>
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" required></textarea>
            <button type="button" id="submit-form-btn" class="btn btn-outline-danger mb-2 mt-2 ms-2 me-2" >Enviar formulario</button>
        </form>
    `;
    addHTMLMessage(formHTML, "bot-message");

    // Cambiar el estado del formulario
    isFormActive = true;

    // Cambiar el placeholder del input para indicar que el formulario está activo
    userInput.placeholder = "Escribe un mensaje o completa el formulario";

    // Agregar evento al botón "Enviar formulario"
    const submitFormBtn = document.getElementById("submit-form-btn");
    submitFormBtn.addEventListener("click", submitForm);
}

// Enviar mensaje al presionar "Enter" o al hacer clic en el botón de enviar
userInput.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
        if (isFormActive) {
            // Si el formulario está activo, permitir escribir mensajes
            event.preventDefault();
            sendMessage(); // Enviar mensaje del chat
        } else {
            sendMessage(); // Enviar mensaje del chat si el formulario no está activo
        }
    }
});

sendBtn.addEventListener("click", () => {
    if (isFormActive) {
        // Si el formulario está activo, permitir enviar mensajes
        sendMessage();
    } else {
        sendMessage(); // Enviar mensaje del chat si el formulario no está activo
    }
});

// Función para desplazar el scroll al final del chat
function scrollToBottom() {
    messages.scrollTop = messages.scrollHeight;
}

// Detectar cambios en el campo de entrada y desplazar el scroll
userInput.addEventListener("input", () => {
    scrollToBottom(); // Desplazar hacia abajo mientras se escribe
});

// Abrir el chatbot automáticamente al cargar la página
window.addEventListener("load", () => {
    chatbotWindow.classList.remove("hidden"); // Mostrar el chatbot automáticamente

    // Mostrar el mensaje de bienvenida solo una vez
    addMessage("¡Hola! ¿Cómo puedo ayudarte?", "bot-message");
});
    </script>
