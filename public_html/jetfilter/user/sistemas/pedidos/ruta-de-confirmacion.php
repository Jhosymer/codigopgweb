<?php
session_start();
include("./../../../config/conex.php");

header('Content-Type: application/json');

// Verificar si la sesión de usuario y los datos del pedido son válidos
if (!isset($_SESSION['id']) || !isset($_POST['itemsValidos'])) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos de usuario o del pedido.']);
    exit();
}

$id_user = $_SESSION['id'];
$items_validos = json_decode($_POST['itemsValidos'], true);

if (empty($items_validos)) {
    echo json_encode(['status' => 'error', 'message' => 'No hay ítems válidos para registrar.']);
    exit();
}

// Iniciar una transacción para asegurar la integridad de los datos
$linki->begin_transaction();

try {
    // 1. Calcular el total del pedido
    $total_pedido = 0;
    foreach ($items_validos as $item) {
        $total_pedido += $item['total'];
    }

    // 2. Insertar en la tabla 'pedidos'
    $stmt_pedido = $linki->prepare("INSERT INTO pedidos (id_users, fecha, total_pedido) VALUES (?, CURDATE(), ?)");
    $stmt_pedido->bind_param("id", $id_user, $total_pedido);
    if (!$stmt_pedido->execute()) {
        throw new Exception("Error al insertar en la tabla pedidos: " . $stmt_pedido->error);
    }
    $id_pedido = $stmt_pedido->insert_id;
    $stmt_pedido->close();

    // 3. Insertar en la tabla 'lista_pedidos' para cada ítem
    $stmt_lista = $linki->prepare("INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad, precio_u, total) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($items_validos as $item) {
        $id_producto = $item['id'];
        $cantidad = $item['cantidad_solicitada'];
        $precio_u = $item['precio'];
        $total_item = $item['total'];

        $stmt_lista->bind_param("iiidd", $id_pedido, $id_producto, $cantidad, $precio_u, $total_item);
        if (!$stmt_lista->execute()) {
            throw new Exception("Error al insertar en la tabla lista_pedidos: " . $stmt_lista->error);
        }
    }
    $stmt_lista->close();

    // Si todo va bien, confirmar la transacción
    $linki->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Pedido registrado exitosamente.',
        'pedido_id' => $id_pedido,
        'fecha' => date('Y-m-d'),
        'total_pedido' => number_format($total_pedido, 2)
    ]);

} catch (Exception $e) {
    // Si hay un error, revertir la transacción
    $linki->rollback();
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$linki->close();
?>