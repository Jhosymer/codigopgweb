<?php 
session_start();
include_once('./../../../config/conex.php');

if(isset($_POST['modo'])){
    $modo = $_POST['modo'];

    if($modo == "Insertar"){
        $fecha = date('Y-m-d');
        $id_pro = mysqli_real_escape_string($linki, $_POST['id_pro']);
        $cantidad = mysqli_real_escape_string($linki, $_POST['cantidad']);
        $precio = mysqli_real_escape_string($linki, $_POST['precio_u']);
        $total_linea = mysqli_real_escape_string($linki, $_POST['total_pp']);
        $id_users = $_SESSION['id'];

        $wsqli1 = "INSERT INTO pedidos (id_users, fecha,total_pedido) VALUES ('$id_users','$fecha','$total_linea')";
        $result1 = $linki->query($wsqli1);
        $idGenerado = mysqli_insert_id($linki);

        $wsqli2 = "INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad, precio_u, total ) VALUES ($idGenerado, $id_pro, $cantidad, $precio, $total_linea )";

        try {
            $result2 = $linki->query($wsqli2);  
            
            $_SESSION['idGenerado'] = $idGenerado;
        } catch (\Throwable $e) {
            $_SESSION['mensajeLista'] = "No se pudo insertar :" . $linki->error;
            $_SESSION['tm'] = "alert-danger";
        }

        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido&id=' . $idGenerado . '";';
        echo '</script>';
        exit();
    } else if ($modo == "actualizar_agregar_linea") {
        $id_pedido = mysqli_real_escape_string($linki, $_POST['id_pedido']);
        $id_pro = mysqli_real_escape_string($linki, $_POST['id_pro']);
        $cantidad = mysqli_real_escape_string($linki, $_POST['cantidad']);
        $precio = mysqli_real_escape_string($linki, $_POST['precio_u']);
        $total_linea = mysqli_real_escape_string($linki, $_POST['total_pp']);
        $id_lista_pedido = mysqli_real_escape_string($linki, $_POST['id_lista_pedido']);

        $wsqli = "UPDATE lista_pedidos SET id_producto='$id_pro', cantidad='$cantidad', precio_u='$precio', total='$total_linea' WHERE id = '$id_lista_pedido'";

        try {
            $result = $linki->query($wsqli);
            $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido'";
            $resultado_suma = mysqli_query($linki, $wqsuma);
            $row = mysqli_fetch_assoc($resultado_suma);
            $suma_precios = $row['suma_precios'];
            $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
            $linki->query($wqupdate);
           
            
            $_SESSION['id_pedido'] = $id_pedido;

        } catch (\Throwable $e) {
            $_SESSION['mensajeLista'] = "No se pudo actualizar: " . $linki->error;
            $_SESSION['tm'] = "alert-danger";
        }

        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido&id=' . $id_pedido . '#agregar-item";';
        echo '</script>';
        exit();
    } else if ($modo == "volver") {
        unset($_SESSION['id_pedido']);
        unset($_SESSION['idGenerado']);
        
        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido";';
        echo '</script>';
        exit();
    } else if ($modo == "cerrar") {
       if (isset($_POST['id'])) {
            $id_pedido = mysqli_real_escape_string($linki, $_POST['id']);
            $stmt = $linki->prepare("UPDATE `pedidos` SET `stat` = 'C' WHERE `id` = ?");
            $stmt->bind_param("i", $id_pedido);
            if ($stmt->execute()) {
           
            echo '<script type="text/javascript">';
            echo 'window.location.href = "./../../sistemas/pedidos/pedido_enviado.php?id=' . $id_pedido . '";'; 
            echo '</script>';
            unset($_SESSION['id_pedido']);
            unset($_SESSION['idGenerado']);
            exit(); 
            
        } else {
            // Error al ejecutar la consulta SQL
            $_SESSION['alerta_activa'] = true;
            $_SESSION['icono_alerta'] = 'error';
            $_SESSION['mensaje_alerta'] = "Error al cerrar el pedido: " . $stmt->error;
            $stmt->close();
            // Si hay error, redirigimos a la página principal para mostrar el error.
            header('Location: ../../index.php?pag=pedido');
            exit();
        }
        $stmt->close(); // Mover el close si no hay redirección
        
    } else {
        $_SESSION['alerta_activa'] = true;
        $_SESSION['icono_alerta'] = 'error';
        $_SESSION['mensaje_alerta'] = "No se encontró un ID de pedido válido para cerrar.";
        // Si hay error, redirigimos a la página principal para mostrar el error.
        header('Location: ../../index.php?pag=pedido');
        exit();
    }
} else if ($modo == "guardar"){
        if (isset($_POST['id'])) {
            $id_pedido = mysqli_real_escape_string($linki, $_POST['id']);
            $stmt = $linki->prepare("UPDATE `pedidos` SET `stat` = 'G' WHERE `id` = ?");
            $stmt->bind_param("i", $id_pedido);

            if ($stmt->execute()) {
                $_SESSION['alerta_activa'] = true;
                $_SESSION['icono_alerta'] = 'success';
                $_SESSION['mensaje_alerta'] = "El pedido fue guardado con éxito.";
                unset($_SESSION['id_pedido']);
                unset($_SESSION['idGenerado']);
            } else {
                $_SESSION['alerta_activa'] = true;
                $_SESSION['icono_alerta'] = 'error';
                $_SESSION['mensaje_alerta'] = "Error al guardar el pedido: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['alerta_activa'] = true;
            $_SESSION['icono_alerta'] = 'warning';
            $_SESSION['mensaje_alerta'] = "No se encontró un ID de pedido válido para guardar.";
        }
        
        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido";';
        echo '</script>';
        exit();
    } else   if($modo == "agregarmas"){

    $id_pedido = mysqli_real_escape_string($linki, $_POST['id_pedido']);
    $id_pro = mysqli_real_escape_string($linki, $_POST['id_pro']);
    $cantidad = mysqli_real_escape_string($linki, $_POST['cantidad']);
    $precio = mysqli_real_escape_string($linki, $_POST['precio_u']);
    
    // Buscar si el producto ya existe en el pedido
    $query = "SELECT * FROM lista_pedidos WHERE id_producto = '$id_pro' and id_pedido = '$id_pedido'";
    $resultado = mysqli_query($linki, $query);

    if (mysqli_num_rows($resultado) > 0) {
        // Si el producto existe, actualizamos la cantidad y el total
        $row = mysqli_fetch_assoc($resultado);
        $cantidad_existente = $row['cantidad'];
        $id_lista_pedido_existente = $row['id'];
        $nueva_cantidad = $cantidad_existente + $cantidad;
        $nuevo_total = $nueva_cantidad * $precio;

        $wsqli = "UPDATE lista_pedidos SET cantidad = '$nueva_cantidad', total = '$nuevo_total' WHERE id = '$id_lista_pedido_existente'";
        
        try {
            $linki->query($wsqli);
        } catch(\Throwable $e) {
            $_SESSION['mensajeLista'] = "No se pudo actualizar la cantidad: " . $linki->error;
            $_SESSION['tm'] = "alert-danger";
        }
    } else {
        // Si el producto no existe, lo insertamos como una nueva línea
        $total_linea = $cantidad * $precio; // Calcular el total de la nueva línea
        $wsqli = "INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad, precio_u, total) VALUES ($id_pedido, $id_pro, $cantidad, $precio, $total_linea)";
        
        try {
            $linki->query($wsqli);
            $_SESSION['id_pedido'] = $id_pedido;
        } catch(\Throwable $e) {
            $_SESSION['mensajeLista'] = "No se pudo agregar el producto: " . $linki->error;
            $_SESSION['tm'] = "alert-danger";
        }
    }

    // Siempre actualiza el total general del pedido después de insertar o actualizar
    $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido'";
    $resultado_suma = mysqli_query($linki, $wqsuma);
    $row = mysqli_fetch_assoc($resultado_suma);
    $suma_precios = $row['suma_precios'];
    $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
    $linki->query($wqupdate);
    $_SESSION['id_pedido'] = $id_pedido; // Esto asegura que la variable de sesión esté actualizada

    // Aquí está la parte clave: descomentar y corregir el redireccionamiento
    echo '<script type="text/javascript">';
echo 'window.location.href = "./../../index.php?pag=pedido&id=' . $id_pedido . '&accion=agregar-item";';
echo '</script>';
exit();




}} else { // Manejo de peticiones GET
    if(isset($_GET['id'])){
        $id = mysqli_real_escape_string($linki, $_GET['id']);
        $wsqli = "DELETE pedidos, lista_pedidos FROM pedidos JOIN lista_pedidos ON pedidos.id = lista_pedidos.id_pedido WHERE pedidos.id ='$id' and lista_pedidos.id_pedido = '$id' ";
        try {
            $linki->query($wsqli);
            $_SESSION['alerta_activa'] = true; 
            $_SESSION['icono_alerta'] = 'success';
            $_SESSION['mensaje_alerta'] = "Se eliminó con éxito el pedido.!!!";
        } catch (\Throwable $e) {
            $_SESSION['alerta_activa'] = true;
            $_SESSION['icono_alerta'] = 'error';
            $_SESSION['mensaje_alerta'] = "No se pudo eliminar: " . $linki->error;
        }
    
        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido";';
        echo '</script>';
        exit();
    } else if (isset($_GET['id_lista_pedido'])){
        $id = mysqli_real_escape_string($linki, $_GET['id_lista_pedido']);
        $wsqli1 = "SELECT * from lista_pedidos WHERE id ='$id'";
        $result1 = $linki->query($wsqli1);
        if ($linki->errno) die($linki->error);
        while ($row = $result1->fetch_array()){
            $id_pedido = $row['id_pedido'];
        }
        $wsqli = "DELETE FROM lista_pedidos WHERE id ='$id'";
        try {
            $linki->query($wsqli);
            $wqsuma = "SELECT SUM(total) AS suma_precios FROM lista_pedidos WHERE id_pedido = '$id_pedido'";
            $resultado_suma = mysqli_query($linki, $wqsuma);
            $row = mysqli_fetch_assoc($resultado_suma);
            $suma_precios = $row['suma_precios'];
            $wqupdate = "UPDATE pedidos SET total_pedido = '$suma_precios' WHERE id = '$id_pedido'";
            $linki->query($wqupdate);
            $_SESSION['alerta_activa'] = true;
            $_SESSION['icono_alerta'] = 'success';
            $_SESSION['mensaje_alerta'] = "El ítem fue eliminado con éxito del pedido.";
            $_SESSION['id_pedido'] = $id_pedido;
        } catch (\Throwable $e) {
            $_SESSION['alerta_activa'] = true;
            $_SESSION['icono_alerta'] = 'error';
            $_SESSION['mensaje_alerta'] = "No se pudo eliminar el ítem: " . $e->getMessage();
        }

        echo '<script type="text/javascript">';
        echo 'window.location.href = "./../../index.php?pag=pedido&id=' . $id_pedido . '#agregar-item";';
        echo '</script>';
        exit();
    }
}


?>