<?php 
     $loc = "../../../";
     $locj = "./../../";
     $title = "lote de equivalencia ";
     include_once('../index/header.php');
     include_once('../../../config/conexion.php');
    ?>

<div class="container mt-5">
    <h2 class="titulo text-uppercase text-center  py-5">Cargar lote de equivalencia</h2>
    <form action="procesar_excel.php" method="post" enctype="multipart/form-data" class="border p-4 rounded shadow">
        <div class="mb-3">
            <label for="marca" class="form-label">Seleccione Marca</label>
            <select class="form-select" name="id_marca" id="marca" required>
                <option value="" disabled selected>Seleccione una marca</option>
                <?php
                $stmt = $base_de_datos->query("SELECT id, marca FROM equivalencia_marca where deleted_at is null ORDER BY marca ASC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value=\"{$row['id']}\">{$row['marca']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="archivo_excel" class="form-label">Selecciona un archivo Excel</label>
            <input type="file" class="form-control" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required>
             <div class="alert alert-light mt-2" role="alert">
                <strong>Nota:</strong> El archivo Excel debe contener : "código marca" y "código web". Sin encabezados
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Subir Archivo</button>
        </div>
    </form>
</div>

<?php   
    
    include("../index/footer.php");

    ?>
    