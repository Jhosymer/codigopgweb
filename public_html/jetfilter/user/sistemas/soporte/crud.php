<?php
session_start();
include_once('./../../../config/conex.php');

if (isset($_POST['modo'])) {
    $modo = $_POST['modo'];

    if ($modo == "Insertar") {
        $fecha = date('Y-m-d');
        $id_tp_soporte = mysqli_real_escape_string($linki, $_POST['id_tp_soporte']);
        $detalle = mysqli_real_escape_string($linki, $_POST['detalle']);
        $status = "A";
        $id_users = $_SESSION['id'];
        $asunto = mysqli_real_escape_string($linki, $_POST['asunto']);

        // Insertar el ticket en la base de datos
        $wsqli1 = "INSERT INTO ticket_soporte (id_user, id_tp_soporte, detalle, stado, fecha_creado, asunto, detalle_revision) 
                    VALUES ('$id_users','$id_tp_soporte','$detalle', '$status', '$fecha', '$asunto', '')";
        $result1 = $linki->query($wsqli1);
        $idGenerado = mysqli_insert_id($linki);

        // Manejo de la carga de archivos
        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
            $file_ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
            $allowed_ext = array('pdf', 'jpg', 'jpeg', 'png');

            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $upload_path = './../../../img/soporte/';
                $new_filename = $idGenerado . '.' . $file_ext;
                $file_path = $upload_path . $new_filename;

                // Mover el archivo a la carpeta de destino
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path)) {
                    // Actualizar el ticket con el campo asunto
                    $wsqli2 = "UPDATE ticket_soporte SET anexo = '$new_filename' WHERE id = $idGenerado";
                    $linki->query($wsqli2);
                    
                    $_SESSION['mensajeLista'] = "Ticket creado exitosamente con archivo adjunto.";
                    $_SESSION['tm'] = "alert-success";
                } else {
                    $_SESSION['mensajeLista'] = "Error al subir el archivo.";
                    $_SESSION['tm'] = "alert-danger";
                }
            } else {
                $_SESSION['mensajeLista'] = "Tipo de archivo no permitido. Solo se permiten PDF, JPG y PNG.";
                $_SESSION['tm'] = "alert-danger";
            }
        } else {
            // Si no hay archivo, solo se crea el ticket sin actualizar el asunto
            $_SESSION['mensajeLista'] = "Ticket creado exitosamente sin archivo adjunto.";
            $_SESSION['tm'] = "alert-success";
        }
    }
}

header("location:./../../index.php?pag=soporte");
exit();
?>
