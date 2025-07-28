<?php
    require_once 'clases/respuestas.class.php';
    require_once 'clases/espec_panel.class.php';
     header('Access-Control-Allow-Origin: *');
    
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

  
    $_respuestas=new respuestas;
    $_espec_panel=new espec_panel;
    
    //echo "hola desde catagorias<br>";

    if($_SERVER['REQUEST_METHOD']=="GET"){

        if(empty($_GET)){
          $listaDeespec_panel=$_espec_panel->listarespec_panel();
          header("Content-Type: application/json");
          echo json_encode($listaDeespec_panel);
          http_response_code(200);
        }
        if(isset($_GET['id'])){
         $id=$_GET['id'];
         $listaDeespec_panel=$_espec_panel->listarespec_panelId($id);
         header("Content-Type: application/json");
         echo json_encode($listaDeespec_panel);
         http_response_code(200);
       }
       if(isset($_GET['codigo'])){
        $codigo=$_GET['codigo'];
         $listaDeespec_panel=$_espec_panel->listarespec_panelCodigo($codigo);
         header("Content-Type: application/json");
         echo json_encode($listaDeespec_panel);
         http_response_code(200);
       }
       if(isset($_GET['total'])){
        $listaDeespec_panel=$_espec_panel->listarespec_panelTotal();
        header("Content-Type: application/json");
        echo json_encode($listaDeespec_panel);
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