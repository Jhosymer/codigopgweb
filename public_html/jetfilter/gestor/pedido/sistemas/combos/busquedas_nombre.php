<?php
include('./../../../../config/conex_combos.php');
$campo = $_POST["nombre_p"];


$sql = "SELECT id, codigo, descripcion, precio, stock FROM filtro_codificacion WHERE (codigo LIKE ? and deleted_at is null) OR (descripcion LIKE ? and deleted_at is null) ORDER BY id ASC LIMIT 0, 10";
$query = $pdo->prepare($sql);
$query->execute(['%' . $campo . '%', '%' . $campo . '%']);

$html = "";

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	$html .= "<li class='list-group-item' onclick=\"mostrar('" . $row["id"] . "','".$row["codigo"] . "','".$row["descripcion"] . "','".$row["precio"] . "','".$row["stock"] . "')\">"  . $row["descripcion"] . " -- " . $row["codigo"] ."</li>";
    

//onclick="ver_estudios
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
