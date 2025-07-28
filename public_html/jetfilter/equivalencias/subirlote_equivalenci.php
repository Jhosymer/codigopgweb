<?php
    include_once('./../arriba_carpeta.php');
    include_once('./../conexion/conexion.php');
    ?>
    <style>
 /* Estilo general del contenedor del formulario */
.form-container {
    max-width: 600px; /* Ancho máximo del contenedor */
    margin: 0 auto; /* Centrar el contenedor */
    padding: 20px; /* Espaciado interno */
    background-color: #f9f9f9; /* Color de fondo */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra */
}

/* Estilo del título del formulario */
.form-title {
    font-size: 24px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
    text-align: center; /* Centrar el texto */
    margin-bottom: 20px; /* Espaciado inferior */
}

/* Estilo de las etiquetas */
.form-label {
    display: block; /* Mostrar como bloque */
    margin-bottom: 8px; /* Espaciado inferior */
    font-weight: bold; /* Negrita */
}

/* Estilo de los select y inputs */
.form-select, .form-input {
    width: 100%; /* Ancho completo */
    padding: 10px; /* Espaciado interno */
    border: 1px solid #ccc; /* Borde */
    border-radius: 4px; /* Bordes redondeados */
    box-sizing: border-box; /* Incluir padding y border en el ancho total */
    margin-bottom: 15px; /* Espaciado inferior */
}

/* Estilo del botón */
.form-button {
    background-color: #007bff; /* Color de fondo */
    color: white; /* Color del texto */
    border: none; /* Sin borde */
    padding: 10px 20px; /* Espaciado interno */
    border-radius: 4px; /* Bordes redondeados */
    cursor: pointer; /* Cambiar cursor al pasar el mouse */
    font-size: 16px; /* Tamaño de fuente */
}

/* Efecto al pasar el mouse sobre el botón */
.form-button:hover {
    background-color: #0056b3; /* Color de fondo al pasar el mouse */
}

/* Espaciado superior */
.mt-5 {
    margin-top: 3rem; /* Espaciado superior */
}

/* Espaciado inferior */
.mb-4 {
    margin-bottom: 1.5rem; /* Espaciado inferior */
}

.mt-2 {
    margin-top: .5rem !important;
}

.alert {
   
    position: relative;
    padding: 1rem, 1rem;
    margin-bottom: 1rem;
    color: #495057;
    border: #495057;
    border-radius: #495057;
}
    </style>
 <title>lote de equivalencia </title>
 <div class="form-container mt-5">
    <h2 class="form-title text-center mb-4">Cargar lote de equivalencia</h2>
    <form action="procesar_excel.php" method="post" enctype="multipart/form-data" class="form-border p-4 rounded shadow">
        <div class="form-group mb-3">
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
        <div class="form-group mb-3">
            <label for="archivo_excel" class="form-label">Selecciona un archivo Excel</label>
            <input type="file" class="form-input" name="archivo_excel" id="archivo_excel" accept=".xlsx, .xls" required>
        </div>
         <div class="alert alert-dark mt-2" role="alert">
                <strong>Nota:</strong> El archivo Excel debe contener <strong>dos filas</strong>: "código-marca" y "código web". Sin encabezados
            </div>
        <div class="text-center">
            <button type="submit" class="form-button">Subir Archivo</button>
        </div>
    </form>
</div>

<?php   
    
   include_once('./../abajo_carpeta.html');

    ?>
    