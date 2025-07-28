<!-- Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="Panton-Bold rojoweb" id="uploadModalLabel">Subir catalogo completo PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="pdfFile" class="form-label">Selecciona un archivo PDF</label>
                        <input type="file" class="form-control" id="pdfFile" name="pdfFile" accept=".pdf" required>
                    </div>
                    <button type="submit" class="btn-icon">Subir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $targetDir = "./PDF/";
    $targetFile = $targetDir . "Web_Catalogo.pdf";
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($_FILES["pdfFile"]["name"], PATHINFO_EXTENSION));

    // Verificar si el archivo es un PDF
    if ($fileType != "pdf") {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Lo siento, solo se permiten archivos PDF.'
                });
              </script>";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk es 0 por un error
    if ($uploadOk == 0) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Lo siento, tu archivo no fue subido.'
                });
              </script>";
    } else {
        // Intentar subir el archivo
        if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'El archivo ha sido guardado correctamente como Web_Catalogo.pdf.'
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Lo siento, hubo un error al subir tu archivo.'
                    });
                  </script>";
        }
    }
}
?>

