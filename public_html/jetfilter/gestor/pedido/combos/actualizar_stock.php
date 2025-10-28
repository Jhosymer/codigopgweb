<?php
// Incluye la librería PhpSpreadsheet
require './../../../../vendor/excel/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
date_default_timezone_set('America/Caracas');
session_start();

// Verifica si se ha subido un archivo
if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
    // Obtiene la ruta temporal del archivo subido
    $excelFileTemporal = $_FILES['excelFile']['tmp_name'];

    try {
        // Carga el archivo Excel
        $documento = IOFactory::load($excelFileTemporal);
        $hoja = $documento->getActiveSheet();
        $datos = $hoja->toArray(); // Obtiene los datos del archivo Excel como un arreglo
        
        // Conexión a la base de datos (ajusta los valores según tu configuración)
        include_once('./../../../../config/conexion.php');

        $cambiosRealizados = 0;

        // Actualiza los registros en la tabla 
        foreach ($datos as $indice => $fila) {
            if ($indice > 0) { // Ignora la primera fila (encabezados)
                $codigo = $fila[0]; // la primera columna contiene el Codigo
                $precio = $fila[1]; // la segunda columna contiene el precio
                $stock = $fila[2]; // la tercera columna contiene el stock
        
                // Comprueba si el código existe en la base de datos
                $consulta = $base_de_datos->prepare("SELECT codigo FROM filtro_codificacion WHERE codigo = :codigo AND deleted_at IS NULL");
                $consulta->bindParam(':codigo', $codigo);
                $consulta->execute();
        
                if ($consulta->rowCount() > 0) {
                    // Si el código existe, actualiza el registro.
                    $updateConsulta = $base_de_datos->prepare("UPDATE filtro_codificacion SET precio = :precio, stock = :stock, precio = WHERE codigo = :codigo AND deleted_at IS NULL");
                    $updateConsulta->bindParam(':precio', $precio);
                    $updateConsulta->bindParam(':stock', $stock);
                    $updateConsulta->bindParam(':codigo', $codigo);
                    $updateConsulta->execute();
        
                    $cambiosRealizados++;
                }
            }
        }

        if ($cambiosRealizados > 0) {
            $_SESSION['mensaje'] = "Cambios de precio y stock de $cambiosRealizados productos realizados exitosamente";
            $_SESSION['actualizado'] = true;
        } else {
            $_SESSION['mensaje'] = "Verifique el archivo: asegúrese de que tenga las columnas codigo, precio y stock. Compruebe que los códigos de productos coincidan con la base de datos.";
            $_SESSION['error'] = true;
        }

        // Redirige a index.php solo si no hay errores
        header("location:../../pedido/index.php");
    } catch (Exception $e) {
        // Imprime el error en lugar de redirigir
      //  echo "Error al procesar el archivo: " . $e->getMessage();
        $_SESSION['error'] = true;
         header("location:../../pedido/index.php"); // Comentar esta línea para ver el error
    }
} else {
    // Imprime el error de carga
   // echo "Error al cargar el archivo: " . $_FILES['excelFile']['error'];
    $_SESSION['error'] = true;
     header("location:../../pedido/index.php"); // Comentar esta línea para ver el error
}
?>
