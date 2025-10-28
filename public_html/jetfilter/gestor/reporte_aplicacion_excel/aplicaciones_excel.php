<?php
  header("Content-Type: application/xls");
  header("Content-Type: application/vnd.ms-excel charset=iso-8859-1");
  header("Content-Disposition: attachment; filename= Aplicaciones_" . $_GET['tipo'] . "_".  date('Y:m:d:m:s').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
    


    include_once('../../../config/conexion.php');
    $tipo = $_GET['tipo'];
    $marcaset =  $_GET['marcaset'];
    try {
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
    } catch (PDOException $pe) {
        die("Could not connect to the database $dbname :" . $pe->getMessage());
    }
     
      if( isset( $_GET['tipo'] ) ){
      
          if ($tipo == 'todo' ){
             
           

            include('completos/prueba.php');
            include('completos/aplicaciones_comercial_execelcompleto.php');
           include('completos/aplicaciones_Fuera_de_Carretera_execelcompleto.php');
           include('completos/aplicaciones_liviano_execelcompleto.php');
           
           
              
  
      }     else   if ($tipo == 'liviano' ){
        include('completos/aplicaciones_liviano_excelcompleto.php');

      }

      else   if ($tipo == 'Comercial' ){
        include('completos/aplicaciones_comercial_excelcompleto.php');

      }

      else   if ($tipo == 'Agricola' ){
        include('completos/aplicaciones_agricola_excelcompleto.php');

      }

      else   if ($tipo == 'Carretera' ){
        include('completos/aplicaciones_Fuera_de_Carretera_excelcompleto.php');

      }
        
      }

  
      
      
      
      
  ?>
  
   
  
  