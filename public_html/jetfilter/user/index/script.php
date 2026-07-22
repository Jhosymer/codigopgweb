<script src="./../../js/js_vende/jquery-3.7.1.js?t=<?php echo $rann; ?>"></script>
<script src="./../../js/js_vende/dataTables.js?t=<?php echo $rann; ?>"></script>
<script src="./../../js/js_vende/dataTables.bootstrap5.js?t=<?php echo $rann; ?>"></script>
<script src="./../../js/js_vende/menutables.js?t=<?php echo $rann; ?>"></script>
<script src="./../../js/js_vende/script_orden_compra.js?t=<?php echo $rann; ?>"></script>

<?php 
// Función para imprimir scripts de forma limpia
function cargarScript($archivo, $rann) {
    echo '<script src="./../../js/js_vende/' . $archivo . '?t=' . $rann . '"></script>';
}

// Scripts condicionales según la página
if (isset($_GET['pag'])) {
    switch ($_GET['pag']) {
        case 'soporte':
            cargarScript('script_soporte.js', $rann);
            break;
        case 'pedido':
            cargarScript('calculoporprecios.js', $rann);
            break;
        case 'disponibilidad':
            cargarScript('buscardisponibilidad.js', $rann);
            cargarScript('mesnajedisp.js', $rann); // Este era el que faltaba
            break;
    }
}
?>