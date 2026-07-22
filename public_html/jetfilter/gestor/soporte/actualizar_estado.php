<?php
session_start();
include_once('../../../config/conexion.php');
if (!$base_de_datos) {
    die("Error: No hay conexión a la base de datos");
}
if (isset($_POST['id_visto'])) {
    $id = $_POST['id_visto'];
    
    try {
        // Asegúrate de que el nombre de la tabla y la columna existan EXACTAMENTE así
        $stmt = $base_de_datos->prepare("UPDATE ticket_soporte SET visto_admin = 'S' WHERE id = :id");
        $resultado = $stmt->execute([':id' => $id]);
        
        // Verifica cuántas filas fueron afectadas
        if ($stmt->rowCount() > 0) {
            echo "SUCCESS: Fila actualizada";
        } else {
            echo "AVISO: No se encontró el ID " . $id . " o ya estaba marcado";
        }
    } catch (PDOException $e) {
        echo "ERROR SQL: " . $e->getMessage();
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $nuevo_estado = $_POST['nuevo_estado'];
    $detalle_revision = $_POST['detalle_revision'] ?: "Sin Detalle";
    $fecha_actual = date('Y-m-d H:i:s');

    // Datos SAP desde el formulario
    $usar_sap = $_POST['usar_sap'] ?? 'no';
    $asunto_sap = $_POST['asunto_sap'] ?? null;
    $prioridad_sap = $_POST['prioridad_sap'] ?? 'Medio';
    $tipo_sap = $_POST['tipo_problema_sap'] ?? 'Reclamos de Filtros';
    $comentario_sap = $_POST['comentario_sap'] ?? null;

    // 1. Manejar Archivo de Evidencia
    $anexo_r = null;

    if (isset($_POST['id_visto'])) {
    $id = $_POST['id_visto'];
    $stmt = $base_de_datos->prepare("UPDATE ticket_soporte SET visto_admin = 'S' WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    // Solo enviamos una respuesta limpia
    echo "SUCCESS";
    exit; 
}
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $fileExtension = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        $allowed = array('pdf', 'jpg', 'jpeg', 'png');

        if (in_array($fileExtension, $allowed)) {
            $uploadDir = './../../img/soporte/revision/';
            $fileName = $ticket_id . '_' . time() . '.' . $fileExtension; 
            if(move_uploaded_file($_FILES['archivo']['tmp_name'], $uploadDir . $fileName)) {
                $anexo_r = $fileName;
            }
        }
    }

    // 2. Transacción para asegurar integridad de ambas tablas
    try {
        $base_de_datos->beginTransaction();

        // --- PARTE A: Actualizar Tabla ticket_soporte ---
        $sql = "UPDATE ticket_soporte SET stado = :st, detalle_revision = :dt, visto_cliente = 'N'";
        $params = [':st' => $nuevo_estado, ':dt' => $detalle_revision, ':tid' => $ticket_id];

        if ($nuevo_estado == 'A') {
            $sql .= ", fecha_revision = NULL, fecha_cerrado = NULL";
        } elseif ($nuevo_estado == 'R') {
            $sql .= ", fecha_revision = :f, fecha_cerrado = NULL";
            $params[':f'] = $fecha_actual;
        } elseif ($nuevo_estado == 'C') {
            // Nota: Aquí validamos si ya tiene fecha de revisión previa
            $sql .= ", fecha_revision = IFNULL(fecha_revision, :f), fecha_cerrado = :f";
            $params[':f'] = $fecha_actual;
        }

        if ($anexo_r) {
            $sql .= ", anexo_r = :img";
            $params[':img'] = $anexo_r;
        }

        $sql .= " WHERE id = :tid";
        $stmt = $base_de_datos->prepare($sql);
        $stmt->execute($params);

        // --- PARTE B: Manejar Tabla tickt_soporte_sap ---
        if ($usar_sap === 'si') {
            // Verificar si ya existe en la tabla SAP
            $checkSap = $base_de_datos->prepare("SELECT id FROM tickt_soporte_sap WHERE id_soporte = ?");
            $checkSap->execute([$ticket_id]);
            
            if ($checkSap->rowCount() > 0) {
                // Actualizar Registro SAP existente
                $sqlSap = "UPDATE tickt_soporte_sap SET 
                            asunto = :asu, 
                            prioridad = :pri, 
                            tipo = :tip, 
                            comentario_interno = :com 
                           WHERE id_soporte = :tid";
            } else {
                // Insertar Nuevo Registro SAP (num_tick_sap nace vacío por defecto en la DB)
                $sqlSap = "INSERT INTO tickt_soporte_sap (id_soporte, asunto, prioridad, tipo, comentario_interno) 
                           VALUES (:tid, :asu, :pri, :tip, :com)";
            }
            
            $stmtSap = $base_de_datos->prepare($sqlSap);
            $stmtSap->execute([
                ':asu' => $asunto_sap,
                ':pri' => $prioridad_sap,
                ':tip' => $tipo_sap,
                ':com' => $comentario_sap,
                ':tid' => $ticket_id
            ]);
        }

        $base_de_datos->commit();
        $_SESSION['actualizado'] = true;

    } catch (Exception $e) {
        $base_de_datos->rollBack();
        $_SESSION['error'] = true;
        // Opcional: registrar el error para debugging
        // error_log($e->getMessage());
    }
}

header("Location: index.php");
exit();
?>