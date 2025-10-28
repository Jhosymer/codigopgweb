<?php 
     $loc = "../../../";
     $locj = "./../../";
     $title = "Pedidos";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
   include_once('./../alertas/alerta_actualizado.php');
    if(isset($_SESSION['mensaje'])){ 
    $mensa=  $_SESSION['mensaje'];
    }else {
       $mensa= 'no actualizado';
   }
    alerta_error($mensa);
    alerta_actualizado($mensa);
    alerta_eliminado("El Pedido se elimino correctamente");
?>







<?php 

    $id="";
    $codpro="";
    $nombre="";
    $cantidad="";
    $id_pro="";
    $id_lista_pedido="";
    $id_pedido="";
    $colorbn="btn btn-primary";
    $modo="Insertar";
    $nb="Registrar";
    $precio_u="";
    $total_pp="";
    $fecha= date('Y-m-d');
    $cliente = '';

    /*if(isset($_GET['id'])) {
        $id=$_GET['id'];
        $nb="Agregar Mas";
        $colorbn="btn btn-success";
        $modo="agregarmas";

        include('form_edi_arriba.php');
        include('modal.php');

        include('form_edi_medio.php');

        include('form_edi_abajo.php');
        include('tabla_lista_pedido.php');

    }else if(isset($_GET['id_lista_pedido'])) {
       
        $id_lista_pedido = $_GET['id_lista_pedido'];
         
        $wsqli1 = " SELECT id_pedido FROM lista_pedidos WHERE id= '$id_lista_pedido'";
        $result1 = $base_de_datos->query($wsqli1);
        if ($base_de_datos->errorCode() !== '00000') {
            $errorInfo = $base_de_datos->errorInfo();
            die("Query failed: " . $errorInfo[2]);
        }
     
        while ($row1 = $result1->fetch(PDO::FETCH_ASSOC)) {
            $id = $row1['id_pedido'];
         
            include('form_edi_arriba.php');
            
        }

        include('modal.php');

        $wsqli2="SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, pedidos.total_pedido as total_pedido,lista_pedidos.precio_u as precio_u, lista_pedidos.total as total_linea,  lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo
        as codpro , filtro_codificacion.descripcion as nombre, filtro_codificacion.stock as stock, lista_pedidos.cantidad as cantida
         from lista_pedidos inner join pedidos on lista_pedidos.id_pedido = pedidos.id inner join filtro_codificacion on lista_pedidos.id_producto= filtro_codificacion.id
         where lista_pedidos.id ='$id_lista_pedido' ";
          $result2 = $base_de_datos->query($wsqli2);
         if ($base_de_datos->errorCode() !== '00000') {
            $errorInfo = $base_de_datos->errorInfo();
            die("Query failed: " . $errorInfo[2]);
        }
     
        while ($row2 = $result2->fetch(PDO::FETCH_ASSOC)) {
            $codpro=$row2['codpro'];
            $nombre=$row2['nombre'];
            $cantidad=$row2['cantida'];
            $id_pro=$row2['id_pro'];
            $id_lista_pedido=$row2['id_lista_pedido'];
            $id_pedido=$row2['id_pedido'];
            $precio_u = $row2['precio_u'];
            $total_pp = $row2['total_linea'];
            $stock= $row2['stock'];
           
            $nb="Actualizar Linea";
            $modo="actualizar_agregar_linea";
            $colorbn="btn btn-info text-white";
            include('form_edi_medio.php');
            
        }
        include('form_edi_abajo.php');
        include('tabla_lista_pedido.php');

    } else  if(isset($_GET['id_ver'])) {
        $id_pedido = $_GET['id_ver'];
        include('ver_pedido.php');

    } else if (isset($_POST['procesar'])) {

        include('procesar.php');
    }else {
         
        include('tabla_pedido.php');
    }*/
   
if(isset($_GET['id_ver'])) {
        $id_pedido = $_GET['id_ver'];
        include('ver_pedido.php');
 } else 
            {
         
        include('tabla_pedido.php');
    }



?>

<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>

<script src="<?php echo $loc; ?>js/js_vende/menutables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/calculoporprecios.js"></script>


<?php   
    
    include("../index/footer.php");

    ?>
    

    