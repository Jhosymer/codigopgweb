<?php
require_once 'config/conexion.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar el cuerpo de la solicitud JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Obtener los valores de marca y código
    $codigo = $input['codigo'] ?? '';
    $marca = $input['marca'] ?? '';

    // Validar que el código no esté vacío
    if (empty($codigo)) {
        echo json_encode([
            'success' => false,
            'message' => 'El código no puede estar vacío.'
        ]);
        exit;
    }

    $caracteres_a_reemplazar = ['-', " ", "_", '/', '%', '*', ',', '.'];
    $codigo_buscar = str_replace($caracteres_a_reemplazar, '', $codigo);
    $codigo_preparado = "%$codigo_buscar%";

    try {
        $id_marca = null;

        // Si se proporciona la marca, buscar su ID
        if (!empty($marca)) {
            $sql = "SELECT id FROM equivalencia_marca WHERE (marca = :marca) AND (deleted_at IS NULL)";
            $coincidencia_marca = $base_de_datos->prepare($sql);
            $coincidencia_marca->bindParam(":marca", $marca, PDO::PARAM_STR);
            $coincidencia_marca->setFetchMode(PDO::FETCH_ASSOC);
            $coincidencia_marca->execute();
            $resultado_marca = $coincidencia_marca->fetch();

            if ($resultado_marca) {
                $id_marca = $resultado_marca['id'];
            }
        }

        // Búsqueda de coincidencias exactas
        if ($id_marca) {
            $sql = "SELECT fe.codigo_marca AS codigo_marca, 
                    fe.codigo AS codigo, 
                    fe.marca AS marca, 
                    fc.clase AS clase
                    FROM filtro_equivalencia fe
                    INNER JOIN filtro_codificacion fc ON fe.codigo = fc.codigo
                    WHERE (fe.id_marca = :id_marca)  
                    AND (fe.codigo_marca = :codigo OR fe.codigo_marca_buscar = :codigo)  
                    AND (fe.deleted_at IS NULL AND fc.deleted_at IS NULL);";
            $consulta = $base_de_datos->prepare($sql);
            $consulta->bindParam(":id_marca", $id_marca, PDO::PARAM_INT);
            $consulta->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        } else {
            $sql = "SELECT fe.codigo_marca AS codigo_marca,
                fe.codigo AS codigo,
                fe.marca AS marca,
                fc.clase clase
            FROM filtro_equivalencia fe
            INNER JOIN filtro_codificacion fc ON fe.codigo = fc.codigo
            WHERE (fe.codigo_marca = :codigo OR fe.codigo_marca_buscar = :codigo) 
            AND (fe.deleted_at IS NULL AND fc.deleted_at IS NULL);";
            $consulta = $base_de_datos->prepare($sql);
            $consulta->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        }

        $consulta->setFetchMode(PDO::FETCH_ASSOC);
        $consulta->execute();
        $resultados_exactos = $consulta->fetchAll();

        // Búsqueda de coincidencias parciales
        if ($id_marca) {
            $sql = "SELECT fe.codigo_marca AS codigo_marca,
                    fe.codigo AS codigo,
                    fe.marca AS marca,
                    fc.clase clase
                FROM filtro_equivalencia fe
                INNER JOIN filtro_codificacion fc ON fe.codigo = fc.codigo
                WHERE (fe.id_marca = :id_marca) 
                AND (fe.codigo_marca LIKE :codigo_preparado OR fe.codigo_marca_buscar LIKE :codigo_preparado) 
                AND (fe.codigo_marca != :codigo AND fe.codigo_marca_buscar != :codigo) 
                AND (fe.deleted_at IS NULL AND fc.deleted_at IS NULL);";
            $consulta_parcial = $base_de_datos->prepare($sql);
            $consulta_parcial->bindParam(":id_marca", $id_marca, PDO::PARAM_INT);
            $consulta_parcial->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
            $consulta_parcial->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        } else {
            $sql = "SELECT fe.codigo_marca AS codigo_marca,
                    fe.codigo AS codigo,
                    fe.marca AS marca,
                    fc.clase clase
                FROM filtro_equivalencia fe
                INNER JOIN filtro_codificacion fc ON fe.codigo = fc.codigo
                WHERE (fe.codigo_marca LIKE :codigo_preparado OR fe.codigo_marca_buscar LIKE :codigo_preparado) 
                AND (fe.codigo_marca != :codigo AND fe.codigo_marca_buscar != :codigo) 
                AND (fe.deleted_at IS NULL AND fc.deleted_at IS NULL);";
            $consulta_parcial = $base_de_datos->prepare($sql);
            $consulta_parcial->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
            $consulta_parcial->bindParam(":codigo", $codigo, PDO::PARAM_STR);
        }

        $consulta_parcial->setFetchMode(PDO::FETCH_ASSOC);
        $consulta_parcial->execute();
        $resultados_parciales = $consulta_parcial->fetchAll();

        // Combinar resultados
        $equivalencias = array_merge($resultados_exactos, $resultados_parciales);

        // Verificar si hay resultados
        if (empty($equivalencias)) {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontraron equivalencias para el código proporcionado.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'equivalencias' => $equivalencias
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error al realizar la búsqueda: ' . $e->getMessage()
        ]);
    }
}
?>