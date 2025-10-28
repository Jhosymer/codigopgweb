<option value="">Seleccione una opción</option>
<?php

$wsqli = "SELECT u.id as 'id', u.name as 'name' FROM pedidos p INNER JOIN users u ON p.id_users = u.id WHERE p.stat = 'En Proceso'";
$result = $base_de_datos->query($wsqli);
if ($base_de_datos->errorCode() !== '00000') {
    $errorInfo = $base_de_datos->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}

$clientesAgregados = []; // Array para almacenar los IDs de clientes ya agregados

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $idcliente = $row['id'];

    // Verificar si el cliente ya ha sido agregado
    if (!in_array($idcliente, $clientesAgregados)) {
        $clientesAgregados[] = $idcliente; // Agregar el ID al array
        ?>
        <option value="<?php echo $row['id'] ?>" <?php if (isset($id) && $id == $idcliente) { ?> selected<?php } ?>><?php echo $row['name'] ?></option>
        <?php
    }
}
?>
