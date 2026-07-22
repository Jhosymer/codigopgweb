<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class soporte extends conexion {
    
    public function listarTickets() {
        // Usamos COALESCE para traer el código válido de forma directa y eficiente
        $query = "SELECT 
                    ts.id AS ticket_id,
                    u.rif,
                    tss.tipo,
                    tss.comentario_interno AS comentario,
                    tss.prioridad,
                    tss.asunto,
                    COALESCE(fc.codigo, 'Sin código') AS codigo
                FROM ticket_soporte ts
                INNER JOIN users u ON ts.id_user = u.id
                INNER JOIN tickt_soporte_sap tss ON ts.id = tss.id_soporte
                LEFT JOIN filtro_codificacion fc ON ts.id_producto = fc.id AND fc.deleted_at IS NULL
                WHERE (tss.num_tick_sap IS NULL OR tss.num_tick_sap = '')";

        return parent::obtenerDatos($query);
    }
}
?>