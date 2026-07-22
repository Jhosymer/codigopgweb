<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Clear-Site-Data: \"cache\", \"storage\"");

date_default_timezone_set('America/Caracas');
header('Access-Control-Allow-Origin: *');

$lockFile = './../ftp/proceso.lock';
if (file_exists($lockFile)) {
    die("Proceso en ejecución, saltando.");
}
file_put_contents($lockFile, getmypid());

$jsonFilePath = './../ftp/sap.json';
include_once('./../config/conexion.php'); 

$cambios_filtro_codificacion = 0;
$cambios_filtro_alternativo = 0;
$no_encontrados_itemcode = []; 
$nuevos_nano_insertados_codificacion = []; 

function limpiar_codigo($codigo) { return is_null($codigo) ? '' : preg_replace('/[^a-zA-Z0-9]/', '', $codigo); }
function limpiar_decimal($valor) { return is_null($valor) || $valor === '' ? 0.00 : (float)str_replace(',', '', (string)$valor); }
function limpiar_entero($valor) { return is_null($valor) || $valor === '' ? 0 : (int)floatval(str_replace([',', ' '], '', (string)$valor)); }

try {
    // Limpieza inicial (Ejecutar esto una vez)
   $base_de_datos->exec("UPDATE filtro_codificacion SET act_sap = 'N' WHERE deleted_at IS NULL");
    $base_de_datos->exec("UPDATE filtro_alternativo_sap SET act_sap = 'N'");

    $jsonContent = file_get_contents($jsonFilePath);
    $datosJson = json_decode($jsonContent, true);

    if ($datosJson === null || !is_array($datosJson)) throw new Exception("Error al leer JSON.");

    // --- CONSULTAS PREPARADAS (FUERA DEL BUCLE PARA VELOCIDAD) ---
    $stmtSelect = $base_de_datos->prepare("SELECT id, act_sap FROM filtro_codificacion WHERE codigo = :codigo AND deleted_at IS NULL");
    $stmtSelectNano = $base_de_datos->prepare("SELECT id, act_sap FROM filtro_codificacion WHERE codigo = :codigo");
    $stmtUpdateCod = $base_de_datos->prepare("UPDATE filtro_codificacion SET descripcion = :desc, stock = :stock, precio = :pre, und_empaque = :und, codigo_barra = :cb, disponible_inmediata = :di, act_sap = 'Y' WHERE id = :id");
    $stmtSelectAlt = $base_de_datos->prepare("SELECT id_codigo FROM filtro_alternativo_sap WHERE codigo_alt = :codigo");
    $stmtUpdatePrincipalAlt = $base_de_datos->prepare("UPDATE filtro_codificacion SET descripcion = :desc, precio = :pre, und_empaque = :und, codigo_barra = :cb, disponible_inmediata = :di WHERE id = :id AND act_sap != 'Y'");
    $stmtUpdateAlt = $base_de_datos->prepare("UPDATE filtro_alternativo_sap SET act_sap = 'Y', stock = :stock WHERE codigo_alt = :codigo");
    $stmtSelectAltItem = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :alt AND deleted_at IS NULL");
    $stmtInsertAlt = $base_de_datos->prepare("INSERT INTO filtro_alternativo_sap (id_codigo, codigo_alt, stock, act_sap) VALUES (:id, :cod, :stock, 'Y')");
    $stmtUpdatePrinAlt = $base_de_datos->prepare("UPDATE filtro_codificacion SET descripcion = :desc, stock = :stock, precio = :pre, und_empaque = :und, codigo_barra = :cb, disponible_inmediata = :di, act_sap = 'N' WHERE id = :id AND act_sap != 'Y'");
    $stmtSelectCat = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :cat AND deleted_at IS NULL");
    $stmtUpdateFull = $base_de_datos->prepare("UPDATE filtro_codificacion SET descripcion = :desc, stock = :stock, precio = :pre, und_empaque = :und, codigo_barra = :cb, disponible_inmediata = :di, act_sap = 'Y' WHERE id = :id");

    foreach ($datosJson as $fila) {
        $codigo = $fila['ItemCode'];
        $limpioCodigo = limpiar_codigo($codigo);
        $fue_procesado = false;
        $esNano = (stripos((string)$codigo, 'NANO') !== false);
        
        $query = $esNano ? $stmtSelectNano : $stmtSelect;
        $query->execute([':codigo' => $codigo]);

        if ($query->rowCount() > 0) {
            $resultado = $query->fetch(PDO::FETCH_ASSOC);
            if ($resultado['act_sap'] !== 'Y') {
                $stmtUpdateCod->execute([':desc'=>$fila['ItemName'], ':stock'=>limpiar_entero($fila['Disponible']??0), ':pre'=>limpiar_decimal($fila['Price']??0), ':und'=>limpiar_entero($fila['SalPackUn']??0), ':cb'=>$fila['CodeBars'], ':di'=>limpiar_entero($fila['disponible_inmediata']??0), ':id'=>$resultado['id']]);
                $cambios_filtro_codificacion++;
            }
            $fue_procesado = true;
        } else {
            $stmtSelectAlt->execute([':codigo' => $codigo]);
            if ($stmtSelectAlt->rowCount() > 0) {
                $idCodigo = $stmtSelectAlt->fetch(PDO::FETCH_ASSOC)['id_codigo'];
                $stmtUpdatePrincipalAlt->execute([':desc'=>$fila['ItemName'], ':pre'=>limpiar_decimal($fila['Price']??0), ':und'=>limpiar_entero($fila['SalPackUn']??0), ':cb'=>$fila['CodeBars'], ':di'=>limpiar_entero($fila['disponible_inmediata']??0), ':id'=>$idCodigo]);
                $stmtUpdateAlt->execute([':stock'=>limpiar_entero($fila['Disponible']??0), ':codigo'=>$codigo]);
                $cambios_filtro_codificacion++; $cambios_filtro_alternativo++; $fue_procesado = true;
            } else {
                $stmtSelectAltItem->execute([':alt' => $fila['AltItem']]);
                if ($stmtSelectAltItem->rowCount() > 0) {
                    $idPrincipal = $stmtSelectAltItem->fetch(PDO::FETCH_ASSOC)['id'];
                    $stmtInsertAlt->execute([':id'=>$idPrincipal, ':cod'=>$codigo, ':stock'=>limpiar_entero($fila['Disponible']??0)]);
                    $stmtUpdatePrinAlt->execute([':desc'=>$fila['ItemName'], ':stock'=>limpiar_entero($fila['Disponible']??0), ':pre'=>limpiar_decimal($fila['Price']??0), ':und'=>limpiar_entero($fila['SalPackUn']??0), ':cb'=>$fila['CodeBars'], ':di'=>limpiar_entero($fila['disponible_inmediata']??0), ':id'=>$idPrincipal]);
                    $cambios_filtro_codificacion++; $cambios_filtro_alternativo++; $fue_procesado = true;
                } else if (!empty($fila['codigo_catalogo'])) {
                    $stmtSelectCat->execute([':cat' => $fila['codigo_catalogo']]);
                    if ($stmtSelectCat->rowCount() > 0) {
                        $idPrincipal = $stmtSelectCat->fetch(PDO::FETCH_ASSOC)['id'];
                        $stmtUpdateFull->execute([':desc'=>$fila['ItemName'], ':stock'=>limpiar_entero($fila['Disponible']??0), ':pre'=>limpiar_decimal($fila['Price']??0), ':und'=>limpiar_entero($fila['SalPackUn']??0), ':cb'=>$fila['CodeBars'], ':di'=>limpiar_entero($fila['disponible_inmediata']??0), ':id'=>$idPrincipal]);
                        $stmtInsertAlt->execute([':id'=>$idPrincipal, ':cod'=>$codigo, ':stock'=>limpiar_entero($fila['Disponible']??0)]);
                        $cambios_filtro_codificacion++; $cambios_filtro_alternativo++; $fue_procesado = true;
                    }
                }
            }
        }
        if (!$fue_procesado) $no_encontrados_itemcode[] = array_merge($fila, ['CodigoLimpio' => $limpioCodigo]);
    }

    // --- SECCION NANO  ---
    $clase_valor = 'aireindustrial';
    $fecha_actualiza = date('d-m-Y'); 
    $sincronizado = date('Ymd');
    $deleted_at = date('Y-m-d H:i:s');

    for ($i = count($no_encontrados_itemcode) - 1; $i >= 0; $i--) {
        $f = $no_encontrados_itemcode[$i];
        if (stripos((string)$f['ItemCode'], 'NANO') !== false || stripos((string)$f['AltItem'], 'NANO') !== false) {
            $check = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :c AND deleted_at IS NULL");
            $check->execute([':c' => $f['ItemCode']]);
            if ($check->rowCount() == 0) {
                $ins = $base_de_datos->prepare("INSERT INTO filtro_codificacion (codigo, codigo_buscar, descripcion, stock, precio, und_empaque, codigo_barra, disponible_inmediata, act_sap, clase, fecha_actualiza, sincronizado, deleted_at) VALUES (:codigo, :codigo_buscar, :descripcion, :stock, :precio, :undEmpaque, :codigoBarra, :disponibleInmediata, 'Y', :clase, :fecha_actualiza, :sincronizado, :deleted_at)");
                $ins->execute([
                    ':codigo' => $f['ItemCode'], ':codigo_buscar' => $f['CodigoLimpio'], ':descripcion' => $f['ItemName'],
                    ':stock' => $f['Disponible'], ':precio' => $f['Price'], ':undEmpaque' => $f['SalPackUn'],
                    ':codigoBarra' => $f['CodeBars'], ':disponibleInmediata' => $f['disponible_inmediata'],
                    ':clase' => $clase_valor, ':fecha_actualiza' => $fecha_actualiza, 
                    ':sincronizado' => $sincronizado, ':deleted_at' => $deleted_at
                ]);
                $cambios_filtro_codificacion++;
                $nuevos_nano_insertados_codificacion[] = $f['ItemCode'];
                unset($no_encontrados_itemcode[$i]);
            }
        }
    }
    $no_encontrados_itemcode = array_values($no_encontrados_itemcode);

unlink($lockFile);
} catch (Exception $e) {
    if (file_exists($lockFile)) unlink($lockFile);
    echo "Error: " . $e->getMessage();
}
?>


<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Resumen de Sincronización</h5>
        </div>
        <div class="card-body">
            <p class="mb-1">Registros en <strong>filtro_codificacion</strong>: <span class="badge bg-info text-dark"><?php echo $cambios_filtro_codificacion; ?></span></p>
            <p class="mb-1">Registros en <strong>filtro_alternativo_sap</strong> (con Stock actualizado): <span class="badge bg-info text-dark"><?php echo $cambios_filtro_alternativo; ?></span></p>
            
            <hr>
            <h6>Inserciones NANO: <?php echo count($nuevos_nano_insertados_codificacion); ?></h6>
            <?php if(!empty($nuevos_nano_insertados_codificacion)) echo '<div class="small text-muted">' . implode(", ", $nuevos_nano_insertados_codificacion) . '</div>'; ?>

            <hr>
            <h6 class="text-danger">No Procesados (No eran NANO): <?php echo count($no_encontrados_itemcode); ?></h6>
            <?php 
            if(!empty($no_encontrados_itemcode)) {
                $cols = array_column($no_encontrados_itemcode, 'ItemCode');
                echo '<div class="alert alert-light border small">' . implode(", </br>", $cols ) . '</div>';
            }
            ?>
        </div><br>
        <div class="card-footer text-muted small">Fin de la sincronización.</div>
    </div>
</div>