<?php
    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";

class filtro_codificacion extends conexion {
    
    private $table = "filtro_codificacion";

    /**
     * Inyecta de forma plana los cálculos dentro del mismo objeto de datos
     */
    private function procesarDatos($datos) {
        if (!$datos) return [];

        foreach ($datos as $key => $row) {
            $stock = 0;
            $act1 = $row['act_sap_c'] ?? 'N';
            $act2 = $row['act_sap_a'] ?? 'N';
            
            // Lógica unificada de inventario según permisos SAP
            if ($act1 == 'Y' && $act2 == 'Y') {
                $stock = (int)$row["stock1"] + (int)$row["stock2"];
            } else if ($act1 == 'Y') {
                $stock = (int)$row["stock1"];
            } else if ($act2 == 'Y') {
                $stock = (int)$row["stock2"];
            }

            // Mapeo lógico del semáforo de disponibilidad
            $meta = (int)($row['disponible_inmediata'] ?? 0);
            if ($meta <= 0) {
                $status = "info"; 
                $mensaje = "stock no configurado";
            } else {
                if ($stock <= ($meta * 0.10)) {
                    $status = "danger"; $mensaje = "Consulta con Ventas";
                } elseif ($stock <= ($meta * 0.30)) {
                    $status = "warning"; $mensaje = "Poca Disponibilidad";
                } else {
                    $status = "success"; $mensaje = "Disponibilidad Inmediata";
                }
            }

            // Anexar campos calculados al mismo nivel estructural del JSON
            $datos[$key]['stock_calculado'] = $stock;
            $datos[$key]['semaforo_status'] = $status;
            $datos[$key]['semaforo_mensaje'] = $mensaje;
        }
        return $datos;
    }

    public function listarfiltro_codificacion() {
        $query = "SELECT t1.*, 
                         t1.stock AS stock1, 
                         SUM(COALESCE(t2.stock, 0)) AS stock2, 
                         t1.act_sap AS act_sap_c, 
                         MAX(COALESCE(t2.act_sap, 'N')) AS act_sap_a
                  FROM " . $this->table . " t1
                  LEFT JOIN filtro_alternativo_sap t2 ON t1.id = t2.id_codigo
                  WHERE t1.deleted_at IS NULL 
                -- WHERE (t1.deleted_at IS NULL OR (t1.deleted_at IS NOT NULL AND t1.codigo LIKE '%NANO%'))
                  GROUP BY t1.id";
        
        return $this->procesarDatos(parent::obtenerDatos($query));
    }

    public function listarfiltro_codificacionId($id) {
        $query = "SELECT t1.*, t1.stock AS stock1, SUM(COALESCE(t2.stock, 0)) AS stock2, 
                         t1.act_sap AS act_sap_c, MAX(COALESCE(t2.act_sap, 'N')) AS act_sap_a
                  FROM " . $this->table . " t1
                  LEFT JOIN filtro_alternativo_sap t2 ON t1.id = t2.id_codigo
                  WHERE t1.id = '$id' AND t1.deleted_at IS NULL
                  GROUP BY t1.id";
        
        return $this->procesarDatos(parent::obtenerDatos($query));
    }

    public function listarfiltro_codificacionNombre($nombre) {
        $query = "SELECT t1.*, t1.stock AS stock1, SUM(COALESCE(t2.stock, 0)) AS stock2, 
                         t1.act_sap AS act_sap_c, MAX(COALESCE(t2.act_sap, 'N')) AS act_sap_a
                  FROM " . $this->table . " t1
                  LEFT JOIN filtro_alternativo_sap t2 ON t1.id = t2.id_codigo
                  WHERE t1.codigo LIKE '%$nombre%' AND t1.deleted_at IS NULL
                  GROUP BY t1.id";
        
        return $this->procesarDatos(parent::obtenerDatos($query));
    }

    public function listarfiltro_codificacionTotal() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted_at IS NULL";
        return parent::obtenerDatos($query);
    }
}
?>