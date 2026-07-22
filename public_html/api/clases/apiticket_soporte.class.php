<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class ticket_soporte extends conexion {
    private $table = "ticket_soporte";

    public function listarTicketsPorUsuario($id_user) {
        $id_user = (int)$id_user; 
        
        // Consulta todos los campos de la tabla
     $query = "SELECT t.*, f.codigo, ts.nombre AS nombre_soporte, tss.num_tick_sap 
          FROM " . $this->table . " AS t 
          INNER JOIN filtro_codificacion AS f ON t.id_producto = f.id 
          INNER JOIN tipo_soporte AS ts ON t.id_tp_soporte = ts.id 
          LEFT JOIN tickt_soporte_sap AS tss ON tss.id_soporte = t.id 
          WHERE t.id_user = $id_user 
          ORDER BY t.id DESC";

        $datos = parent::obtenerDatos($query);

        if (!$datos) {
            return [];
        }

        return $datos;
    }
}
?>