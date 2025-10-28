<?php
session_start(); // Iniciar la sesión
include_once('../../../config/conexion.php'); // Incluir la conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $ticket_id = $_POST['ticket_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $detalle_revision = isset($_POST['detalle_revision']) ? $_POST['detalle_revision'] : '';
    
    // Manejar la subida del archivo
    $anexo_r = null;
    $archivo_subido = false; // Variable para verificar si se subió un archivo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['archivo']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION)); // Obtener la extensión del archivo

        // Definir las extensiones permitidas
        $allowedfileExtensions = array('pdf', 'jpg', 'jpeg', 'png');

        // Verificar la extensión del archivo
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Ruta donde se guardará el archivo
            $uploadFileDir = './../../img/soporte/revision/';
            $fileName = $ticket_id . '.' . $fileExtension; // Renombrar el archivo solo con el ID del ticket y la extensión
            $dest_path = $uploadFileDir . $fileName;

            // Mover el archivo a la carpeta de destino
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $anexo_r = $fileName; // Guardar el nombre del archivo para la base de datos
                $archivo_subido = true; // Indicar que se subió un archivo
            } else {
                $_SESSION['error'] = true; // Error al mover el archivo
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = true; // Extensión de archivo no permitida
            header("Location: index.php");
            exit();
        }
    }

    // Obtener la fecha actual en el formato deseado
    $fecha_actual = date('Y-m-d');

    // Preparar la consulta para obtener el estado actual y las fechas
    $sql_check = "SELECT stado, fecha_revision, fecha_cerrado, anexo_r FROM ticket_soporte WHERE id = :ticket_id";
    $stmt_check = $base_de_datos->prepare($sql_check);
    $stmt_check->bindParam(':ticket_id', $ticket_id);
    $stmt_check->execute();
    $current_data = $stmt_check->fetch(PDO::FETCH_ASSOC);
    $current_state = $current_data['stado'];
    $current_fecha_revision = $current_data['fecha_revision'];
    $current_fecha_cerrado = $current_data['fecha_cerrado'];
    $current_anexo_r = $current_data['anexo_r'];

    // Preparar la consulta para actualizar el estado y el detalle de revisión
    $sql = "UPDATE ticket_soporte SET stado = :nuevo_estado, detalle_revision = :detalle_revision";

    // Agregar condiciones para limpiar las fechas según el nuevo estado
    if ($nuevo_estado == 'A') {
        // Limpiar fecha_revision si estaba en R
        if ($current_state == 'R') {
            $sql .= ", fecha_revision = NULL";
        }
        // Limpiar fecha_cerrado si estaba en C
        if ($current_state == 'C') {
            $sql .= ", fecha_cerrado = NULL, fecha_revision = NULL";
        }
    } elseif ($nuevo_estado == 'R') {
        // Si el estado cambia de C a R, mantener fecha_cerrado
        if ($current_state == 'C') {
            $sql .= ", fecha_revision = :fecha_revision, fecha_cerrado = NULL"; // Actualizar fecha_revision
        } else {
            $sql .= ", fecha_revision = :fecha_revision"; // Actualizar fecha_revision si no estaba en R
        }
    } elseif ($nuevo_estado == 'C') {
        // Si el estado cambia a C, actualizar ambas fechas
        $sql .= ", fecha_cerrado = :fecha_cerrado, fecha_revision = :fecha_revision"; // Actualizar ambas fechas
    }

    // Solo actualizar anexo_r si se subió un nuevo archivo
    if ($archivo_subido) {
        $sql .= ", anexo_r = :anexo_r"; // Solo actualizar si hay un nuevo archivo
    } else {
        $anexo_r = $current_anexo_r; // Mantener el valor actual de anexo_r
    }

    $sql .= " WHERE id = :ticket_id";
    
    $stmt = $base_de_datos->prepare($sql);
    
    // Vincular los parámetros
    $stmt->bindParam(':nuevo_estado', $nuevo_estado);
    $stmt->bindParam(':detalle_revision', $detalle_revision);
    $stmt->bindParam(':ticket_id', $ticket_id);

    // Vincular las fechas si es necesario
    if ($nuevo_estado == 'R' && $current_state !== 'R') {
        $stmt->bindParam(':fecha_revision', $fecha_actual);
    } elseif ($nuevo_estado == 'C') {
        $stmt->bindParam(':fecha_cerrado', $fecha_actual);
        $stmt->bindParam(':fecha_revision', $fecha_actual); // También actualizar fecha_revision
    }

    // Vincular anexo_r si se subió un nuevo archivo
    if ($archivo_subido) {
        $stmt->bindParam(':anexo_r', $anexo_r);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $_SESSION['actualizado'] = true; // Actualización exitosa
    } else {
        $_SESSION['error'] = true; // Error al actualizar el ticket
        error_log(print_r($stmt->errorInfo(), true)); // Registrar el error
    }
} else {
    $_SESSION['error'] = true; // Método de solicitud no permitido
}

// Redirigir a la página index.php
header("Location: index.php");
exit();
?>
