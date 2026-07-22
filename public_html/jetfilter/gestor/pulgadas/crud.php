<?php
include_once('../../../config/conexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = !empty($_POST['id']) ? intval($_POST['id']) : null;
    $entrada_usuario = trim($_POST['codigo']);
    $valor_nominal = $_POST['valor_nominal'];

    // 1. Transformación para el GUARDADO: Convertimos cualquier punto en coma
    // Si el usuario envió "m12 x 1.5", se convierte en "m12 x 1,5"
    $codigo_para_guardar = str_replace('.', ',', $entrada_usuario);

    //Solo unifica separadores decimales (puntos por comas) 
// y quita espacios DOBLES, pero mantiene el espacio sencillo.
$codigo_critico = str_replace(',', '.', $entrada_usuario); // Unificamos a punto para validar
$codigo_critico = preg_replace('/\s+/', ' ', trim($codigo_critico)); // Colapsa múltiples espacios en uno solo
    try {
        // Buscamos si ya existe un código similar en la base de datos
        $check_sql = "SELECT id FROM pulgadas 
                      WHERE REPLACE(REPLACE(REPLACE(codigo, ' ', ''), ',', ''), '.', '') = :codigo_critico 
                      AND deleted_at IS NULL";
        
        if ($id) { $check_sql .= " AND id != :id"; }

        $check_stmt = $base_de_datos->prepare($check_sql);
        $check_stmt->bindParam(':codigo_critico', $codigo_critico);
        if ($id) $check_stmt->bindParam(':id', $id);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $_SESSION["yaExistencia"] = true; 
            header("Location: ./index.php?status=error_duplicado");
            exit();
        }

        // 3. Operación de Base de Datos usando $codigo_para_guardar (con comas)
        if ($id) {
            $sql = "UPDATE pulgadas SET codigo = :codigo, valor_nominal = :valor, updated_at = NOW() WHERE id = :id";
            $stmt = $base_de_datos->prepare($sql);
            $stmt->bindParam(':id', $id);
            $status = "actualizado";
        } else {
            $sql = "INSERT INTO pulgadas (codigo, valor_nominal, updated_at, deleted_at) VALUES (:codigo, :valor, NOW(), NULL)";
            $stmt = $base_de_datos->prepare($sql);
            $status = "nuevo";
        }

        $stmt->bindParam(':codigo', $codigo_para_guardar);
        $stmt->bindParam(':valor', $valor_nominal);

        if ($stmt->execute()) {
            $_SESSION[$status] = true; 
            header("Location: ./index.php?status=$status");
            exit();
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}