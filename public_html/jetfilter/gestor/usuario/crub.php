<?php
session_start(); // Iniciar la sesión
include_once('../../../config/conexion.php'); // Incluir la conexión a la base de datos


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $accion = $_POST['accion'] ?? '';

    try {
        if ($accion === 'crear') {
            // Lógica para crear un nuevo usuario
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $rif = htmlspecialchars($_POST['rif'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $nuevaClave = $_POST['nuevaClave'] ?? '';

            if (empty(trim($nuevaClave))) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'La clave es obligatoria para crear un nuevo usuario.';
                header('Location: index.php');
                exit();
            }

            // Validación de correo único
            $sql_check_email = "SELECT id FROM users WHERE email = ?";
            $stmt_check_email = $base_de_datos->prepare($sql_check_email);
            $stmt_check_email->execute([$email]);
            if ($stmt_check_email->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'El correo electrónico ya está en uso.';
                header('Location: index.php');
                exit();
            }

            // Validación de RIF único
            $sql_check_rif = "SELECT id FROM users WHERE rif = ?";
            $stmt_check_rif = $base_de_datos->prepare($sql_check_rif);
            $stmt_check_rif->execute([$rif]);
            if ($stmt_check_rif->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'El RIF ya está registrado.';
                header('Location: index.php');
                exit();
            }

            $username = str_replace(' ', '', $nombre);
            $rol = 2;
            $fecha_creacion = date('Y-m-d H:i:s');
            $fecha_actualizacion = $fecha_creacion;

            $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, rif, email, username, password, rol, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $base_de_datos->prepare($sql);
            $stmt->execute([$nombre, $rif, $email, $username, $claveEncriptada, $rol, $fecha_creacion, $fecha_actualizacion]);

            // Establece la variable de sesión para la alerta de "nuevo"
            $_SESSION['nuevo'] = true; 
            $_SESSION['mensaje'] = 'Usuario creado correctamente.';
            header('Location: index.php');
            exit();

        } else {
            // Lógica para actualizar un usuario existente
            $user_id = htmlspecialchars($_POST['user_id'] ?? '');
            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $rif = htmlspecialchars($_POST['rif'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $nuevaClave = $_POST['nuevaClave'] ?? '';

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

            // Validación de RIF único (excluyendo al usuario actual)
            $sql_check_rif = "SELECT id FROM users WHERE rif = ? AND id != ?";
            $stmt_check_rif = $base_de_datos->prepare($sql_check_rif);
            $stmt_check_rif->execute([$rif, $user_id]);
            if ($stmt_check_rif->fetch(PDO::FETCH_ASSOC)) {
                $_SESSION['error'] = true;
                $_SESSION['mensaje'] = 'El RIF ya está registrado.';
                header('Location: index.php');
                exit();
            }

            if (!empty(trim($nuevaClave))) {
                $claveEncriptada = password_hash($nuevaClave, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET name = ?, rif = ?, email = ?, username = ?, password = ?, updated_at = ? WHERE id = ?";
                $stmt = $base_de_datos->prepare($sql);
                $stmt->execute([$nombre, $rif, $email, $username, $claveEncriptada, $fecha_actualizacion, $user_id]);
            } else {
                $sql = "UPDATE users SET name = ?, rif = ?, email = ?, username = ?, updated_at = ? WHERE id = ?";
                $stmt = $base_de_datos->prepare($sql);
                $stmt->execute([$nombre, $rif, $email, $username, $fecha_actualizacion, $user_id]);
            }

            $_SESSION['actualizado'] = true;
            $_SESSION['mensaje'] = 'Usuario actualizado correctamente.';
            header('Location: index.php');
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = true;
        $_SESSION['mensaje'] = 'Error en la operación: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }

} else {
    header('Location: index.php');
    exit();
}