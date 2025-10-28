<?php

session_start();
include_once("../../../config/conexion.php"); // Asegúrate de incluir tu conexión a la base de datos

if (isset($_POST['seleccion'])) {
    $idsSeleccionados = $_POST['seleccion']; // Array de id_pedido seleccionados
    $id_user = $_POST['id_user'];
    $fecha_actual = date('Y-m-d');
    echo "ID de Usuario: " . $id_user . "<br>";

    // Inicializar la variable para el total
    $totalGeneral = 0;

    // Insertar un registro en la tabla predespacho
    $insertPredespacho = "INSERT INTO predespacho (fecha, total) VALUES (?, ?)";
    $stmtPredespacho = $base_de_datos->prepare($insertPredespacho);
    $stmtPredespacho->execute([$fecha_actual, 0]); // Inicialmente el total es 0

    // Obtener el ID generado automáticamente
    $id_predespacho = $base_de_datos->lastInsertId();

    foreach ($idsSeleccionados as $id_pedido) {
        $wsqli = "SELECT *, total FROM lista_pedidos WHERE cancel = 0 AND id_pedido = ?";
        $stmt = $base_de_datos->prepare($wsqli);
        $stmt->execute([$id_pedido]);

        // Obtener y mostrar los detalles de cada pedido
        while ($pedido = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID de Pedido: " . $id_pedido . "<br>";
            echo "ID de Línea: " . $pedido['id'] . "<br>";
            echo "Total del Pedido: " . $pedido['total'] . "<br>";

            // Sumar el total al total general
            $totalGeneral += $pedido['total'];

            // Insertar en la tabla lista_predespacho
            $insertListaPredespacho = "INSERT INTO lista_predespacho (id_predespacho, id_lista_predespacho) VALUES (?, ?)";
            $stmtListaPredespacho = $base_de_datos->prepare($insertListaPredespacho);
            $stmtListaPredespacho->execute([$id_predespacho, $pedido['id']]);
        }

        // Si no hay pedidos, mostrar un mensaje
        if ($stmt->rowCount() === 0) {
            echo "No se encontraron datos para el ID de Pedido: " . $id_pedido . "<br>";
        }
    }

    // Actualizar el total en la tabla predespacho
    $updatePredespacho = "UPDATE predespacho SET total = ? WHERE id = ?";
    $stmtUpdatePredespacho = $base_de_datos->prepare($updatePredespacho);
    $stmtUpdatePredespacho->execute([$totalGeneral, $id_predespacho]);

    // Actualizar la tabla pedidos
    $placeholders = implode(',', array_fill(0, count($idsSeleccionados), '?'));
    $updatePedidos = "UPDATE pedidos SET na_pedido = ?, stat = 'Procesado' WHERE id IN ($placeholders)";
    $stmtUpdatePedidos = $base_de_datos->prepare($updatePedidos);
    $stmtUpdatePedidos->execute(array_merge([$id_predespacho], $idsSeleccionados));

    // Mostrar el total general
    echo "Total General de Todos los Pedidos: " . $totalGeneral . "<br>";
}

?>
