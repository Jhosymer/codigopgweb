<?php

require_once($loc . "config/conexion.php");
$id_usuario_actual = $_SESSION['id'];
$permisos_usuario = [];

try {
    $sql_permisos = "SELECT id_permiso_admin FROM users_permiso_admin WHERE id_users = ?";
    $stmt_permisos = $base_de_datos->prepare($sql_permisos);
    $stmt_permisos->execute([$id_usuario_actual]);
    
    while ($row_permiso = $stmt_permisos->fetch(PDO::FETCH_ASSOC)) {
        // Almacenar los IDs de los permisos en un array
        $permisos_usuario[] = $row_permiso['id_permiso_admin'];
    }
} catch (PDOException $e) {
    // Manejo de errores si la consulta falla
    die("Error al obtener permisos: " . $e->getMessage());
}


?>