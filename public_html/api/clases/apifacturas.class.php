<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class facturas extends conexion {

    public function listarFacturasPorUsuario($id_user) {
        $id_user = trim($id_user);

        $query = "SELECT
            T0.id AS factura_id, T0.id_users, T0.num_fact, T0.num_control, T0.fecha_contab, 
            T0.fecha_venc, T0.valor_Moneda, T0.Moneda, T0.iva, T0.porcentaje_iva, 
            T0.porcentaje_descuento, T0.descuento, T0.sub_total, T0.total_fact, T0.visto, T0.nota_credito,
            T5.rif AS usuario_rif, T5.name AS usuario_nombre,
            T3.id AS detalle_id, T3.id_producto, T3.na_pedido, T3.precio AS precio_item, 
            T3.cantidad AS cantidad_item, T3.total AS subtotal_item,
            T4.codigo AS producto_codigo, T4.descripcion AS producto_descripcion
        FROM factura T0
        LEFT JOIN users T5 ON T0.id_users = T5.id
        LEFT JOIN lista_factura T3 ON T3.id_factura = T0.id
        LEFT JOIN filtro_codificacion T4 ON T3.id_producto = T4.id
        WHERE T0.id_users = '$id_user' 
        ORDER BY T0.id DESC"; 

        $datos = parent::obtenerDatos($query);
        if (!$datos) return [];

        $resultados = [];
        foreach ($datos as $fila) {
            $facturaId = $fila['factura_id'];
            if (!isset($resultados[$facturaId])) {
                $resultados[$facturaId] = [
                    "id" => $fila['factura_id'],
                    "id_users" => $fila['id_users'],
                    "rif" => $fila['usuario_rif'] ?? '',
                    "nombre_cliente" => $fila['usuario_nombre'] ?? '',
                    "num_fact" => $fila['num_fact'] ?? '',
                    "num_control" => $fila['num_control'] ?? '',
                    "fecha_contab" => $fila['fecha_contab'] ?? '',
                    "fecha_venc" => $fila['fecha_venc'] ?? '',
                    "valor_moneda" => (float)($fila['valor_Moneda'] ?? 0),
                    "moneda" => $fila['Moneda'] ?? '',
                    "iva" => (float)($fila['iva'] ?? 0),
                    "porcentaje_iva" => (float)($fila['porcentaje_iva'] ?? 0),
                    "porcentaje_descuento" => (float)($fila['porcentaje_descuento'] ?? 0),
                    "descuento" => (float)($fila['descuento'] ?? 0),
                    "sub_total" => (float)($fila['sub_total'] ?? 0),
                    "total_fact" => (float)($fila['total_fact'] ?? 0),
                    "visto" => $fila['visto'] ?? 'N',
                    "nota_credito" => $fila['nota_credito'] ? (int)$fila['nota_credito'] : null,
                    "DocumentLines" => []
                ];
            }
            if (!empty($fila['id_producto'])) {
                $resultados[$facturaId]["DocumentLines"][] = [
                    "id_detalle" => $fila['detalle_id'],
                    "id_producto" => $fila['id_producto'],
                    "codigo" => $fila['producto_codigo'] ?? '',
                    "descripcion" => $fila['producto_descripcion'] ?? '',
                    "na_pedido" => $fila['na_pedido'] ?? '',
                    "cantidad" => (int)($fila['cantidad_item'] ?? 0),
                    "precio" => (float)($fila['precio_item'] ?? 0),
                    "total" => (float)($fila['subtotal_item'] ?? 0)
                ];
            }
        }
        return array_values($resultados);
    }
}
?>