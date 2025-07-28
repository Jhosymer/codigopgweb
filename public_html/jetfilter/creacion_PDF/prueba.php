

<?php


    require_once('./../../librerias/dompdf/autoload.inc.php');
    require_once('./../../librerias/fpdf185/fpdf.php');
    require_once('./../../librerias/FPDI-2.3.7/FPDI-2.3.7/src/autoload.php');
    
    $files = array("./../PDF/vehiculos_comerciales.pdf","./../PDF/vehiculos_agricolas.pdf");

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

    $pdf->Output("F","./../PDF/vehiculos_livianos.pdf");

   /* use Dompdf\Dompdf;
    $domPDF = new Dompdf;
    //$options = $dompdf->getOptions();
    $domPDF->loadHTML($html);
    $domPDF->render();
    $pdf = $domPDF->output();
    file_put_contents("./../PDF/vehiculos_livianos.pdf",$createdPdf);
    $domPDF->stream("dompdf_out.pdf", array("Attachment" => false));
    //header("location: ./../especificaciones.php?pdf_generado=true");*/
?>