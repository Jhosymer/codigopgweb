<?php

require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pedidos extends conexion {
    private $table = "pedidos";
    private $table2 = "users";
    private $table3 = "lista_pedidos";
    private $table4 = "filtro_codificacion";
    private $table5 = "filtro_alternativo_sap";

    // Método para listar pedidos con detalles y estructura JSON deseada
    public function listarPedidosConDetalles() {
       $query = "SELECT
            T0.id AS pedido_id,
            T0.na_pedido,
            T0.fecha,
            T5.rif AS rif_usuario,
            T3.cantidad,
            T3.precio_u,
            CASE
                WHEN T4.act_sap = 'Y' THEN T4.codigo
                WHEN T4.act_sap = 'N' AND T6.act_sap = 'Y' THEN T6.codigo_alt
                ELSE T4.codigo
            END AS codigo_producto
        FROM " . $this->table . " T0
        INNER JOIN " . $this->table2 . " T5 ON T0.id_users = T5.id
        INNER JOIN " . $this->table3 . " T3 ON T3.id_pedido = T0.id
        INNER JOIN " . $this->table4 . " T4 ON T3.id_producto = T4.id
        LEFT JOIN " . $this->table5 . " T6 ON T3.id_producto = T6.id_codigo
        WHERE (T0.na_pedido IS NULL OR T0.na_pedido = '') 
        AND T0.stat = 'C'
        ORDER BY T0.id, T3.id";


        $datos = parent::obtenerDatos($query);

        // Agrupar datos para formar la estructura JSON deseada
        $resultados = [];
        foreach ($datos as $fila) {
            $pedidoId = $fila['pedido_id'];

            if (!isset($resultados[$pedidoId])) {
                $resultados[$pedidoId] = [
                    "id" => $fila['pedido_id'],
                    "CardCode" => $fila['rif_usuario'],
                    "DocDueDate" => $fila['fecha'],
                    "Comments" => "Pedido Web",
                    "DocumentLines" => []
                ];
            }

            $resultados[$pedidoId]["DocumentLines"][] = [
                "ItemCode" => $fila['codigo_producto'],
                "Quantity" => (int)$fila['cantidad'],
               // "Price" => (float)$fila['precio_u']
            ];
        }

        // Retornar array de pedidos con detalles
        return array_values($resultados);
    }

    // Puedes mantener otros métodos como listarpedidos() si quieres
    public function listarpedidos() {
        $_respuestas = new respuestas;

        $query = "SELECT
                    T1.id,
                    T1.na_pedido,
                    T2.rif AS rif_usuario
                  FROM " . $this->table . " T1
                  INNER JOIN " . $this->table2 . " T2 ON T1.id_users = T2.id
                  WHERE T1.na_pedido IS NULL OR T1.na_pedido = ''";

        $datos = parent::obtenerDatos($query);
        return $datos;
    }
}

?>
