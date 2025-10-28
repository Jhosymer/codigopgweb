<?php
// Habilitar la visualización de errores para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Iniciar la sesión
include_once('../../../config/conexion.php'); // Incluir la conexión a la base de datos


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accion = $_POST['accion'] ?? '';

    try {
        // Iniciar una transacción para asegurar que todas las operaciones se completen o ninguna
        $base_de_datos->beginTransaction();

        if ($accion === 'crear') {
            // Lógica para crear un nuevo usuario
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $nuevaClave = $_POST['nuevaClave'] ?? '';
            $permisos_seleccionados = $_POST['permisos'] ?? [];

            if (empty(trim($nuevaClave))) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'La clave es obligatoria para crear un nuevo usuario.';
                // Redireccionar en caso de error de validación
                header('Location: index.php');
                exit();
            }
            
            $username = str_replace(' ', '', $nombre);
            $rol = 1; // Rol fijo a 1
            $fecha_creacion = date('Y-m-d H:i:s');
            $fecha_actualizacion = $fecha_creacion;
            $rif = ''; // Campo RIF para evitar el error. Se le da un valor vacío.


            // Validación de correo único
            $sql_check_email = "SELECT id FROM users WHERE email = ?";
            $stmt_check_email = $base_de_datos->prepare($sql_check_email);
            $stmt_check_email->execute([$email]);
            if ($stmt_check_email->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'El correo electrónico ya está en uso.';
                // Redireccionar en caso de error de validación
                header('Location: index.php');
                exit();
            }

            // La validación de unicidad para el nombre de usuario ha sido eliminada.
            

            $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);
            // Nuevo: Se añade el campo `rif` a la consulta de inserción y se le pasa como parámetro.
            $sql_insert_user = "INSERT INTO users (name, email, username, password, rol, rif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert_user = $base_de_datos->prepare($sql_insert_user);
            $stmt_insert_user->execute([$nombre, $email, $username, $claveEncriptada, $rol, $rif, $fecha_creacion, $fecha_actualizacion]);

            // Obtener el ID del usuario recién creado
            $new_user_id = $base_de_datos->lastInsertId();

            // Insertar los permisos seleccionados para el nuevo usuario
            if (!empty($permisos_seleccionados)) {
                $sql_insert_permiso = "INSERT INTO users_permiso_admin (id_users, id_permiso_admin) VALUES (?, ?)";
                $stmt_insert_permiso = $base_de_datos->prepare($sql_insert_permiso);
                foreach ($permisos_seleccionados as $permiso_id) {
                    $stmt_insert_permiso->execute([$new_user_id, $permiso_id]);
                }
            }

            // Si todo fue bien, se confirma la transacción
            $base_de_datos->commit();

            $_SESSION['nuevo'] = true; 
            $_SESSION['mensaje'] = 'Usuario creado correctamente.';
            header('Location: index.php');
            exit();

        } else {
            // Lógica para actualizar un usuario existente, incluyendo permisos
            $user_id = htmlspecialchars($_POST['user_id'] ?? '');
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $nuevaClave = $_POST['nuevaClave'] ?? '';
            $permisos_seleccionados = $_POST['permisos'] ?? [];

            $username = str_replace(' ', '', $nombre);
            $fecha_actualizacion = date('Y-m-d H:i:s');

            // Validación de correo único (excluyendo al usuario actual)
            $sql_check_email = "SELECT id FROM users WHERE email = ? AND id != ?";
            $stmt_check_email = $base_de_datos->prepare($sql_check_email);
            $stmt_check_email->execute([$email, $user_id]);
            if ($stmt_check_email->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'El correo electrónico ya está en uso por otro usuario.';
                header('Location: index.php');
                exit();
            }

            // La validación de unicidad para el nombre de usuario ha sido eliminada.

            // --- Lógica para actualizar los datos del usuario ---
            if (!empty(trim($nuevaClave))) {
                $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);
                $sql_update_user = "UPDATE users SET name = ?, email = ?, username = ?, password = ?, updated_at = ? WHERE id = ?";
                $stmt_update_user = $base_de_datos->prepare($sql_update_user);
                $stmt_update_user->execute([$nombre, $email, $username, $claveEncriptada, $fecha_actualizacion, $user_id]);
            } else {
                $sql_update_user = "UPDATE users SET name = ?, email = ?, username = ?, updated_at = ? WHERE id = ?";
                $stmt_update_user = $base_de_datos->prepare($sql_update_user);
                $stmt_update_user->execute([$nombre, $email, $username, $fecha_actualizacion, $user_id]);
            }

            // --- Lógica para actualizar los permisos ---
            
            // 1. Eliminar todos los permisos actuales del usuario
            $sql_delete_permisos = "DELETE FROM users_permiso_admin WHERE id_users = ?";
            $stmt_delete_permisos = $base_de_datos->prepare($sql_delete_permisos);
            $stmt_delete_permisos->execute([$user_id]);

            // 2. Insertar los nuevos permisos seleccionados
            if (!empty($permisos_seleccionados)) {
                $sql_insert_permiso = "INSERT INTO users_permiso_admin (id_users, id_permiso_admin) VALUES (?, ?)";
                $stmt_insert_permiso = $base_de_datos->prepare($sql_insert_permiso);
                foreach ($permisos_seleccionados as $permiso_id) {
                    $stmt_insert_permiso->execute([$user_id, $permiso_id]);
                }
            }

            // Si todo fue bien, se confirma la transacción
            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            $_SESSION['mensaje'] = 'Usuario actualizado correctamente.';
            header('Location: index.php');
            exit();
        }

    } catch (PDOException $e) {
        // Si algo falla, se revierte la transacción
        $base_de_datos->rollBack();
        $_SESSION['error'] = true;
        $_SESSION['mensaje'] = 'Error en la operación: ' . $e->getMessage();
        
        // Muestra un mensaje de error detallado en la página
        echo "<h1>Error de Base de Datos</h1>";
        echo "<p>Mensaje de Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Código de Error: " . htmlspecialchars($e->getCode()) . "</p>";
        echo "<p>Línea: " . htmlspecialchars($e->getLine()) . "</p>";
        exit();

    } catch (Exception $e) {
        $base_de_datos->rollBack();
        $_SESSION['error'] = true;
        $_SESSION['mensaje'] = 'Error en la operación: ' . $e->getMessage();
        echo "<h1>Error de Aplicación</h1>";
        echo "<p>Mensaje de Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>Línea: " . htmlspecialchars($e->getLine()) . "</p>";
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
