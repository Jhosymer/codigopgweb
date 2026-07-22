<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class pagos extends conexion {
    private $table = "pagos";
    private $table_detalle = "pagos_detalle";

    public function listarPagosPorUsuario($id_user) {
        // FUERZA: Convertimos explícitamente a entero para evitar problemas de tipos
        $id_user = (int)$id_user; 
        
        $query = "SELECT
            T0.id AS pago_id,
            T0.id_users,
            T0.num_pago_sap,
            T0.sap_reference_id,
            T0.fecha_pago,
            T0.referencia,
            T0.moneda,
            T0.tasa_cambio,
            T0.total_pago,
            T0.total_bs,
            T0.comentarios,
            T0.visto,
            T1.id AS detalle_id,
            T1.num_fact_externa,
            T1.num_control,
            T1.fecha_factura,
            T1.comprobante_retencion,
            T1.tipo_doc,
            T1.plazo,
            T1.monto_retencion,
            T1.monto_aplicado,
            T1.monto_bs
        FROM pagos T0
        INNER JOIN pagos_detalle T1 ON T0.id = T1.id_pagos
        WHERE T0.id_users = $id_user
        ORDER BY T0.id DESC"; 

        $datos = parent::obtenerDatos($query);

        if (!$datos) {
            return [];
        }

        $resultados = [];
        foreach ($datos as $fila) {
            $pagoId = $fila['pago_id'];

            if (!isset($resultados[$pagoId])) {
                $resultados[$pagoId] = [
                    "id" => $fila['pago_id'],
                    "id_users" => $fila['id_users'],
                    "num_pago_sap" => $fila['num_pago_sap'] ?? '',
                    "sap_reference_id" => $fila['sap_reference_id'] ?? '',
                    "fecha_pago" => $fila['fecha_pago'] ?? '',
                    "referencia" => $fila['referencia'] ?? '',
                    "moneda" => $fila['moneda'] ?? '',
                    "tasa_change" => (float)($fila['tasa_cambio'] ?? 0),
                    "total_pago" => (float)($fila['total_pago'] ?? 0),
                    "total_bs" => (float)($fila['total_bs'] ?? 0),
                    "comentarios" => $fila['comentarios'] ?? '',
                    "visto" => $fila['visto'] ?? 'N',
                    "DocumentLines" => []
                ];
            }

            if (!empty($fila['detalle_id'])) {
                $resultados[$pagoId]["DocumentLines"][] = [
                    "id_detalle" => $fila['detalle_id'],
                    "num_fact_externa" => $fila['num_fact_externa'] ?? '',
                    "num_control" => $fila['num_control'] ?? '',
                    "fecha_factura" => $fila['fecha_factura'] ?? '',
                    "comprobante_retencion" => $fila['comprobante_retencion'] ?? '',
                    "tipo_doc" => $fila['tipo_doc'] ?? '',
                    "plazo" => $fila['plazo'] ?? '',
                    "monto_retencion" => (float)($fila['monto_retencion'] ?? 0),
                    "monto_aplicado" => (float)($fila['monto_aplicado'] ?? 0),
                    "monto_bs" => (float)($fila['monto_bs'] ?? 0)
                ];
            }
        }
        return array_values($resultados);
    }
}
?>