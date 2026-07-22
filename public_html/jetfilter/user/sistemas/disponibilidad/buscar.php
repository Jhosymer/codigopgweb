<?php
include('./../../../config/conex_combos.php');

$campo = isset($_POST["termino"]) ? $_POST["termino"] : "";
$tipoBusqueda = isset($_POST["tipo"]) ? $_POST["tipo"] : "codigo"; 

$campoLimpio = preg_replace('/[^a-zA-Z0-9]/', '', $campo);

$html = "";

if (!empty($campo)) {
    // Usamos SUM para el stock y MAX para asegurar que traemos el valor de act_sap si existe
    $selectFields = "t1.id, t1.codigo, t1.descripcion, t1.precio, t1.clase, 
                     t1.stock AS stock1, 
                     SUM(COALESCE(t2.stock, 0)) AS stock2, 
                     t1.act_sap AS act1, 
                     MAX(COALESCE(t2.act_sap, 'N')) AS act2,
                     t1.disponible_inmediata, t1.und_empaque";

    if ($tipoBusqueda == 'codigo') {
        $sql = "SELECT $selectFields
                FROM filtro_codificacion AS t1
                LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
                WHERE (REPLACE(REPLACE(t1.codigo, '-', ''), ' ', '') LIKE ? AND t1.act_sap = 'Y' AND t1.precio != 0) 
                OR (REPLACE(REPLACE(t1.codigo, '-', ''), ' ', '') LIKE ? AND t2.act_sap = 'Y' AND t1.precio != 0) 
                GROUP BY t1.id, t1.codigo, t1.descripcion, t1.precio, t1.clase, t1.stock, t1.act_sap, t1.disponible_inmediata, t1.und_empaque
                ORDER BY t1.id ASC";
        $query = $pdo->prepare($sql);
        $query->execute(['%' . $campoLimpio . '%', '%' . $campoLimpio . '%']);
    } else {
        $sql = "SELECT $selectFields
                FROM filtro_codificacion AS t1
                LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo
                WHERE (REPLACE(REPLACE(t1.descripcion, '-', ''), ' ', '') LIKE ? AND t1.act_sap = 'Y' AND t1.precio != 0)
                OR (REPLACE(REPLACE(t1.descripcion, '-', ''), ' ', '') LIKE ? AND t2.act_sap = 'Y' AND t1.precio != 0)
                GROUP BY t1.id, t1.codigo, t1.descripcion, t1.precio, t1.clase, t1.stock, t1.act_sap, t1.disponible_inmediata, t1.und_empaque
                ORDER BY IF(t1.descripcion = ?, 0, 1), IF(t1.descripcion LIKE ?, 0, 1), t1.id ASC";
        $query = $pdo->prepare($sql);
        $query->execute(['%' . $campoLimpio . '%', '%' . $campoLimpio . '%', $campo, $campo . '%']);
    }

    $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($resultados) {
        foreach ($resultados as $row) {
            $codigoOriginal = $row['codigo'];
            $clase  = $row['clase'];
            
            // Lógica de suma: Si ambos están activos en SAP, sumamos
            if ($row['act1'] == 'Y' && $row['act2'] == 'Y') {
                $stock = (int)$row["stock1"] + (int)$row["stock2"];
            } else if ($row['act1'] == 'Y' && $row['act2'] == 'N') {
                $stock = (int)$row["stock1"];
            } else if ($row['act1'] == 'N' && $row['act2'] == 'Y') {
                $stock = (int)$row["stock2"];
            } else {
                $stock = 0;
            }

            $codigoParaLink = $codigoOriginal;
            $meta = (int)$row['disponible_inmediata'];

            if (stripos($codigoOriginal, 'NANO') !== false) {
                $codigoParaLink = str_ireplace("NANO", "", $codigoOriginal);
                $codigoParaLink = str_replace(" ", "", $codigoParaLink);
            }
            
            if ($meta <= 0) {
                $clasePunto = "dot-info"; 
                $mensaje = "stock no configurado";
                $claseTooltip = "tooltip-info";
            } else {
                $diezPorciento = $meta * 0.10;
                $treintaPorciento = $meta * 0.30;

                if ($stock <= $diezPorciento) {
                    $clasePunto = "dot-danger"; 
                    $mensaje = "Consulta con Ventas";
                    $claseTooltip = "tooltip-danger"; 
                } elseif ($stock <= $treintaPorciento) {
                    $clasePunto = "dot-warning"; 
                    $mensaje = "Poca Disponibilidad";
                    $claseTooltip = "tooltip-warning";
                } else {
                    $clasePunto = "dot-success"; 
                    $mensaje = "Disponibilidad Inmediata";
                    $claseTooltip = "tooltip-success";
                }
            }

            $html .= "<tr>
                        <td>
                            <a href='./../../../catalogo/ficha_tecnica/index.php?codigo=$codigoParaLink&clase=$clase&cod=1' 
                               target='_blank' 
                               class='a_web text-decoration-none fw-bold'>
                              $codigoOriginal
                            </a>
                        </td>
                        <td>{$row['descripcion']}</td>
                        <td>" . number_format($row['precio'], 2) . " $</td>
                        <td>
                           <span class='dot-status $clasePunto' 
                      style='cursor:pointer;' 
                      data-bs-toggle='tooltip' 
                      data-bs-custom-class='$claseTooltip' 
                      title='$mensaje'></span>
                        </td>
                        <td>{$row['und_empaque']} </td>
                      </tr>";
        }
    } else {
        $html = "<tr><td colspan='5' class='text-center'>Sin coincidencias para '$campo'</td></tr>";
    }
}
echo $html;
?>