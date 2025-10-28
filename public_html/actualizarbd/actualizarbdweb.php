<?php

date_default_timezone_set('America/Caracas');

header('Access-Control-Allow-Origin: *');
// Desactivar la caché
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Configuración de la ruta del archivo JSON
$jsonFilePath = './../ftp/sap.json';
include_once('./../config/conexion.php'); 

// Inicializa contadores
$cambios_filtro_codificacion = 0;
$cambios_filtro_alternativo = 0;
$no_encontrados_itemcode = []; 
$no_encontrados_altitem = [];
$nuevos_nano_insertados_codificacion = []; // Resumen de las inserciones finales NANO

// Función para limpiar el código de caracteres especiales y espacios
function limpiar_codigo($codigo) {
    if (is_null($codigo)) {
        return '';
    }
    // Elimina cualquier caracter que no sea letra (a-z, A-Z) o número (0-9)
    return preg_replace('/[^a-zA-Z0-9]/', '', $codigo);
}


try {
    // Marcar todos los registros como NO actualizados ('N') antes de empezar
    $base_de_datos->prepare("UPDATE filtro_codificacion SET act_sap = 'N'")->execute();
    $base_de_datos->prepare("UPDATE filtro_alternativo_sap SET act_sap = 'N'")->execute();

    // Leer y decodificar el archivo JSON
    $jsonContent = file_get_contents($jsonFilePath);
    $datosJson = json_decode($jsonContent, true);

    if ($datosJson === null || !is_array($datosJson)) {
        throw new Exception("Error al leer o decodificar el archivo JSON. Verifique la ruta y el formato.");
    }

    // Proceso de actualización del JSON 
    foreach ($datosJson as $fila) {
        $codigo = $fila['ItemCode'];
        $descripcion = $fila['ItemName'];
        $stock = $fila['Disponible'];
        $precio = $fila['Price'];
        $undEmpaque = $fila['SalPackUn'];
        $codigoBarra = $fila['CodeBars'];
        $itemAlterno = $fila['AltItem'];
        // 'codigo_catalogo' del JSON, manejando null o si no existe
        // Se usa la sintaxis que indicaste: $fila['codigo_catalogo']
        $codigoCatalogo = isset($fila['codigo_catalogo']) && !is_null($fila['codigo_catalogo']) ? $fila['codigo_catalogo'] : '';
        
        $limpioCodigo = limpiar_codigo($codigo); 

        $fue_procesado = false; // Bandera para saber si se encontró en alguna de las tablas

        $condicionNano = (stripos((string)$codigo, 'NANO') !== false)
        ? '' // Si contiene 'NANO', no aplica la condición.
        : 'AND deleted_at IS NULL'; 

        // Búsqueda principal en filtro_codificacion.codigo (Usando ItemCode del JSON)
        $sql = "SELECT id FROM filtro_codificacion WHERE codigo = :codigo " . $condicionNano ;
        $consulta = $base_de_datos->prepare($sql);
        $consulta->bindParam(':codigo', $codigo);
        $consulta->execute();

        if ($consulta->rowCount() > 0) {
            // Obtener el ID encontrado y asignarlo a la variable $id
            $resultadoId = $consulta->fetch(PDO::FETCH_ASSOC);
            $id = $resultadoId['id'];

            // Si existe, actualiza el registro principal y marca act_sap = 'Y'.
            $updateConsulta = $base_de_datos->prepare("
                UPDATE filtro_codificacion 
                SET 
                descripcion = :descripcion, 
                stock = :stock, 
                precio = :precio, 
                und_empaque = :undEmpaque,
                codigo_barra = :codigoBarra,
                act_sap = 'Y'
                WHERE id = :id
            ");
            
            //  Ejecutar la actualización usando el ID
            $updateConsulta->execute([
                ':descripcion' => $descripcion,
                ':stock' => $stock,
                ':precio' => $precio,
                ':undEmpaque' => $undEmpaque,
                ':codigoBarra' => $codigoBarra,
                ':id' => $id 
            ]);

            $cambios_filtro_codificacion++;
            $fue_procesado = true;


        } else {
            // Si no se encuentra, busca en filtro_alternativo_sap (Usando ItemCode del JSON)
            $consultaAlternativa = $base_de_datos->prepare("SELECT id_codigo FROM filtro_alternativo_sap WHERE codigo_alt = :codigo");
            $consultaAlternativa->bindParam(':codigo', $codigo);
            $consultaAlternativa->execute();

            if ($consultaAlternativa->rowCount() > 0) {
                //Actualiza el principal y marca el alternativo a 'Y'.
                $resultadoAlternativo = $consultaAlternativa->fetch(PDO::FETCH_ASSOC);
                $idCodigo = $resultadoAlternativo['id_codigo'];
                
                // Actualiza los campos en filtro_codificacion 
                $updatePrincipal = $base_de_datos->prepare("
                    UPDATE filtro_codificacion 
                    SET 
                    descripcion = :descripcion, 
                    stock = :stock, 
                    precio = :precio, 
                    und_empaque = :undEmpaque,
                    codigo_barra = :codigoBarra,
                    act_sap = 'N'
                    WHERE id = :idCodigo
                ");
                $updatePrincipal->execute(compact('descripcion', 'stock', 'precio', 'undEmpaque', 'codigoBarra', 'idCodigo'));
                $cambios_filtro_codificacion++;

                // Actualiza el campo act_sap en la tabla alternativa a 'Y'
                $base_de_datos->prepare("UPDATE filtro_alternativo_sap SET act_sap = 'Y' WHERE codigo_alt = :codigo")->execute([':codigo' => $codigo]);
                $cambios_filtro_alternativo++;
                $fue_procesado = true;

            } else {
                // Si no se encuentra, busca AltItem en filtro_codificacion.codigo
                $consultaAltItem = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :itemAlterno AND deleted_at IS NULL");
                $consultaAltItem->bindParam(':itemAlterno', $itemAlterno);
                $consultaAltItem->execute();

                if ($consultaAltItem->rowCount() > 0) {
                    //  Inserta el ItemCode actual como alternativo y actualiza el principal (AltItem).
                    $resultadoId = $consultaAltItem->fetch(PDO::FETCH_ASSOC);
                    $idPrincipal = $resultadoId['id'];

                    // Inserta el ItemCode actual como nuevo alternativo
                    $insertAlternativo = $base_de_datos->prepare("
                        INSERT INTO filtro_alternativo_sap (id_codigo, codigo_alt, act_sap) 
                        VALUES (:id_codigo, :codigo_alt, 'Y')
                    ");
                    $insertAlternativo->execute([':id_codigo' => $idPrincipal, ':codigo_alt' => $codigo]);
                    $cambios_filtro_alternativo++;

                    // Actualiza el registro principal usando el ID del AltItem
                    $updatePrincipal = $base_de_datos->prepare("
                        UPDATE filtro_codificacion 
                        SET 
                        descripcion = :descripcion, 
                        stock = :stock, 
                        precio = :precio, 
                        und_empaque = :undEmpaque,
                        codigo_barra = :codigoBarra,
                        act_sap = 'N'
                        WHERE id = :idPrincipal
                    ");
                    $updatePrincipal->execute(compact('descripcion', 'stock', 'precio', 'undEmpaque', 'codigoBarra', 'idPrincipal'));
                    $cambios_filtro_codificacion++;
                    $fue_procesado = true;
                    
                } else {
                    //  Busca codigo_catalogo (del JSON) en filtro_codificacion.codigo
                    if (!empty($codigoCatalogo)) {
                        // Solo busca si el campo del JSON no está vacío
                        $consultaCatalogo = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :codigoCatalogo AND deleted_at IS NULL");
                        $consultaCatalogo->bindParam(':codigoCatalogo', $codigoCatalogo);
                        $consultaCatalogo->execute();

                        if ($consultaCatalogo->rowCount() > 0) {
                            // Actualiza el registro encontrado por codigo_catalogo
                            $resultadoCatalogo = $consultaCatalogo->fetch(PDO::FETCH_ASSOC);
                            $idPrincipal = $resultadoCatalogo['id'];

                            // Actualiza los campos en filtro_codificacion
                            $updatePrincipal = $base_de_datos->prepare("
                                UPDATE filtro_codificacion 
                                SET 
                                descripcion = :descripcion, 
                                stock = :stock, 
                                precio = :precio, 
                                und_empaque = :undEmpaque,
                                codigo_barra = :codigoBarra,
                                act_sap = 'N'
                                WHERE id = :idPrincipal
                            ");
                            $updatePrincipal->execute(compact('descripcion', 'stock', 'precio', 'undEmpaque', 'codigoBarra', 'idPrincipal'));
                            $cambios_filtro_codificacion++;

                            //Inserta el ItemCode actual como nuevo alternativo
                            $insertAlternativo = $base_de_datos->prepare("
                                INSERT INTO filtro_alternativo_sap (id_codigo, codigo_alt, act_sap) 
                                VALUES (:id_codigo, :codigo_alt, 'Y')
                            ");
                            $insertAlternativo->execute([':id_codigo' => $idPrincipal, ':codigo_alt' => $codigo]);
                            $cambios_filtro_alternativo++;
                            $fue_procesado = true;
                        }
                    } 
                    
                    // Si $fue_procesado aún es false  va a la lista de no encontrados
                    if (!$fue_procesado) {
                        // Código No Encontrado (Se añade a la lista para el chequeo NANO)
                        $no_encontrados_itemcode[] = [
                            'ItemCode' => $codigo, 
                            'ItemName' => $descripcion,
                            'Disponible' => $stock,
                            'Price' => $precio,
                            'SalPackUn' => $undEmpaque,
                            'CodeBars' => $codigoBarra,
                            'AltItem' => $itemAlterno,
                            'CodigoLimpio' => $limpioCodigo 
                        ];
                        if (!empty($itemAlterno)) {
                            $no_encontrados_altitem[] = $itemAlterno;
                        }
                    } 
                } 
            } 
        } 
    } 

    
   // Inserción Final para Códigos "NANO"
    $clase_valor = 'aireindustrial';
    $fecha_actualiza = date('d-m-Y'); 
    $sincronizado = date('Ymd');
    $deleted_at = date('Y-m-d H:i:s');
    

    $insertCodificacionNano = $base_de_datos->prepare("
        INSERT INTO filtro_codificacion
        (
            codigo, codigo_buscar, descripcion, stock, precio, und_empaque, codigo_barra, act_sap,
            clase, fecha_actualiza, sincronizado, deleted_at
        )
        VALUES
        (
            :codigo, :codigo_buscar, :descripcion, :stock, :precio, :undEmpaque, :codigoBarra, 'Y',
            :clase, :fecha_actualiza, :sincronizado, :deleted_at
        )
    ");

    // Iterar sobre los elementos que NO se encontraron en los pasos A, B, C y D.
    for ($i = count($no_encontrados_itemcode) - 1; $i >= 0; $i--) {
        $fila = $no_encontrados_itemcode[$i];
        $codigo = $fila['ItemCode'];
        $itemAlterno = $fila['AltItem'];
        
        // **Condición clave:** Solo procede si contiene "NANO" en ItemCode o AltItem.
        if (stripos((string)$codigo, 'NANO') !== false || stripos((string)$itemAlterno, 'NANO') !== false) {
            
            // Doble chequeo de existencia antes de insertar para evitar duplicados
            $consultaExistencia = $base_de_datos->prepare("SELECT id FROM filtro_codificacion WHERE codigo = :codigo AND deleted_at IS NULL");
            $consultaExistencia->bindParam(':codigo', $codigo);
            $consultaExistencia->execute();
            
            if ($consultaExistencia->rowCount() == 0) {
                // Ejecutar la inserción
                $insertCodificacionNano->execute([
                    ':codigo' => $fila['ItemCode'],
                    ':codigo_buscar' => $fila['CodigoLimpio'],
                    ':descripcion' => $fila['ItemName'],
                    ':stock' => $fila['Disponible'],
                    ':precio' => $fila['Price'],
                    ':undEmpaque' => $fila['SalPackUn'],
                    ':codigoBarra' => $fila['CodeBars'],
                    ':clase' => $clase_valor,
                    ':fecha_actualiza' => $fecha_actualiza,
                    ':sincronizado' => $sincronizado,
                    ':deleted_at' => $deleted_at, 
                ]);

                $cambios_filtro_codificacion++; 
                $nuevos_nano_insertados_codificacion[] = $codigo;
                
                // Remover de la lista de no encontrados para que no aparezca en el resumen final
                unset($no_encontrados_itemcode[$i]);
            }
        }
    }
    // Reindexar el array después de eliminar elementos
    $no_encontrados_itemcode = array_values($no_encontrados_itemcode);


} catch (Exception $e) {
    // Manejo de errores
    echo "Error al procesar el archivo JSON o la base de datos: " . $e->getMessage();
    exit();
}


## Resumen Final de la Sincronización

$cantidad_no_encontrados_itemcode = count($no_encontrados_itemcode);
$cantidad_nuevos_nano_insertados_codificacion = count($nuevos_nano_insertados_codificacion);

echo "Resumen de la sincronización.<br><br>";

if ($cambios_filtro_codificacion > 0) {
    echo "Se **actualizaron/insertaron $cambios_filtro_codificacion** registros en la tabla (**filtro_codificacion**).<br>";
} else {
    echo "No se encontraron registros para actualizar o insertar en la tabla (**filtro_codificacion**).<br>";
}

if ($cambios_filtro_alternativo > 0) {
    echo "Se **actualizaron/insertaron $cambios_filtro_alternativo** registros en la tabla (**filtro_alternativo_sap**).<br>";
} else {
    echo "No se encontraron registros para actualizar o insertar en la tabla (**filtro_alternativo_sap**).<br>";
}

echo "<br>--- Resumen de Inserciones NANO ---<br>";

if ($cantidad_nuevos_nano_insertados_codificacion > 0) {
    echo "Se **insertaron $cantidad_nuevos_nano_insertados_codificacion nuevos** registros 'NANO' en (**filtro_codificacion**).<br>";
    echo "Códigos 'NANO' insertados:<br>" . implode("<br>", $nuevos_nano_insertados_codificacion) . "<br>";
} else {
    echo "No se insertaron nuevos registros 'NANO'.<br>";
}

echo "<br>--- Códigos No Procesados (Ignorados) ---<br>";

if ($cantidad_no_encontrados_itemcode > 0) {
    // Se muestran solo los ItemCode de los que quedaron sin procesar (los que no eran NANO)
    $codigos_no_encontrados_para_mostrar = array_column($no_encontrados_itemcode, 'ItemCode');
    echo "Se encontraron **" . $cantidad_no_encontrados_itemcode . " (ItemCode)** que no tuvieron coincidencia y **NO eran 'NANO'**.<br>";
    echo "Lista de ItemCode no encontrados:<br>" . implode("<br>", $codigos_no_encontrados_para_mostrar) . "<br>";
} else {
    echo "Todos los (**ItemCode**) restantes del archivo JSON fueron procesados con éxito o eran 'NANO' y se insertaron.<br>";
}
echo "<br>Fin de la sincronización.";

?>