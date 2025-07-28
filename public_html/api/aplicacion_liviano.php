<?php
    require_once 'clases/respuestas.class.php';
    require_once 'clases/aplicacion_liviano.class.php';
     header('Access-Control-Allow-Origin: *');
    
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

  
    $_respuestas=new respuestas;
    $_aplicacion_liviano=new aplicacion_liviano;
    
    //echo "hola desde catagorias<br>";

    if($_SERVER['REQUEST_METHOD']=="GET"){

        if(empty($_GET)){
          $listaDeaplicacion_liviano=$_aplicacion_liviano->listaraplicacion_liviano();
          header("Content-Type: application/json");
          echo json_encode($listaDeaplicacion_liviano);
          http_response_code(200);
        }
        
    
        


    }else if($_SERVER['REQUEST_METHOD']=="POST"){

    }else if($_SERVER['REQUEST_METHOD']=="PUT"){

    }else if($_SERVER['REQUEST_METHOD']=="DELETE"){

    }else{
        header('Content-Type: application/json');
        $datosArray = $_respuestas->error_405();
        echo json_encode($datosArray);
    }


?>