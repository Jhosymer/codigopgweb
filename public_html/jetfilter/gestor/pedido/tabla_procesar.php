<?php
include_once("../../../config/conexion.php"); // Asegúrate de incluir tu conexión a la base de datos

if (isset($_GET['id'])) {
    $idSeleccionado = intval($_GET['id']); // Asegúrate de sanitizar la entrada
    echo "<input type='hidden' name='id_user' id ='id_user'  value='{$idSeleccionado}'>";
    $wsqli = "SELECT pedidos.id as id_pedido, pedidos.total_pedido as 'totalpedido', users.name as 'name', pedidos.id_users as id_users, pedidos.na_pedido as na_pedido, pedidos.fecha as fecha, pedidos.stat as stat FROM pedidos INNER JOIN users ON users.id = pedidos.id_users WHERE stat = 'En Proceso' AND pedidos.id_users = $idSeleccionado";

    $result = $base_de_datos->query($wsqli);
    if ($base_de_datos->errorCode() !== '00000') {
        $errorInfo = $base_de_datos->errorInfo();
        die("Query failed: " . $errorInfo[1]);
    }

    $contador = 1;
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $total = number_format($row['totalpedido'], 2, ',', '.') . '$';
    echo "<tr>
            <th scope='row'>{$contador}</th>
            <th scope='row'>
                <input type='checkbox' name='seleccion[]' value='{$row['id_pedido']}' />
                
            </th>
            <td>{$row['name']}</td>
            <td>{$row['id_pedido']}</td>
            <td>{$row['fecha']}</td>
            <td>" . ($row['na_pedido'] == '' ? 'Por Asignar' : $row['na_pedido']) . "</td>
            <td>{$row['stat']}</td>
            <td>{$total}</td>
            <td class='text-center'>
                <button class='btn btn-primary' type='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasRight-{$row['id_pedido']}' aria-controls='offcanvasRight'><i class='bx bxs-search' style='color:#ffffff'></i></button>
            </td>
          </tr>";

    $contador++;
}

}

       
?>

