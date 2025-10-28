<?php
// Este script inserta nuevos productos en la base de datos
// leyendo un archivo JSON desde una ruta de red.

date_default_timezone_set('America/Caracas');
session_start();

// 1. Configuración de la ruta del archivo JSON
$jsonFilePath = '//192.168.0.63/wwwroot/pgweb/filtrosimportados.json';

// Conexión a la base de datos
include_once('./../config/conexion.php');

$nuevosProductos = 0;
$mensajeError = '';

try {
    // 2. Leer el archivo JSON
    $jsonContent = file_get_contents($jsonFilePath);

    // 3. Decodificar el JSON a un array de PHP
    $datosJson = json_decode($jsonContent, true);

    // Verificar si se pudo leer y decodificar el archivo correctamente
    if ($datosJson === null || !is_array($datosJson)) {
        throw new Exception("Error al leer o decodificar el archivo JSON. Verifique la ruta y el formato.");
    }

    // 4. Iniciar el procesamiento de la base de datos
    foreach ($datosJson as $fila) {
        // Asigna los valores del JSON a variables.
        $codigo        = $fila['ItemCode'];
        $codigo_buscar = str_replace(' ', '', $fila['ItemCode']);
        $codigo_barra  = $fila['CodeBars'];
        $descripcion   = $fila['ItemName'];
        
        // CORRECCIÓN: Reemplazar la coma por un punto en el precio
        $precio = str_replace(',', '.', $fila['Price']);
        
        $stock         = $fila['Disponible'];
        $und_empaque   = $fila['SalPackUn'];
        
        // Comprueba si el código ya existe en la base de datos
        $consulta = $base_de_datos->prepare("SELECT codigo FROM filtro_codificacion WHERE codigo = :codigo");
        $consulta->bindParam(':codigo', $codigo);
        $consulta->execute();

        if ($consulta->rowCount() == 0) {
            // Si el código NO existe, inserta un nuevo registro.
            $insertConsulta = $base_de_datos->prepare("
                INSERT INTO filtro_codificacion (
                    codigo,
                    codigo_buscar,
                    codigo_barra,
                    descripcion,
                    precio,
                    stock,
                    und_empaque,
                    act_sap,
                    fecha_actualiza,
                    sincronizado,
                    updated_at,
                    deleted_at
                ) VALUES (
                    :codigo,
                    :codigo_buscar,
                    :codigo_barra,
                    :descripcion,
                    :precio,
                    :stock,
                    :und_empaque,
                    'Y',
                    DATE_FORMAT(CURDATE(), '%d-%m-%Y'),
                    DATE_FORMAT(CURDATE(), '%Y%m%d'),
                    NULL,
                    NOW() -- Se agrega la fecha y hora actual aquí
                )
            ");
            
            $insertConsulta->bindParam(':codigo', $codigo);
            $insertConsulta->bindParam(':codigo_buscar', $codigo_buscar);
            $insertConsulta->bindParam(':codigo_barra', $codigo_barra);
            $insertConsulta->bindParam(':descripcion', $descripcion);
            $insertConsulta->bindParam(':precio', $precio);
            $insertConsulta->bindParam(':stock', $stock);
            $insertConsulta->bindParam(':und_empaque', $und_empaque);
            $insertConsulta->execute();
            
            $nuevosProductos++;
        }
    }

    // 5. Configurar los mensajes de salida
    if ($nuevosProductos > 0) {
        echo "Se crearon $nuevosProductos nuevos productos exitosamente.";
    } else {
        echo 'No se encontraron nuevos productos para agregar. Todos los códigos del archivo JSON ya existen en la base de datos.';
    }

} catch (Exception $e) {
    // Manejo de errores
    echo "Error al procesar el archivo JSON: " . $e->getMessage();
}

exit();
?>