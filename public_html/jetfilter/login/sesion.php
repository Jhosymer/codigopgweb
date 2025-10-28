<?php
    session_start() or die('Error iniciando gestor de variables de sesión');  
    if( !isset($_SESSION['email']) ){
      header("location: ./index.php");
    }
    if(isset($_SESSION['tiempo']) ) {

        $inactivo = 3600;

        $vida_session = time() - $_SESSION['tiempo'];

            if($vida_session > $inactivo)
            {
                session_unset();
                session_destroy();              
                header("Location: ./login/");

                exit();
            }

    }
    $_SESSION['tiempo'] = time();

if( $_SESSION['rol'] == '1' ){


    header("location: ../gestor/", true, 301);

}



else if( $_SESSION['rol'] == '2' ){
   // header("location: ./vendedores/sesion_vendedor.php", true, 301);
   header("location: ../user/", true, 301);
}
?>
 