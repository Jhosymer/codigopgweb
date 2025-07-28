<?php 
    try{
        $url_base_datos = './conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: gestion.php?errorBase=true");
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
    }
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $sql = "SELECT email, password, name from users where email= :email";
        if($inicio = $base_de_datos->prepare($sql)){
            $inicio->bindParam(':email', $email, PDO::PARAM_STR);
            $inicio->execute(); 
            $mData=$inicio->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password,$mData['password'])){
                session_start() or die('Error iniciando gestor de variables de sesión');
                $_SESSION['email']= $email;
                $_SESSION['name'] = $mData['name'];
                header("location: sesion.php", true, 301);
            }
            else{
                header("location: gestion.php?fallo=true");
            }
        }
        else {
            $outPut=array('status'=>false, 'msg'=>'Error prepare');
        }
    }
    catch (PDOException $ex) {
        $outPut=array('status'=>false, 'msg'=>$ex->getMessage());
        return $outPut;
    }
    catch (Exception $ex) {
        header("location: gestion.php?error");
    }
?>