<?php
include('./../../../config/conex_combos.php');

$campo = isset($_POST["termino"]) ? $_POST["termino"] : "";
// Mantenemos la limpieza para evitar caracteres especiales en la búsqueda
$campoLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $campo);
$html = "";

if (!empty($campoLimpio)) {
    // 1. Simplificamos a una sola tabla
    // 2. Filtramos por deleted_at IS NULL
    $sql = "SELECT id, codigo 
            FROM filtro_codificacion 
            WHERE deleted_at IS NULL 
            AND REPLACE(REPLACE(codigo, '-', ''), ' ', '') LIKE ?
            ORDER BY codigo ASC";

    $query = $pdo->prepare($sql);
    $param = '%' . $campoLimpio . '%';
    $query->execute([$param]);
    $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        foreach ($resultados as $row) {
            // Se mantiene la estructura compatible con Bootstrap 5.3
            $html .= "<button type='button' class='list-group-item list-group-item-action item-resultado' 
                      data-id='{$row['id']}' 
                      data-codigo='{$row['codigo']}'>
                      <strong>{$row['codigo']}</strong>
                      </button>";
        }
    } else {
        $html = "<button type='button' class='list-group-item list-group-item-action disabled'>Sin resultados para: " . htmlspecialchars($campo) . "</button>";
    }
}
echo $html;
?>