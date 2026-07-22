<?php
session_start();
include_once('./../config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptcha_secret = '6LdSxn8sAAAAAJtc4b2OkFyv3W-4ryUC3FD_K_hi';
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    // Llamada a Google
    $url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $verify = @file_get_contents($url);
    $response = json_decode($verify);

    // VALIDACIÓN DEFINITIVA
    // Si la respuesta es exitosa Y el puntaje es muy bajo (bot), bloqueamos.
    // Si la respuesta falla por red/dominio, permitimos el paso para no bloquear Jet Filter.
    if ($response && $response->success && $response->score < 0.1) {
        header('location: ./login/?fallo=captcha');
        exit;
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT email, password, name, rol, id from users where email = :email";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $user['name'];
            $_SESSION['rol'] = $user['rol'];
            $_SESSION['id'] = $user['id'];

            $path = ($user['rol'] == '1') ? "./gestor/" : "./user/";
            header("location: $path", true, 301);
            exit();
        } else {
            header("location: ./login/?fallo=true");
            exit();
        }
    } catch (Exception $e) {
        header("location: ./login/?error");
        exit();
    }
}