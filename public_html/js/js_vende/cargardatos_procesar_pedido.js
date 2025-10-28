function cargarDatos() {
    var idSeleccionado = document.getElementById("idcliente").value;
    document.getElementById('miDiv').style.display = 'block';
    // Verifica si se ha seleccionado un cliente
    if (idSeleccionado) {
        // Realiza una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "tabla_procesar.php?id=" + idSeleccionado, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Actualiza la tabla con los datos recibidos
                document.getElementById("tablaResultados").innerHTML = xhr.responseText;            }
        };
        xhr.send();
    } else {
        // Si no se selecciona, limpia la tabla
        document.getElementById("tablaResultados").innerHTML = "";
    }
}