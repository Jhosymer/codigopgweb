<?php
// Asegura que no se muestre nada antes de tiempo
ob_start();

// Aumenta el límite de memoria para manejar archivos grandes
ini_set('memory_limit', '512M'); 

// Asegúrate de que esta ruta sea la correcta para el autoload de Composer
require './../../../../vendor/excel/vendor/autoload.php';

// Asegúrate de que esta ruta sea la correcta para tu archivo de conexión
include("./../../../config/conex.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// 1. Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();

// 2. CREAR Y POBLAR LA HOJA DE VALIDACIÓN
$sheetData = $spreadsheet->createSheet();
$sheetData->setTitle('DatosValidacion');

// Consulta para obtener todos los códigos y sus unidades de empaque
$sqlCodigos = "SELECT DISTINCT t1.codigo, t1.und_empaque, t1.descripcion, t1.precio
FROM filtro_codificacion AS t1 
LEFT JOIN filtro_alternativo_sap AS t2 ON t1.id = t2.id_codigo 
WHERE (t1.act_sap = 'Y' OR t2.act_sap = 'Y') AND t1.precio != 0 ORDER BY t1.codigo ASC";
$resultCodigos = $linki->query($sqlCodigos);

$codigosArray = [];
if ($resultCodigos && $resultCodigos->num_rows > 0) {
    while ($row = $resultCodigos->fetch_assoc()) {
        $codigosArray[] = [$row['codigo'], $row['descripcion'], $row['und_empaque'], $row['precio']];
    }
}

// Llenar la hoja de validación con los datos en el nuevo orden
$sheetData->fromArray($codigosArray, NULL, 'A1');

// Ocultar la hoja de validación para que el usuario no la vea
$sheetData->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);

// 3. SELECCIONAR LA HOJA PRINCIPAL PARA POBLAR
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Reporte de Códigos');

// 4. Definir los encabezados en el nuevo orden
$headers = ['Codigo', 'Descripción', 'Ud.Emp', 'Cantidad', 'Precio Und', 'Total'];
$sheet->fromArray($headers, NULL, 'A1');

// Estilos de encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];
$codeHeaderStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E2001A'],
    ],
];

$blackHeaderStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '000000'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

$cantidadHeaderStyle = [
    'font' => [
        'bold' => true,
        'color' => ['argb' => Color::COLOR_WHITE],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'A9A9A9'],
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
];

// Estilo para el borde blanco entre celdas negras
$whiteBorderStyle = [
    'borders' => [
        'left' => [
            'borderStyle' => Border::BORDER_MEDIUM,
            'color' => ['argb' => 'FFFFFFFF'], // Blanco
        ],
    ],
];

// Aplicar estilos a las celdas de encabezado en el nuevo orden
$sheet->getStyle('A1')->applyFromArray($headerStyle)->applyFromArray($codeHeaderStyle);
$sheet->getStyle('B1')->applyFromArray($blackHeaderStyle); // Descripción
$sheet->getStyle('C1')->applyFromArray($blackHeaderStyle)->applyFromArray($whiteBorderStyle); // Ud.Emp con borde blanco a la izquierda
$sheet->getStyle('D1')->applyFromArray($cantidadHeaderStyle); // Cantidad
$sheet->getStyle('E1')->applyFromArray($blackHeaderStyle); // Precio Und
$sheet->getStyle('F1')->applyFromArray($blackHeaderStyle)->applyFromArray($whiteBorderStyle); // Total con borde blanco a la izquierda

// Congelar la fila de encabezados
$sheet->freezePane('A2');

// 5. Llenar las columnas con los datos iniciales de la base de datos
$resultInicial = $linki->query($sqlCodigos);
$rowNumber = 2;

if ($resultInicial && $resultInicial->num_rows > 0) {
    while ($row = $resultInicial->fetch_assoc()) {
        $sheet->setCellValue('A' . $rowNumber, $row['codigo']);
        $sheet->setCellValue('B' . $rowNumber, $row['descripcion']); // Columna B
        $sheet->setCellValue('C' . $rowNumber, $row['und_empaque']); // Columna C
        $sheet->setCellValue('E' . $rowNumber, $row['precio']); // Columna E (Precio Und)
        $rowNumber++;
    }
}

// 6. Aplicar la validación de datos y las fórmulas a las celdas
$totalRowsWithData = $rowNumber - 1;

// 7. Aplicar el estilo gris claro a las celdas con datos
$lightGreyRange = 'A2:C' . $totalRowsWithData;
$lightGreyRange2 = 'E2:F' . $totalRowsWithData; 
$sheet->getStyle($lightGreyRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');
$sheet->getStyle($lightGreyRange2)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

// 8. Bucle para aplicar validaciones y fórmulas fila por fila
for ($i = 2; $i <= $totalRowsWithData + 50; $i++) {

    // Validacion para la columna 'Código' (A)
    $validationCode = $sheet->getCell('A' . $i)->getDataValidation();
    $validationCode->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1('A' . $i . '=""');
    $validationCode->setPromptTitle('¡Atención!');
    $validationCode->setPrompt('El código no debe ser modificado.');
    $validationCode->setErrorTitle('¡Atención!');
    $validationCode->setError('El código ingresado no debe ser modificado.');

    // FÓRMULA para 'Descripción' (B)
    $sheet->setCellValue('B' . $i, '=IF(A' . $i . '="", "", VLOOKUP(A' . $i . ', DatosValidacion!$A:$B, 2, FALSE))');

    // Validación para 'Descripción' (B)
    $validationDesc = $sheet->getCell('B' . $i)->getDataValidation();
    $validationDesc->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1('B' . $i . '=""');
    $validationDesc->setPromptTitle('¡Atención!');
    $validationDesc->setPrompt('La descripción no debe ser modificada.');
    $validationDesc->setErrorTitle('¡Atención!');
    $validationDesc->setError('La descripción no debe ser modificada.');

    // FÓRMULA para 'Ud.Emp' (C)
    $sheet->setCellValue('C' . $i, '=IF(A' . $i . '="", "", VLOOKUP(A' . $i . ', DatosValidacion!$A:$C, 3, FALSE))');

    // Validación para 'Ud.Emp' (C)
    $validationUdEmp = $sheet->getCell('C' . $i)->getDataValidation();
    $validationUdEmp->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1('C' . $i . '=""');
    $validationUdEmp->setPromptTitle('¡Atención!');
    $validationUdEmp->setPrompt('La unidad de empaque no debe ser modificada.');
    $validationUdEmp->setErrorTitle('¡Atención!');
    $validationUdEmp->setError('La unidad de empaque no debe ser modificada.');

    // FÓRMULA para 'Precio Und' (E)
    $sheet->setCellValue('E' . $i, '=IF(A' . $i . '="", "", VLOOKUP(A' . $i . ', DatosValidacion!$A:$D, 4, FALSE))');

    // Validación para 'Precio Und' (E)
    $validationPrecio = $sheet->getCell('E' . $i)->getDataValidation();
    $validationPrecio->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1('E' . $i . '=""');
    $validationPrecio->setPromptTitle('¡Atención!');
    $validationPrecio->setPrompt('El precio unitario no debe ser modificado.');
    $validationPrecio->setErrorTitle('¡Atención!');
    $validationPrecio->setError('El precio unitario no debe ser modificado.');

    // FÓRMULA para 'Total' (F)
    $sheet->setCellValue('F' . $i, '=IF(D' . $i . '="", "", D' . $i . '*E' . $i . ')');

    // Validación para 'Total' (F)
    $validationTotal = $sheet->getCell('F' . $i)->getDataValidation();
    $validationTotal->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1('F' . $i . '=""');
    $validationTotal->setPromptTitle('¡Atención!');
    $validationTotal->setPrompt('El total se calcula automáticamente y no debe ser modificado.');
    $validationTotal->setErrorTitle('¡Atención!');
    $validationTotal->setError('El total se calcula automáticamente y no debe ser modificado.');
    
    // Validacion para la columna 'Cantidad' (D)
    $dataValidation = $sheet->getCell('D' . $i)->getDataValidation();
    $dataValidation->setType(DataValidation::TYPE_CUSTOM)
        ->setErrorStyle(DataValidation::STYLE_STOP)
        ->setAllowBlank(true)
        ->setShowInputMessage(true)
        ->setShowErrorMessage(true)
        ->setFormula1("=MOD(D" . $i . ", C" . $i . ")=0");
    $dataValidation->setPromptTitle('Cantidad Solicitada');
    $dataValidation->setPrompt('La cantidad de unidades que ingrese debe ser un múltiplo de la unidad de empaque de este producto.');
    $dataValidation->setErrorTitle('¡Atención!');
    $dataValidation->setError("La cantidad ingresada no es un múltiplo de la unidad de empaque. Por ejemplo, si la unidad de Empaque es (8) podrías ingresar (8, 16, etc).");
}

// 9. Aplicar bordes negros a todo el rango de datos
$dataRange = 'A1:F' . $totalRowsWithData;
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Negro
        ],
    ],
];
$sheet->getStyle($dataRange)->applyFromArray($styleArray);

// 10. Ajustar automáticamente el ancho de las columnas y aplicar Wrap Text a la Descripción
foreach (range('A', 'F') as $columnID) {
    if ($columnID == 'B') { // Columna de Descripción
        $sheet->getColumnDimension($columnID)->setWidth(110);
        $sheet->getStyle($columnID . '2:' . $columnID . $totalRowsWithData)->getAlignment()->setWrapText(true);
    } else {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
}

// 11. Añadir el total al final de la hoja
$lastRowForTotal = $totalRowsWithData + 1;

// Combinar celdas para el texto "Total"
$sheet->mergeCells('E' . $lastRowForTotal . ':E' . $lastRowForTotal);

$sheet->setCellValue('E' . $lastRowForTotal, 'Total'); // Celda para el texto "Total"
$sheet->setCellValue('F' . $lastRowForTotal, '=SUM(F2:F' . $totalRowsWithData . ')'); // Celda para la suma total

// Aplicar estilos a las celdas del total
$sheet->getStyle('E' . $lastRowForTotal)->applyFromArray($blackHeaderStyle);
$sheet->getStyle('F' . $lastRowForTotal)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F2F2F2');

// Definir el formato de moneda personalizado para 0,00$
$customCurrencyFormat = '#,##0.00"$"';

// Aplicar formato de moneda a las columnas de precio y total
$sheet->getStyle('E2:E' . $totalRowsWithData)->getNumberFormat()->setFormatCode($customCurrencyFormat);
$sheet->getStyle('F2:F' . $totalRowsWithData)->getNumberFormat()->setFormatCode($customCurrencyFormat);

// Aplicar formato de moneda y bordes a las celdas del total
$sheet->getStyle('F' . $lastRowForTotal)->getNumberFormat()->setFormatCode($customCurrencyFormat);

// Aplicar bordes solo a las celdas del total
$totalRange = 'E' . $lastRowForTotal . ':F' . $lastRowForTotal;
$sheet->getStyle($totalRange)->applyFromArray($styleArray);

// 12. Proteger la hoja de cálculo
// Habilitar la protección
$sheet->getProtection()->setPassword('jetfilter'); // Puedes cambiar la contraseña aquí
$sheet->getProtection()->setSheet(true); // Proteger la hoja
$sheet->getProtection()->setFormatCells(true);
$sheet->getProtection()->setFormatColumns(true);
$sheet->getProtection()->setFormatRows(true);
$sheet->getProtection()->setInsertColumns(false);
$sheet->getProtection()->setInsertRows(true);
$sheet->getProtection()->setDeleteColumns(false);
$sheet->getProtection()->setDeleteRows(true);

// Desproteger las celdas de la columna Cantidad (D) para que puedan ser editadas
$sheet->getStyle('D2:D' . $totalRowsWithData)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('D' . ($totalRowsWithData + 1) . ':D' . ($totalRowsWithData + 50))->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
$sheet->getStyle('D1')->getProtection()->setLocked(Protection::PROTECTION_PROTECTED);


// 13. Configurar el escritor y las cabeceras para la descarga
$writer = new Xlsx($spreadsheet);
$fechaActual = date('Y-m-d');
$fileName = 'Pedido jetfilter-' . $fechaActual . '.xlsx';

// Limpiar el buffer de salida y enviar cabeceras
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// 14. Enviar el archivo al navegador para su descarga
$writer->save('php://output');

// Cerrar la conexión a la base de datos
$linki->close();
exit();
?>