<?php
include('./../../../config/conex_combos.php');

$campo = $_POST["codigo_pr"];
$html = "";
$primer_match_encontrado = false;

$sql = "SELECT t1.id, t1.codigo, t1.descripcion, t1.precio, t1.stock, t1.und_empaque
        FROM filtro_codificacion AS t1
        LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
        WHERE (t1.codigo LIKE ? AND t1.act_sap = 'Y' AND t1.precio != 0) 
        OR (t1.codigo LIKE ? AND t2.act_sap = 'Y' AND t1.precio != 0) 
        GROUP BY t1.id
        ORDER BY t1.id ASC 
        LIMIT 0, 10";
$query = $pdo->prepare($sql);
$query->execute(['%' . $campo . '%', '%' . $campo . '%']);

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    
    $clase_activa = '';
    // Lógica mejorada para resaltar el elemento más relevante
    if (!$primer_match_encontrado) {
        if (strcasecmp($row['descripcion'], $campo) === 0 || substr(strtolower($row['descripcion']), 0, strlen($campo)) === strtolower($campo)) {
            $clase_activa = 'active'; 
            $primer_match_encontrado = true;
        }
    }
    
    $disponibilidad = ($row["stock"] <= 20) ? "Poca Disponibilidad" : "Disponible";

    $html .= "<li class='list-group-item " . $clase_activa . "' onclick=\"mostrar('" . $row["id"] . "','" . $row["codigo"] . "','" . $row["descripcion"] . "','" . $row["und_empaque"] . "','" . $row["precio"] . "','" . $disponibilidad . "')\">" . $row["codigo"] . " -- " . $row["descripcion"] . "</li>";
}

echo json_encode($html, JSON_UNESCAPED_UNICODE);
?>