<?php 
include_once('./../arriba_carpeta.php');
include_once('./../conexion/conexion.php');
require './../vendor/excel/vendor/autoload.php'; // Asegúrate de que PhpSpreadsheet esté instalado

use PhpOffice\PhpSpreadsheet\IOFactory;
?>
<title>Lote de Equivalencia</title>
 <style> 
 /* Estilo general del contenedor del formulario */
.form-container {
    max-width: 1200px; /* Ancho máximo del contenedor */
    margin: 0 auto; /* Centrar el contenedor */
    padding: 20px; /* Espaciado interno */
    background-color: #f9f9f9; /* Color de fondo */
    border-radius: 8px; /* Bordes redondeados */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Sombra */
}

/* Estilo del título del formulario */
h3 {
    font-size: 24px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
    text-align: center; /* Centrar el texto */
    margin-bottom: 20px; /* Espaciado inferior */
}

/* Estilo de las alertas */
.alert {
    padding: 15px; /* Espaciado interno */
    border-radius: 5px; /* Bordes redondeados */
    margin-bottom: 20px; /* Espaciado inferior */
}

/* Estilo de la alerta de éxito */
.alert-success {
    background-color: #d4edda; /* Color de fondo */
    color: #155724; /* Color del texto */
    border: 1px solid #c3e6cb; /* Borde */
}

/* Estilo de la alerta de error */
.alert-danger {
    background-color: #f8d7da; /* Color de fondo */
    color: #721c24; /* Color del texto */
    border: 1px solid #f5c6cb; /* Borde */
}

/* Estilo de la alerta de advertencia */
.alert-warning {
    background-color: #fff3cd; /* Color de fondo */
    color: #856404; /* Color del texto */
    border: 1px solid #ffeeba; /* Borde */
}

/* Estilo de los enlaces */
.btn-icon {
    text-decoration: none; /* Sin subrayado */
    color: #007bff; /* Color del texto */
    font-weight: bold; /* Negrita */
}

/* Efecto al pasar el mouse sobre el enlace */
.btn-icon:hover {
    text-decoration: underline; /* Subrayar al pasar el mouse */
}

/* Estilo para el espaciado superior */
.mt-5 {
    margin-top: 3rem; /* Espaciado superior */
}

/* Estilo para el espaciado inferior */
.mb-2 {
    margin-bottom: 0.5rem; /* Espaciado inferior */
}

/* Estilo para el espaciado inferior más grande */
.mb-3 {
    margin-bottom: 1rem; /* Espaciado inferior */
}

/* Estilo para centrar elementos */
.text-center {
    text-align: center; /* Centrar texto */
}

/* Estilo para flexbox */
.d-flex {
    display: flex; /* Usar flexbox */
}

/* Estilo para alinear elementos en el centro */
.align-items-center {
    align-items: center; /* Alinear elementos verticalmente al centro */
}

/* Estilo para permitir que un elemento crezca */
.flex-grow-1 {
    flex-grow: 1; /* Permitir que el elemento crezca */
}
/* Estilo del botón */
.boton {
    background-color: #007bff; /* Color de fondo */
    color: white; /* Color del texto */
    border: none; /* Sin borde */
    padding: 10px 20px; /* Espaciado interno */
    border-radius: 4px; /* Bordes redondeados */
    cursor: pointer; /* Cambiar cursor al pasar el mouse */
    font-size: 16px; /* Tamaño de fuente */
    text-decoration: none; /* Sin subrayado */
    display: inline-block; /* Permitir que el botón se ajuste al contenido */
    min-width: 150px; /* Ancho mínimo */
    max-width: 200px; /* Ancho máximo */
    text-align: center; /* Centrar texto */
}

/* Efecto al pasar el mouse sobre el botón */
.boton:hover {
    background-color: #0056b3; /* Color de fondo al pasar el mouse */
}

 </style>
<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_excel'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];

    // Obtener el id_marca del formulario
    $id_marca = $_POST['id_marca'];

    // Obtener la marca correspondiente al id_marca
    $stmt_marca = $base_de_datos->prepare("SELECT marca FROM equivalencia_marca WHERE id = :id_marca AND deleted_at IS NULL");
    $stmt_marca->execute(['id_marca' => $id_marca]);
    $marca_result = $stmt_marca->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la marca
    if ($marca_result) {
        $marca = $marca_result['marca']; // Asignar la marca seleccionada
    } else {
        echo "<div class='alert alert-danger'>Marca no encontrada.</div>";
        exit;
    }

    // Cargar el archivo Excel
    $spreadsheet = IOFactory::load($archivo);
    $hoja = $spreadsheet->getActiveSheet();
    
    // Variable para acumular mensajes de error
    $registros_duplicados = [];
    $registros_insertados = 0; // Contador de registros insertados

    // Recorrer las filas del archivo Excel
    foreach ($hoja->getRowIterator() as $fila) {
        $celdas = $fila->getCellIterator();
        $celdas->setIterateOnlyExistingCells(false); // Para incluir celdas vacías

        $codigo_marca = '';
        $codigo = '';

        foreach ($celdas as $celda) {
            if ($celda->getColumn() == 'A') { // Suponiendo que 'A' es codigo_marca
                $codigo_marca = $celda->getValue();
            }
            if ($celda->getColumn() == 'B') { // Suponiendo que 'B' es codigo
                $codigo = $celda->getValue();
            }
        }

        // Limpiar el campo codigo solo si no es null
        $codigo_buscar = $codigo !== null ? preg_replace('/[^A-Za-z0-9]/', '', $codigo) : '';

        // Buscar en la tabla filtro_codificacion
        $stmt = $base_de_datos->prepare("SELECT id, codigo, codigo_buscar FROM filtro_codificacion WHERE codigo_buscar = :codigo_buscar AND deleted_at IS NULL");
        $stmt->execute(['codigo_buscar' => $codigo_buscar]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $id_codigo = $resultado['id']; // Obtener el id_codigo
            $codigo_codificacion = $resultado['codigo']; // Obtener el codigo
            $codigo_buscarr = $resultado['codigo_buscar']; 
            $codigo_marca_buscar = $codigo_marca !== null ? preg_replace('/[^A-Za-z0-9]/', '', $codigo_marca) : '';
        
            // Verificar si el registro ya existe en filtro_equivalencia
            $stmt_existencia = $base_de_datos->prepare("SELECT COUNT(*) FROM filtro_equivalencia WHERE codigo = :codigo AND id_marca = :id_marca AND codigo_marca = :codigo_marca AND deleted_at IS NULL");
            $stmt_existencia->execute([
                'codigo' => $codigo_codificacion,
                'id_marca' => $id_marca,
                'codigo_marca' => $codigo_marca
            ]);
            $existe = $stmt_existencia->fetchColumn();

            if ($existe > 0) { // Si existe, acumular mensaje de error
                $registros_duplicados[] = "Código: $codigo_codificacion, Marca: $marca, Código de Marca: $codigo_marca.";
            }
        }
    }

    // Si hay registros duplicados, mostrar mensaje y detener el proceso
    if (!empty($registros_duplicados)) {
        echo "<div class='form-container mb-2 mt-5'><div class='d-flex align-items-center alert alert-danger'><h3 class=' flex-grow-1 text-center mb-3'>No se pueden crear Nuevos Registros.</h3><a href='./subirlote_equivalenci.php'  class='boton' >Atrás</a></div>";
        echo "<div class='alert alert-warning'>";
        echo "<h3 class='text-center mb-3'> Verifica tu Excel, los siguientes registros ya existen en la base de datos:</h3>";
        echo "<ul>";
        foreach ($registros_duplicados as $registro) {
            echo "<li>$registro</li>";
        }
        echo "</ul>";
        echo "</div></div>";
    } else {
        // Si no hay duplicados, proceder a la inserción
        foreach ($hoja->getRowIterator() as $fila) {
            $celdas = $fila->getCellIterator();
            $celdas->setIterateOnlyExistingCells(false); // Para incluir celdas vacías

            $codigo_marca = '';
            $codigo = '';

            foreach ($celdas as $celda) {
                if ($celda->getColumn() == 'A') { // Suponiendo que 'A' es codigo_marca
                    $codigo_marca = $celda->getValue();
                }
                if ($celda->getColumn() == 'B') { // Suponiendo que 'B' es codigo
                    $codigo = $celda->getValue();
                }
            }

            // Limpiar el campo codigo solo si no es null
            $codigo_buscar = $codigo !== null ? preg_replace('/[^A-Za-z0-9]/', '', $codigo) : '';

            // Buscar en la tabla filtro_codificacion
            $stmt = $base_de_datos->prepare("SELECT id, codigo, codigo_buscar FROM filtro_codificacion WHERE codigo_buscar = :codigo_buscar AND deleted_at IS NULL");
            $stmt->execute(['codigo_buscar' => $codigo_buscar]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $id_codigo = $resultado['id']; // Obtener el id_codigo
                $codigo_codificacion = $resultado['codigo']; // Obtener el codigo
                $codigo_buscarr = $resultado['codigo_buscar']; 
                $codigo_marca_buscar = $codigo_marca !== null ? preg_replace('/[^A-Za-z0-9]/', '', $codigo_marca) : '';
                
                // Verificar si el registro ya existe en filtro_equivalencia
                $stmt_existencia = $base_de_datos->prepare("SELECT COUNT(*) FROM filtro_equivalencia WHERE codigo = :codigo AND id_marca = :id_marca AND codigo_marca = :codigo_marca AND deleted_at IS NULL");
                $stmt_existencia->execute([
                    'codigo' => $codigo_codificacion,
                    'id_marca' => $id_marca,
                    'codigo_marca' => $codigo_marca
                ]);
                $existe = $stmt_existencia->fetchColumn();

                if ($existe == 0) { // Si no existe, realizar la inserción
                    // Insertar en la tabla filtro_equivalencia
                    $fecha_hoy = date('Ymd'); // Formato YYYYMMDD
                    $stmt_insertar = $base_de_datos->prepare("INSERT INTO filtro_equivalencia (codigo, codigo_buscar, marca, codigo_marca, codigo_marca_buscar, id_marca, id_codigo, sincronizado, deleted_at, updated_at) VALUES (:codigo, :codigo_buscar, :marca, :codigo_marca, :codigo_marca_buscar, :id_marca, :id_codigo, :sincronizado, NULL, NULL)");
                    $stmt_insertar->execute([
                        'codigo' => $codigo_codificacion, // Usar el codigo de filtro_codificacion
                        'codigo_buscar' => $codigo_buscar, // Usar el codigo_buscar
                        'marca' => $marca,
                        'codigo_marca' => $codigo_marca,
                        'codigo_marca_buscar' => $codigo_marca_buscar,
                        'id_marca' => $id_marca, // Usar el id_marca del formulario
                        'id_codigo' => $id_codigo, // Agregar id_codigo aquí
                        'sincronizado' => $fecha_hoy
                    ]);
                    $registros_insertados++; // Incrementar el contador
                }
            }
        }
        // Mostrar el mensaje de éxito con la cantidad de registros insertados
        echo "<div class='form-container mb-2 mt-5'><div class='d-flex align-items-center alert alert-success'><h3 class=' flex-grow-1 text-center mb-3'>Datos procesados correctamente. Registros insertados: $registros_insertados.</h3><a href='./subirlote_equivalenci.php'  class='boton' >Atrás</a></div></div>";
    }
}

include_once('./../abajo_carpeta.html');
?>
