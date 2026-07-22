<?php 

session_start();
// Incluye tu conexión donde se define $linki
include_once('./../../../config/conex.php');

$update_messages = [];
$error_messages = [];
$update_sql_parts = [];

// 1. Verificación de conexión (Reutilizado del index, pero aquí más simple)
// Usamos !($linki instanceof mysqli) para ser compatibles con tu index.php
if (!isset($linki) || !($linki instanceof mysqli) || $linki->connect_error) {
    $_SESSION['swal_type'] = 'error';
    $_SESSION['swal_title'] = 'Error Crítico';
    $_SESSION['swal_text'] = 'El sistema no tiene una conexión válida a la base de datos.';
    header('Location: index.php?pag=perfil'); 
    exit();
}

// 2. Verificación del modo y del ID
if(isset($_POST['modo']) && $_POST['modo'] === 'actualizar' && isset($_POST['user_id']) && !empty($_POST['user_id'])){
    
    // CORRECCIÓN: Usar el método orientado a objetos: $linki->real_escape_string
    $id = $linki->real_escape_string($_POST['user_id']);

    // 3. Procesar Email
    if(isset($_POST['email'])) {
        // CORRECCIÓN: Usar el método orientado a objetos: $linki->real_escape_string
        $new_email = $linki->real_escape_string($_POST['email']);
        
        $update_sql_parts[] = "`email` = '{$new_email}'";
        $update_messages[] = "Email actualizado";
    } else {
        $error_messages[] = "El campo Email no fue recibido.";
    }


    // 4. PROCESAR CLAVE (Condicionalmente y con Hashing)
    if (isset($_POST['nuevaClave']) && isset($_POST['confirmarClave'])) {
        
        $nuevaClave = $_POST['nuevaClave'];
        $confirmarClave = $_POST['confirmarClave'];
        
        if (!empty($nuevaClave) && !empty($confirmarClave)) {
            
            if ($nuevaClave === $confirmarClave) {
                // HASHING SEGURO
                $hashed_password = password_hash($nuevaClave, PASSWORD_DEFAULT);
                
                // Sanear el hash antes de la inserción
                // CORRECCIÓN: Usar el método orientado a objetos
                $safe_hashed_password = $linki->real_escape_string($hashed_password);
                
                $update_sql_parts[] = "`password` = '{$safe_hashed_password}'";
                $update_messages[] = "Clave actualizada";
                
            } else {
                $error_messages[] = "Las nuevas claves no coinciden, la clave NO fue actualizada.";
            }
        } else {
           $update_messages[] = "La clave NO fue modificada. </br><span class='fw-bold'><small>El campo de clave se dejó vacío</small></span>.";
        }
    }


    // 5. Ejecución de la consulta SQL
    
    if (!empty($update_sql_parts)) {
        
        $set_clause = implode(', ', $update_sql_parts);
        $wsqli_update_user = "UPDATE `users` SET {$set_clause} WHERE id = '{$id}'";

        // Ejecutar la consulta usando el método de POO
        if ($linki->query($wsqli_update_user)) {
            
            // Configurar SweetAlert2 de éxito/advertencia
            if (empty($error_messages)) {
                $_SESSION['swal_type'] = 'success';
                $_SESSION['swal_title'] = '✅ Actualización Exitosa';
                $_SESSION['swal_text'] = implode('<br>', $update_messages);
            } else {
                $_SESSION['swal_type'] = 'warning';
                $_SESSION['swal_title'] = '⚠️ Actualización Parcial';
                $_SESSION['swal_text'] = "Se actualizó: " . implode(', ', $update_messages) . "<br>Errores: " . implode(', ', $error_messages);
            }
            
        } else {
            // Error en la ejecución de la consulta
            $_SESSION['swal_type'] = 'error';
            $_SESSION['swal_title'] = 'Error de Base de Datos';
            $_SESSION['swal_text'] = 'Falló la ejecución de la consulta: ' . $linki->error;
        }
        
    } else {
        // No hay nada que actualizar
        $_SESSION['swal_type'] = 'info';
        $_SESSION['swal_title'] = 'Sin Cambios';
        $_SESSION['swal_text'] = 'No se recibió ningún campo válido (Email o Clave) para actualizar.';
    }

} else {
    // Solicitud inválida
    $_SESSION['swal_type'] = 'error';
    $_SESSION['swal_title'] = 'Error de Solicitud';
    $_SESSION['swal_text'] = 'Solicitud de actualización inválida o incompleta.';
}

// 6. REDIRECCIÓN FINAL
header('Location: ./../../index.php?pag=perfil');
exit();