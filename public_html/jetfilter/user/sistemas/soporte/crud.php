<?php
session_start();
include_once('./../../../config/conex.php');

if (isset($_POST['modo']) && $_POST['modo'] == "Insertar") {
    
    $archivo_valido = true;
    $nombre_seguro = "";
    $upload_path = './../../../img/soporte/';

    // --- PASO 1: VALIDACIÓN PREVIA DEL ARCHIVO ---
    if (isset($_FILES['archivo']) && $_FILES['archivo']['name'] != "") {
        
        if ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['mensajeLista'] = "Error en la subida del archivo.";
            $_SESSION['tm'] = "alert-danger";
            $archivo_valido = false;
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeReal = $finfo->file($_FILES['archivo']['tmp_name']);
            
            $formatos = [
                'image/jpeg'      => 'jpg',
                'image/png'       => 'png',
                'application/pdf' => 'pdf'
            ];

            // Si el contenido NO coincide con la lista blanca (ej: un .exe)
            if (!array_key_exists($mimeReal, $formatos)) {
                $_SESSION['mensajeLista'] = "<b>BLOQUEADO POR SEGURIDAD:</b> El archivo contiene código no permitido (.exe u otro). No se registró el ticket.";
                $_SESSION['tm'] = "alert-danger";
                $archivo_valido = false; 
            } else {
                // El archivo es bueno, preparamos el nombre
                $ext = $formatos[$mimeReal];
                $nombre_seguro = bin2hex(random_bytes(10)) . "_verificado." . $ext;
            }
        }
    }

    // --- PASO 2: REGISTRO SÓLO SI EL ARCHIVO ES VÁLIDO ---
    if ($archivo_valido) {
        $fecha = date('Y-m-d');
        $id_tp_soporte = mysqli_real_escape_string($linki, $_POST['id_tp_soporte']);
        $detalle = mysqli_real_escape_string($linki, $_POST['detalle']);
        $id_users = $_SESSION['id'];
        $asunto = mysqli_real_escape_string($linki, $_POST['asunto']);
        $valor_id = !empty($_POST['codigo_filtro']) ? mysqli_real_escape_string($linki, $_POST['codigo_filtro']) : null;
        $fragmento_sql = ($valor_id === null) ? "NULL" : "'" . $valor_id . "'";

        $wsqli1 = "INSERT INTO ticket_soporte (id_user, id_tp_soporte, id_producto, detalle, stado, fecha_creado, asunto, detalle_revision, anexo) 
                   VALUES ('$id_users', '$id_tp_soporte', $fragmento_sql, '$detalle', 'A', '$fecha', '$asunto', '', '$nombre_seguro')";

        if ($linki->query($wsqli1)) {
            $idGenerado = mysqli_insert_id($linki);
            
            // Si había archivo, lo movemos ahora que el ticket ya existe
            if ($nombre_seguro != "") {
                $file_path = $upload_path . $nombre_seguro;
                if (move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path)) {
                    if (strpos($mimeReal, 'image') !== false) {
                        limpiarImagen($file_path, ($mimeReal == 'image/png' ? 'png' : 'jpg'));
                    }
                }
            }
            
            $_SESSION['mensajeLista'] = "Ticket #$idGenerado creado exitosamente.";
            $_SESSION['tm'] = "alert-success";
        } else {
            $_SESSION['mensajeLista'] = "Error al registrar: " . $linki->error;
            $_SESSION['tm'] = "alert-danger";
        }
    }
    // Si $archivo_valido es false, no entra aquí y el ticket nunca se inserta.
}

function limpiarImagen($ruta, $ext) {
    $img = ($ext == 'jpg' || $ext == 'jpeg') ? @imagecreatefromjpeg($ruta) : @imagecreatefrompng($ruta);
    if ($img) {
        if ($ext == 'png') { imagealphablending($img, false); imagesavealpha($img, true); imagepng($img, $ruta); }
        else { imagejpeg($img, $ruta, 85); }
        imagedestroy($img);
        return true;
    }
    return false;
}

if (isset($_POST['id_visto'])) {
    $id = mysqli_real_escape_string($linki, $_POST['id_visto']);
    
    // Usamos $linki (mysqli) en lugar de $base_de_datos (PDO)
    $sql_visto = "UPDATE ticket_soporte SET visto_cliente = 'S' WHERE id = '$id'";
    
    if ($linki->query($sql_visto)) {
        echo "SUCCESS";
    } else {
        echo "ERROR: " . $linki->error;
    }
    exit;
}
 echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=soporte"';
        echo '</script>';
        exit();
