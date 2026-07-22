<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pedidos extends conexion {
    public function listarPedidosPorUsuario($id_user) {
        $id_user = trim($id_user);
        
        $query = "SELECT
            T0.id AS pedido_id, T0.id_users, T0.na_pedido, T0.fecha_sap, T0.fecha, T0.total_pedido,
            T0.numero_oc, T0.stat, T0.origen,
            T5.rif AS usuario_rif, T5.name AS usuario_nombre,
            T3.id AS detalle_id, T3.id_producto, T3.cantidad, T3.precio_u, T3.total AS subtotal_item, T3.cancel AS item_cancelado,
            T4.codigo AS producto_codigo, T4.descripcion AS producto_descripcion
        FROM pedidos T0
        LEFT JOIN users T5 ON T0.id_users = T5.id
        LEFT JOIN lista_pedidos T3 ON T3.id_pedido = T0.id
        LEFT JOIN filtro_codificacion T4 ON T3.id_producto = T4.id
        WHERE T0.id_users = '$id_user' 
        ORDER BY T0.id DESC"; 

        $datos = parent::obtenerDatos($query);
        if (!$datos) return [];

        $resultados = [];
        foreach ($datos as $fila) {
            $pedidoId = $fila['pedido_id'];
            if (!isset($resultados[$pedidoId])) {
                $resultados[$pedidoId] = [
                    "id" => $fila['pedido_id'],
                    "id_users" => $fila['id_users'],
                    "rif" => $fila['usuario_rif'] ?? '',
                    "nombre_cliente" => $fila['usuario_nombre'] ?? '',
                    "na_pedido" => $fila['na_pedido'] ?? '',
                    "fecha_sap" => $fila['fecha_sap'] ?? '',
                    "fecha" => $fila['fecha'] ?? '',
                    "total_pedido" => (float)($fila['total_pedido'] ?? 0),
                    "numero_oc" => $fila['numero_oc'] ?? '',
                    "stat" => $fila['stat'] ?? '',
                    "origen" => $fila['origen'] ?? '',
                    "DocumentLines" => []
                ];
            }
            if (!empty($fila['id_producto'])) {
                $resultados[$pedidoId]["DocumentLines"][] = [
                    "id_detalle" => $fila['detalle_id'],
                    "id_producto" => $fila['id_producto'],
                    "codigo" => $fila['producto_codigo'] ?? '',
                    "descripcion" => $fila['producto_descripcion'] ?? '',
                    "cantidad" => (int)($fila['cantidad'] ?? 0),
                    "precio_u" => (float)($fila['precio_u'] ?? 0),
                    "total" => (float)($fila['subtotal_item'] ?? 0),
                    "cancel" => $fila['item_cancelado'] ?? '0'
                ];
            }
        }
        return array_values($resultados);
    }
}