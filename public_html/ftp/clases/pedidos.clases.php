<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pedidos extends conexion {
    private $table = "pedidos";
    private $table2 = "users";
    private $table3 = "lista_pedidos";
    private $table4 = "filtro_codificacion";
    private $table5 = "filtro_alternativo_sap";

    public function listarPedidosConDetalles() {
        // La consulta está bien, pero aseguramos los índices en los campos del JOIN
        $query = "SELECT
            T0.id AS pedido_id, T0.na_pedido, T0.fecha, T5.rif AS rif_usuario,
            T3.cantidad, T3.precio_u,
            COALESCE(
                (SELECT IF(T4.act_sap='Y', T4.codigo, NULL) FROM filtro_codificacion T4 WHERE T4.id = T3.id_producto LIMIT 1),
                (SELECT IF(T6.act_sap='Y', T6.codigo_alt, NULL) FROM filtro_alternativo_sap T6 WHERE T6.id_codigo = T3.id_producto LIMIT 1)
            ) AS codigo_producto
        FROM {$this->table} T0
        INNER JOIN {$this->table2} T5 ON T0.id_users = T5.id
        INNER JOIN {$this->table3} T3 ON T3.id_pedido = T0.id
        WHERE (T0.na_pedido IS NULL OR T0.na_pedido = '') AND T0.stat = 'C'
        ORDER BY T0.id, T3.id";

        $datos = parent::obtenerDatos($query);

        $resultados = [];
        foreach ($datos as $fila) {
            $pedidoId = $fila['pedido_id'];
            if (!isset($resultados[$pedidoId])) {
                $resultados[$pedidoId] = [
                    "id" => $fila['pedido_id'],
                    "CardCode" => $fila['rif_usuario'],
                    "DocDueDate" => $fila['fecha'],
                    "Comments" => "Pedido Web #" . $fila['pedido_id'],
                    "DocumentLines" => []
                ];
            }
            // Solo agregamos si el código fue encontrado
            if ($fila['codigo_producto']) {
                $resultados[$pedidoId]["DocumentLines"][] = [
                    "ItemCode" => $fila['codigo_producto'],
                    "Quantity" => (int)$fila['cantidad'],
                    "WhsCode" => "03"
                ];
            }
        }
        return array_values($resultados);
    }
}