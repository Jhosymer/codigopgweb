<?php
// Incluye el archivo de conexión a la base de datos
include("./../../../config/conex.php");

// Carga la librería PhpSpreadsheet (generado por Composer)
require './../../../../vendor/excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

// --- Función para limpiar el código, ahora maneja acentos ---
function limpiarCodigo($codigo) {
    // 1. Convertir a minúsculas
    $codigo = strtolower($codigo);
    // 2. Convertir caracteres acentuados a sin acento
    $codigo = iconv('UTF-8', 'ASCII//TRANSLIT', $codigo);
    // 3. Remover caracteres no alfanuméricos (incluidos los que queden después de iconv)
    $codigo = preg_replace('/[^a-z0-9]/', '', $codigo);
    // 4. Convertir a mayúsculas para la comparación
    return strtoupper($codigo);
}

// Verifica si se ha subido un archivo
if (!isset($_FILES['archivoPedido'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se ha subido ningún archivo.']);
    exit();
}

$archivo = $_FILES['archivoPedido'];
$extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
$rutaTemporal = $archivo['tmp_name'];

$respuesta = [
    'status' => 'success',
    'errores' => [],
    'exitos' => [],
    'totalPedido' => 0
];
 
if ($extension !== 'xlsx' && $extension !== 'xls') {
    $respuesta['status'] = 'error';
    $respuesta['errores'][] = ['mensaje' => 'Formato de archivo no válido. Solo se permiten archivos .xlsx o .xls.'];
    echo json_encode($respuesta);
    exit();
}

try {
    $spreadsheet = IOFactory::load($rutaTemporal);
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();

    // 1. Identificar las columnas de "Código" y "Cantidad"
    $codigoCol = null;
    $cantidadCol = null;

    $headers = $worksheet->rangeToArray('A1:Z1', NULL, TRUE, FALSE)[0];

    foreach ($headers as $index => $header) {
        // Normaliza el encabezado para hacer la comparación
        $headerLimpio = limpiarCodigo(str_replace('Ud.Emp', 'Cantidad', $header ?? ''));
        
        if ($headerLimpio === 'CODIGO') {
            $codigoCol = $index;
        } elseif ($headerLimpio === 'CANTIDAD') {
            $cantidadCol = $index;
        }
    }

    // 2. Verificar que ambas columnas fueron encontradas
    if ($codigoCol === null || $cantidadCol === null) {
        $respuesta['status'] = 'error';
        $respuesta['errores'][] = ['mensaje' => 'El archivo no contiene los encabezados obligatorios "Código" y "Cantidad".'];
        echo json_encode($respuesta);
        exit();
    }

    // 3. Prepara una sola consulta
    $stmt = $linki->prepare("SELECT t1.id, t1.codigo, t1.und_empaque, t1.precio
        FROM filtro_codificacion AS t1 LEFT JOIN
        filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo
        WHERE (t1.codigo = ? OR t1.codigo_buscar = ? OR t2.codigo_alt = ?)
        AND (t1.act_sap = 'Y' OR t2.act_sap = 'Y')
        AND t1.precio != 0");
    
    // 4. Leer los datos desde la segunda fila
    for ($row = 2; $row <= $highestRow; $row++) {
        $rowData = $worksheet->rangeToArray('A' . $row . ':Z' . $row, NULL, TRUE, FALSE)[0];

        $codigoExcel = trim($rowData[$codigoCol] ?? '');
        $cantidadExcel = (int)($rowData[$cantidadCol] ?? 0);
        
        // Si la celda de código está vacía o la cantidad no es válida, se salta la fila
        if (empty($codigoExcel) || $cantidadExcel <= 0) {
            continue;
        }

        $codigoLimpio = limpiarCodigo($codigoExcel);

        // Binding de los tres parámetros a la consulta
        $stmt->bind_param("sss", $codigoExcel, $codigoLimpio, $codigoExcel);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $rowDB = $result->fetch_assoc();

        if ($result->num_rows === 0) {
            $respuesta['errores'][] = ['mensaje' => "El código '$codigoExcel' no fue encontrado."];
            continue;
        }

        $undEmpaque = (int)$rowDB['und_empaque'];
        $precio = (float)$rowDB['precio'];
        
        // Almacena los datos del ítem para incluirlos en el error
        $itemData = [
            'id' => $rowDB['id'],
            'codigo' => $rowDB['codigo'],
            'und_empaque' => $undEmpaque,
            'precio' => $precio,
            'cantidad_solicitada' => $cantidadExcel
        ];

        if ($precio == 0) {
            $respuesta['errores'][] = array_merge($itemData, ['mensaje' => "El producto con el código '$codigoExcel' tiene un precio de 0."]);
        } else if ($undEmpaque <= 0) {
            $respuesta['errores'][] = array_merge($itemData, ['mensaje' => "La unidad de empaque para el código '$codigoExcel' no puede ser cero o un valor negativo. Revise el valor en la base de datos."]);
        } else if ($cantidadExcel % $undEmpaque !== 0) {
            $respuesta['errores'][] = array_merge($itemData, ['mensaje' => "La cantidad ($cantidadExcel) para el código '$codigoExcel' no es múltiplo de la unidad de empaque ($undEmpaque) de este producto."]);
        } else {
            $total_por_item = $cantidadExcel * $precio;
            $respuesta['exitos'][] = array_merge($itemData, ['total' => (float)$total_por_item]);
            $respuesta['totalPedido'] += $total_por_item;
        }
    }
    $stmt->close();
} catch (\Exception $e) {
    $respuesta['status'] = 'error';
    $respuesta['errores'][] = ['mensaje' => 'Ocurrió un error: ' . $e->getMessage()];
}

$linki->close();

echo json_encode($respuesta);
?>