<?php

    unlink("./../PDF/catalogo_completo.pdf");

    require_once('./../../librerias/fpdf185/fpdf.php');
    require_once('./../../librerias/FPDI-2.3.7/FPDI-2.3.7/src/autoload.php');
    
    $files = array("./../PDF/vehiculos_comerciales.pdf","./../PDF/vehiculos_agricolas.pdf","./../PDF/vehiculos_pasajeros.pdf",'./../PDF/equivalencias.pdf','./../PDF/especificaciones.pdf','./../PDF/fuera_de_carretera.pdf');

    use setasign\Fpdi\Fpdi;
    $pdf = new Fpdi();

    foreach($files as $file){
        $pageCount = $pdf->setSourceFile($file);
        for($i = 1; $i <= $pageCount; $i++){
            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);
            $pdf->AddPage($size['orientacion'],$size);
            $pdf->useTemplate($template);
        }
    }

    $pdf->Output("F","./../PDF/catalogo_completo.pdf");

    header("location: ./../especificaciones.php?pdf_generado=true");
?>