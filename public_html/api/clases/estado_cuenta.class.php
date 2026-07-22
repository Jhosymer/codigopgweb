<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class estado_cuenta extends conexion {

    public function obtenerEstadoCuenta($id_users) {
        $id_users = trim($id_users);
        
    
        $query = "SELECT s.*, f.id as id_factura_web 
                  FROM sap_estado_cuenta s
                  LEFT JOIN factura f ON s.documento = f.num_fact AND s.id_users = f.id_users
                  WHERE s.id_users = '$id_users' AND s.saldo_vencido != 0
                  ORDER BY s.fecha ASC";

        $datos = parent::obtenerDatos($query);
        
        if (!$datos) {
            return [];
        }

        foreach ($datos as $key => $fila) {
            if (isset($fila['monto'])) $datos[$key]['monto'] = (float)$fila['monto'];
            if (isset($fila['saldo_vencido'])) $datos[$key]['saldo_vencido'] = (float)$fila['saldo_vencido'];
        }

        return $datos;
    }
}
?>