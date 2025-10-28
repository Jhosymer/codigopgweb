<?php   
    session_start();

  include_once('./../../config/conex.php');

    if(isset($_POST['modo'])){
        

        $modo=$_POST['modo'];
        

        if($modo=="actualizar_agregar_linea"){
            $id_pedido=mysqli_real_escape_string($linki,$_POST['id_pedido']);
            $id_pro=mysqli_real_escape_string($linki,$_POST['id_pro']); // id producto
            $cantidad=mysqli_real_escape_string($linki,$_POST['cantidad']);
            $id_lista_pedido=mysqli_real_escape_string($linki,$_POST['id_lista_pedido']);
            $precio_u =mysqli_real_escape_string($linki,$_POST['precio_u']);
            $total_pp =mysqli_real_escape_string($linki,$_POST['total_pp']);
            $wsqli="UPDATE lista_pedidos SET id_producto='$id_pro', cantidad='$cantidad', precio_u='$precio_u' , total='$total_pp' where id= '$id_lista_pedido'";
            try {
                $result=$linki->query($wsqli);
                $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido' ";
                $resultado_suma = mysqli_query($linki, $wqsuma);
                $row = mysqli_fetch_assoc($resultado_suma);
                $suma_precios = $row['suma_precios'];
                $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
                $linki->query($wqupdate);
                $_SESSION['mensajeLista']="Se inserto con exito!!!";
                $_SESSION['tm']="alert-success";
                $_SESSION['id_pedido']= $id_pedido;
             

            } catch (\Throwable $e) {
                $_SESSION['mensajeLista']="No se pudo insertar :".$linki->error;
                $_SESSION['tm']="alert-danger";
            }

                
        }  
        else  if($modo=="sap_num"){
            $id=mysqli_real_escape_string($linki,$_POST['id']);
            $nsap=mysqli_real_escape_string($linki,$_POST['nsap']);
            $fecha=mysqli_real_escape_string($linki,$_POST['fecha']);
            $wsqli="UPDATE pedidos SET na_pedido='$nsap', fecha_sap ='$fecha' where id= '$id'";
            try {
                $result=$linki->query($wsqli);
                $_SESSION['mensajeLista']="Se inserto con exito!!!";
                $_SESSION['tm']="alert-success";
                $_SESSION['id']= $id;
             

            } catch (\Throwable $e) {
                $_SESSION['mensajeLista']="No se pudo insertar :".$linki->error;
                $_SESSION['tm']="alert-danger";
            }

            
        }  
        else if($modo=="agregarmas"){
            // echo "estro";
             $_SESSION['mensajeLista']="Se acrualizar con existo!!!";
             $id_pedido=mysqli_real_escape_string($linki,$_POST['id_pedido']); // id pedido
             $id_pro=mysqli_real_escape_string($linki,$_POST['id_pro']); // id producto
             $cantidad=mysqli_real_escape_string($linki,$_POST['cantidad']);
             $precio_u =mysqli_real_escape_string($linki,$_POST['precio_u']);
             $total_pp =mysqli_real_escape_string($linki,$_POST['total_pp']);
             $query = "SELECT * FROM lista_pedidos WHERE id_producto = '$id_pro' and id_pedido = '$id_pedido' ";
             $resultado = mysqli_query($linki, $query);
             if (mysqli_num_rows($resultado) > 0) {
                 $_SESSION['mensajeLista']="El producto ya existe. Verifique la lista de pedido.!!!";
                 $_SESSION['tm']="alert-danger";
                 $_SESSION['id_pedido']= $id_pedido;
             } else {
             $wsqli = "INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad,precio_u , total ) VALUES ($id_pedido, $id_pro, $cantidad, $precio_u, $total_pp)";
             try {
                 $linki->query($wsqli);
                 $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido' ";
                 $resultado_suma = mysqli_query($linki, $wqsuma);
                 $row = mysqli_fetch_assoc($resultado_suma);
                 $suma_precios = $row['suma_precios'];
                 $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
                 $linki->query($wqupdate);
                 
                 $_SESSION['mensajeLista']="Se actualizo con existo :".$linki->error;
                 $_SESSION['tm']="alert-success";
                 $_SESSION['id_pedido']= $id_pedido;
                // $_SESSION['total']= $total;
 
 
             } catch(\Throwable $e) {
                 $_SESSION['mensajeLista']="No se pudo actualizar :".$linki->error;
                 $_SESSION['tm']="alert-danger";
             }
         }
 
         }  
         
         if($modo=="sap_num"){
          header("location:index.php?id_ver=$id"); 
          
         } else {header("location:index.php?id=$id_pedido");   }
          
    }
    else{
        if(isset($_GET['id'])){
          //  echo "estro";
            $id=mysqli_real_escape_string($linki,$_GET['id']);
            $wsqli="UPDATE pedidos SET stat='Rechazado' WHERE id='$id' ";
            try {
                $linki->query($wsqli);
                $_SESSION['mensajeLista']="Se Rechazo con existo el pedido: ".$id."!!!";
                $_SESSION['tm']="alert-success";
                $id_pedido='';
            } catch (\Throwable $e) {
                $_SESSION['mensajeLista']="No se pudo eliminar :".$linki->error;
                $_SESSION['tm']="alert-danger";
            }
           
            header("location:index.php");    

        }

       /* if(isset($_GET['id_lista_pedido'])){
          
            $id=mysqli_real_escape_string($linki,$_GET['id_lista_pedido']);
            $wsqli1="SELECT * from lista_pedidos WHERE id ='$id'";
            $result1=$linki->query($wsqli1);
            if($linki->errno) die($linki->error);
            while($row=$result1->fetch_array()){
                $id_pedido=$row['id_pedido'];
            }
            $wsqli="UPDATE lista_pedidos SET cancel = '1' WHERE lista_pedidos.id ='$id'";
            try {
                $linki->query($wsqli);
                $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido'  and cancel = 0";
                $resultado_suma = mysqli_query($linki, $wqsuma);
                $row = mysqli_fetch_assoc($resultado_suma);
                $suma_precios = $row['suma_precios'];
                $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
                $linki->query($wqupdate);
                $_SESSION['mensajeLista']="Se se elimino con existo!!!";
                $_SESSION['tm']="alert-success";
                $_SESSION['id_pedido']= $id_pedido;
            } catch (\Throwable $e) {
                $_SESSION['mensajeLista']="No se pudo eliminar :".$linki->error;
                $_SESSION['tm']="alert-danger";
            }

            header("location:index.php?id=$id_pedido");    
        }
        if(isset($_GET['id_lista_pedido_act'])){
          
            $id=mysqli_real_escape_string($linki,$_GET['id_lista_pedido_act']);
            $wsqli1="SELECT * from lista_pedidos WHERE id ='$id'";
            $result1=$linki->query($wsqli1);
            if($linki->errno) die($linki->error);
            while($row=$result1->fetch_array()){
                $id_pedido=$row['id_pedido'];
            }
            $wsqli="UPDATE lista_pedidos SET cancel = '0' WHERE lista_pedidos.id ='$id'";
            try {
                $linki->query($wsqli);
                $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido'  and cancel = 0";
                $resultado_suma = mysqli_query($linki, $wqsuma);
                $row = mysqli_fetch_assoc($resultado_suma);
                $suma_precios = $row['suma_precios'];
                $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
                $linki->query($wqupdate);
                $_SESSION['mensajeLista']="Se se elimino con existo!!!";
                $_SESSION['tm']="alert-success";
                $_SESSION['id_pedido']= $id_pedido;
            } catch (\Throwable $e) {
                $_SESSION['mensajeLista']="No se pudo eliminar :".$linki->error;
                $_SESSION['tm']="alert-danger";
            }

            header("location:index.php?id=$id_pedido");    
        }
       
        */
 
    }  if(isset($_POST['idstatus'])){
        // echo "estro";
         $_SESSION['mensajeLista']="Se acrualizar con existo!!!";
         $id_pedido=mysqli_real_escape_string($linki,$_POST['id_pedido']); // id pedido
         $idstatus=mysqli_real_escape_string($linki,$_POST['idstatus']);
        $wsqli="UPDATE pedidos SET stat='$idstatus' WHERE id='$id_pedido'";
        try {
            $linki->query($wsqli);
            $_SESSION['mensaje']="Se actualizo con existo :".$linki->error;
            $_SESSION['tm']="alert-success";
        } catch(\Throwable $e) {
            $_SESSION['mensaje']="No se pudo actualizar :".$linki->error;
            $_SESSION['tm']="alert-danger";
        }
   
        header("location:index.php?id=$id_pedido");  
    }
 
    
   
    
   
   


?>

