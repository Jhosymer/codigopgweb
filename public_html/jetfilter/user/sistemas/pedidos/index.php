<h1 class="titulo text-center">Pedidos</h1>

<?php
$id = "";
$codpro = "";
$nombre = "";
$cantidad = "";
$precio_u = "";
$total_pp = "";
$id_pro = "";
$id_lista_pedido = "";
$id_pedido = "";
$stock = "";
$undemp = "";
$colorbn = "btn btn-primary";
$modo = "Insertar";
$nb = "Registrar";
$fecha = date('Y-m-d');
$total_pedido = '';
$id_users = $_SESSION['id'];
$nbcerrar = "Guardar";
$colorbtvolvecerre = "btn btn-primary";


if (isset($_GET['id_lista_pedido'])) {
    $id = $_GET['id_lista_pedido'];
    $nb = "Actualizar Linea.";
    $modo = "actualizar_agregar_linea";
    $colorbn = "btn btn-info";

    $wsqli = "SELECT lista_pedidos.id_pedido as id_pedido, lista_pedidos.id as id_lista_pedido, pedidos.total_pedido as total_pedido, lista_pedidos.precio_u as precio_u, lista_pedidos.total as total_linea, lista_pedidos.id_producto as id_pro, filtro_codificacion.codigo as codpro, filtro_codificacion.descripcion as nombre, filtro_codificacion.und_empaque as und_empaque, filtro_codificacion.stock as stock, lista_pedidos.cantidad as cantida 
              from lista_pedidos 
              inner join pedidos on lista_pedidos.id_pedido = pedidos.id 
              inner join filtro_codificacion on lista_pedidos.id_producto = filtro_codificacion.id 
              where lista_pedidos.id ='$id'";

    $result = $linki->query($wsqli);
    if ($linki->errno) die($linki->error);

    while ($row = $result->fetch_array()) {
        $codpro = $row['codpro'];
        $nombre = $row['nombre'];
        $cantidad = $row['cantida'];
        $id_pro = $row['id_pro'];
        $id_lista_pedido = $row['id_lista_pedido'];
        $id_pedido = $row['id_pedido'];
        $precio_u = $row['precio_u'];
        $total_pp = $row['total_linea'];
        $stock = $row['stock'];
        $undemp = $row['und_empaque'];
    }
    include('formulariopedido.php');
} else if (isset($_GET['id']) or isset($_SESSION['id_pedido'])) {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $id_pedido = $_GET['id'];
    } elseif (isset($_SESSION['id_pedido'])) {
        $id = $_SESSION['id_pedido'];
        $id_pedido = $_SESSION['id_pedido'];
    }
    
    $nb = "Agregar Mas";
    $modo = "agregarmas";
    $colorbn = "btn btn-success";
    include('formulariopedido.php');
} elseif (isset($_POST['nuevo']) or isset($_SESSION['idGenerado'])) {
    if (isset($_SESSION['idGenerado'])) {
        $id = $_SESSION['idGenerado'];
        $nb = "Agregar Mas";
        $modo = "agregarmas";
        $colorbn = "btn btn-success";
    }
    include('formulariopedido.php');
} elseif (isset($_GET['id_ver'])) {
    $nbcerrar = "Volver";
    $colorbtvolvecerre = "btn btn-primary";
    $id_pedido = $_GET['id_ver'];
    include('ver_pedido.php');
} else {
?>
    <div class="py-3">
        <?php
        if (isset($_SESSION['mensajeLista'])) {
            echo "<div class='text-center alert " . $_SESSION['tm'] . "' role='alert'>";
            echo $_SESSION['mensajeLista'];
            echo "</div>";
            unset($_SESSION['mensajeLista']);
        } ?>
    </div>

    <form action="index.php?pag=pedido" method="post" id='nuevo'>
        <button type="submit" class='btn btn-primary mb-2' name='nuevo'>Nuevo</button>
    </form>
<?php
    include('tabla_pedidos.php');
}
?>