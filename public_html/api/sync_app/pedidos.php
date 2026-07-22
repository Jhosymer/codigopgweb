<?php
require_once('./../clases/conexion/conexion.php');
$conexion = new conexion();
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['pedidos'])) {
    die(json_encode(['status' => 'error', 'message' => 'JSON inválido']));
}

$pedidosProcesados = [];

foreach ($data['pedidos'] as $pedido) {
    $id_raw = $pedido['id'];
    $es_temporal = (strpos((string)$id_raw, '-') !== false || $id_raw == '0');
    $edi = $pedido['edi'];
    $stat = $pedido['stat'];

    // 1. PROCESAR ENCABEZADO
    if ($edi == 'agg' || $es_temporal) {

        $valor_fecha_sap = "NULL";
        $sql = "INSERT INTO pedidos (id_users, na_pedido, fecha_sap, fecha, total_pedido, numero_oc, stat, origen) 
                VALUES ('{$pedido['id_users']}', '{$pedido['na_pedido']}', $valor_fecha_sap, '{$pedido['fecha']}', '{$pedido['total_pedido']}', '{$pedido['numero_oc']}', '$stat', '{$pedido['origen']}')";
        $new_id = $conexion->nonQueryId($sql);
        $id_efectivo = $new_id;
        $pedido['id'] = (string)$new_id;
    } else {
        $id_efectivo = (int)$id_raw;
        if ($edi == 'modificado') {
            $conexion->nonQuery("UPDATE pedidos SET total_pedido = '{$pedido['total_pedido']}', numero_oc = '{$pedido['numero_oc']}', stat = '$stat' WHERE id = $id_efectivo");
        } elseif ($edi == 'eliminado') {
            $conexion->nonQuery("DELETE FROM lista_pedidos WHERE id_pedido = $id_efectivo");
            $conexion->nonQuery("DELETE FROM pedidos WHERE id = $id_efectivo");
            continue;
        }  
    }




    // 2. PROCESAR DETALLES
    $procesados = [];
    if (!empty($pedido['detalles'])) {
        foreach ($pedido['detalles'] as $item) {
            $edi_det = $item['edi'];
            if ($edi_det == 'agg') {
                $sql_ins = "INSERT INTO lista_pedidos (id_pedido, id_producto, cantidad, precio_u, total, cancel) 
                            VALUES ($id_efectivo, '{$item['id_producto']}', '{$item['cantidad']}', '{$item['precio_u']}', '{$item['total']}', '{$item['cancel']}')";
                $item['id_detalle'] = (string)$conexion->nonQueryId($sql_ins);
                $item['edi'] = 'original';
                $procesados[] = $item;
            } elseif ($edi_det == 'modificado') {
                $conexion->nonQuery("UPDATE lista_pedidos SET cantidad = '{$item['cantidad']}', total = '{$item['total']}' WHERE id = '{$item['id_detalle']}'");
                $item['edi'] = 'original';
                $procesados[] = $item;
            } elseif ($edi_det == 'eliminado') {
                if (strpos((string)$item['id_detalle'], '-') === false) {
                    $conexion->nonQuery("DELETE FROM lista_pedidos WHERE id = '{$item['id_detalle']}'");
                }
            } else {
                $procesados[] = $item;
            }
        }
    }
    
    $sum = $conexion->obtenerDatos("SELECT SUM(total) as total FROM lista_pedidos WHERE id_pedido = $id_efectivo");
    $total_final = ($sum && isset($sum[0]['total'])) ? $sum[0]['total'] : 0;
    $conexion->nonQuery("UPDATE pedidos SET total_pedido = $total_final WHERE id = $id_efectivo");

   // ... después de actualizar el total en la base de datos

   if ($stat == 'C' && ($edi == 'agg' || $edi == 'modificado')) {
        $path_a_accion = __DIR__ . '/pedido_envio_accion.php';
        
        if (file_exists($path_a_accion)) {
            require_once $path_a_accion;
            try {
                enviarCorreoPedido($id_efectivo, $conexion); 
                $pedido['confirmacion_enviada'] = true;
            } catch (Throwable $e) {
                $pedido['confirmacion_enviada'] = false;
                $pedido['error_correo'] = $e->getMessage();
            }
        }
    }
if ($edi == 'agg' || $edi == 'modificado' || !empty($procesados)) {
        $pedido['detalles'] = $procesados;
        $pedido['edi'] = 'original';
    }
    
    // Si no hubo cambios, el $pedido se agrega tal cual llegó, 
    // sin inyectarle los campos extra.
    $pedidosProcesados[] = $pedido;
    
}

echo json_encode(['status' => 'success', 'pedidos_sincronizados' => $pedidosProcesados]);
?>