<?php
include('./../../../config/conex_combos.php');

$campo = $_POST["nombre_p"] ?? $_POST["nombre_pr"] ?? "";
$html = "";
$primer_match_encontrado = false;

if (!empty($campo)) {
    // Mismo criterio de act_sap pero filtrando por DESCRIPCION
    $sql = "SELECT t1.id, t1.codigo, t1.descripcion, t1.precio, t1.und_empaque,
                   t1.stock AS stock1, t1.act_sap AS act1,
                   t1.disponible_inmediata,
                   SUM(COALESCE(t2.stock, 0)) AS stock2, 
                   MAX(COALESCE(t2.act_sap, 'N')) AS act2
            FROM filtro_codificacion AS t1
            LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
            WHERE (t1.descripcion LIKE ? AND t1.act_sap = 'Y' AND t1.precio != 0) 
               OR (t1.descripcion LIKE ? AND t2.act_sap = 'Y' AND t1.precio != 0) 
            GROUP BY t1.id
            ORDER BY IF(t1.descripcion LIKE ?, 0, 1), t1.descripcion ASC LIMIT 0, 10";

    $query = $pdo->prepare($sql);
    $query->execute(['%' . $campo . '%', '%' . $campo . '%', $campo . '%']);

    $contador = 0;
    $html_principales = "";
    $html_scroll = "";

    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $clase_activa = '';
        if (!$primer_match_encontrado) {
            if (stripos($row['descripcion'], $campo) === 0) {
                $clase_activa = 'active'; 
                $primer_match_encontrado = true;
            }
        }

        // --- LÓGICA DE STOCK REAL ---
        $stockTotal = 0;
        if ($row['act1'] == 'Y' && $row['act2'] == 'Y') { 
            $stockTotal = (int)$row["stock1"] + (int)$row["stock2"]; 
        } else if ($row['act1'] == 'Y' && $row['act2'] == 'N') { 
            $stockTotal = (int)$row["stock1"]; 
        } else if ($row['act2'] == 'Y' && $row['act1'] == 'N') { 
            $stockTotal = (int)$row["stock2"]; 
        }

        $meta = (int)$row['disponible_inmediata'];
        if ($meta <= 0) {
            $clasePunto = "dot-info"; $mensaje = "Stock no conf.";
        } else {
            if ($stockTotal <= ($meta * 0.10)) { $clasePunto = "dot-danger"; $mensaje = "Consulta con Ventas"; }
            elseif ($stockTotal <= ($meta * 0.30)) { $clasePunto = "dot-warning"; $mensaje = "Poca Disponibilidad"; }
            else { $clasePunto = "dot-success"; $mensaje = "Disponibilidad Inmediata"; }
        }

        $desc_js = addslashes($row['descripcion']);
        $item_html = "<li class='list-group-item $clase_activa' style='cursor:pointer;' 
                          onclick=\"mostrar('{$row['id']}','{$row['codigo']}','$desc_js','{$row['und_empaque']}','{$row['precio']}','$clasePunto','$mensaje')\">
                        <div class='d-flex align-items-center'>
                            <span class='dot-status $clasePunto me-2'></span>
                            <span>{$row['descripcion']} -- <b>{$row['codigo']}</b></span>
                        </div>
                      </li>";

        if ($contador < 10) { $html_principales .= $item_html; } 
        else { $html_scroll .= $item_html; }
        $contador++;
    }

    $html = $html_principales;
    if ($contador > 10) {
        $html .= "<div style='max-height: 200px; overflow-y: auto; border-top: 2px solid #eee; background: #f8f9fa;'>";
        $html .= $html_scroll;
        $html .= "</div>";
    }
}
echo json_encode($html, JSON_UNESCAPED_UNICODE);