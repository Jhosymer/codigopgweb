<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// El resto de tu código...
session_start() or die('Error iniciando gestor de variables de sesión');
include_once('./../config/conexion.php');


//Recoge el email y la contraseña introducidos en el formulario de inicio
$email = $_POST['email'];
$password = $_POST['password'];

try {
    /*
        Consulta que traiga el nombre de usuario, la contraseña, el rol y el nombre del usuario que tenga
        el correo ingresado, en caso de que no exista te redirige afuera
    */
    $sql = "SELECT email, password, name, rol, id from users where email = :email";
    if($inicio = $base_de_datos->prepare($sql)){
        $inicio->bindParam(':email', $email, PDO::PARAM_STR);
        $inicio->execute();
        $mData = $inicio->fetch(PDO::FETCH_ASSOC);

        // Se verifica si se encontró un usuario
        if ($mData && password_verify($password, $mData['password'])) {
            // Si la contraseña es correcta, se asignan las variables de sesión
            $_SESSION['email'] = $email; // Para conocer el correo
            $_SESSION['name'] = $mData['name']; //El nombre
            $_SESSION['rol'] = $mData['rol']; //El rol
            $_SESSION['id'] = $mData['id'];

            //Dependiendo el rol te redirigira a una pagina diferente
            if( $mData['rol'] == '1' ){
                header("location: ./gestor/", true, 301);
                exit();
            } else if( $mData['rol'] == '2' ){
                header("location: ./user/", true, 301);
                exit();
            }
        } else {
            // En caso de no ser correcta la contraseña o no encontrar el usuario, te redirigirá con una alerta de error
            header("location: ./login/?fallo=true");
            exit();
        }
    } else {
        $outPut=array('status' => false, 'msg'=>'Error prepare');
    }
}
catch (PDOException $ex) {
    // Si hay un error de base de datos
    header("location: ./login/?db_error"); // Redirige a una página de error de DB
    exit();
}
catch (Exception $ex) {
    header("location: ./login/?error");
    exit();
}

?>