<?php
session_start();
include_once "../../../config/conexion.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id_factura = $_POST['id'] ?? null;
    $nota_credito = trim($_POST['nota_credito'] ?? '');

    if ($nota_credito === '') {
        $nota_credito = null;
    }

    if ($id_factura) {
        try {
            $base_de_datos->beginTransaction();

            // Preparamos el UPDATE
            $sql = "UPDATE factura SET nota_credito = :nc WHERE id = :id";
            $stmt = $base_de_datos->prepare($sql);
            
            $stmt->execute([
                ':nc' => $nota_credito,
                ':id' => $id_factura
            ]);

            $base_de_datos->commit();
            
            // Guardamos el éxito en la sesión
            $_SESSION['actualizado'] = true;

        } catch (Exception $e) {
            $base_de_datos->rollBack();
            $_SESSION['error'] = true;
        }
    }
}

// Redirección limpia
header("Location: /jetfilter/gestor/factura/");
exit();