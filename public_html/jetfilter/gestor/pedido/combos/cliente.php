<option	value="">Seleccione una opción</option>
<?php

$wsqli = "SELECT * FROM `users` WHERE rol = 2 ";
$result = $base_de_datos->query($wsqli);
if ($base_de_datos->errorCode() !== '00000') {
    $errorInfo = $base_de_datos->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $idcliente = $row['id'];
    ?>
    <option value="<?php echo $row['id'] ?>" <?php if (isset($id) && $id == $idcliente) { ?> selected<?php } ?>><?php echo $row['name'] ?></option>
<?php } ?>


