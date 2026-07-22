<?php
require_once("./../../../config/conex.php"); 

$id_users = $_GET['id_users'] ?? '';
// Recibimos la fecha y hora pasadas por URL
$display_fecha = $_GET['fecha'] ?? date('d/m/Y');
$display_hora = $_GET['hora'] ?? date('H:i:s');

if (empty($id_users)) { die("ID de cliente no especificado."); }

$sql = "SELECT s.*, f.id as id_factura_web 
        FROM sap_estado_cuenta s
        LEFT JOIN factura f ON s.documento = f.num_fact AND s.id_users = f.id_users
        WHERE s.id_users = '$id_users' AND s.saldo_vencido != 0
        ORDER BY s.fecha ASC";

$result = $linki->query($sql);

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=Estado_Cuenta_" . $id_users . "_" . date('d-m-Y') . ".xls");
echo "\xEF\xBB\xBF"; 
?>
<table border="1">
    <thead>
        <tr style="background-color: #f2f2f2; font-weight: bold;">
            <th>FECHA</th>
            <th>DOCUMENTO</th>
            <th>ESTADO</th>
            <th>INFO DETALLADA</th>
            <th>MONTO (ME)</th>
            <th>SALDO VENCIDO (ME)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total_saldo = 0;
        while ($m = $result->fetch_assoc()): 
            // ... (tu lógica de estados sigue igual) ...
            $fecha_cont = new DateTime($m['fecha']);
            $dias = (new DateTime())->diff($fecha_cont)->days;
            $estado_texto = ($dias > 30) ? "Vencida" : "Vigente";
            $color_estado = ($dias > 30) ? "#dc3545" : "#198754";
            $total_saldo += $m['saldo_vencido'];
            
            $celda_estado = $estado_texto . "&#10;(" . $dias . " días)";
        ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($m['fecha'])); ?></td>
                <td><?php echo $m['documento']; ?></td>
                <td style="white-space: pre-wrap; text-align: center; color: <?php echo $color_estado; ?>; font-weight: bold;">
                    <?php echo $celda_estado; ?>
                </td>
                <td><?php echo $m['detalle']; ?></td>
                <td style="text-align: right; mso-number-format:'0\.000000';">
                    <?php echo number_format($m['monto'], 6, ',', '.'); ?>
                </td>
                <td style="text-align: right; color: #dc3545; font-weight: bold; mso-number-format:'0\.000000';">
                    <?php echo number_format($m['saldo_vencido'], 6, ',', '.'); ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td colspan="5" style="text-align: right;">SALDO TOTAL A PAGAR:</td>
            <td style="text-align: right; mso-number-format:'0\.000000';">
                <?php echo number_format(abs($total_saldo), 6, ',', '.'); ?>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: right; font-size: 10pt; color: #6c757d; border: none;">
                Estado de Cuenta &nbsp;&nbsp; 
                <strong>Fecha:</strong> <?php echo $display_fecha; ?> | 
                <strong>Tiempo:</strong> <?php echo $display_hora; ?>
            </td>
        </tr>
    </tfoot>
</table>