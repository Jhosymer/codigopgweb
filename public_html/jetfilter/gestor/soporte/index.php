<?php 
    $loc = "../../../";
    $locj = "./../../";
    $title = "Soporte";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    alerta_nuevo();
    alerta_actualizado('Ticket Soporte ha sido actualizado');
    alerta_eliminado("Ticket Soporte se ha eliminado correctamente");

    // Contar cuántos tickets tienen visto_admin = 'N'
$stmt_nuevos_count = $base_de_datos->query("SELECT COUNT(*) FROM ticket_soporte WHERE visto_admin = 'N'");
$total_nuevos = $stmt_nuevos_count->fetchColumn();
?>

<div class='container py-4 px-lg-5 mt-5'>
    <h1 class="titulo text-center mb-5">Ticket Soporte</h1>
    <div class='bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm border'>
        
        <!--copiar form-->

        <div class="col-12 content">
            <form action="index.php?pag=soporte" method="post" class="mb-4">
                <div class="row g-2 justify-content-center" id="btnTenRec">
                            <div class="col-6 col-md-2"><button type="submit" class="btn text-white fw-bold w-100 py-2 border-0" name="N" style="background-color: #007bff;">Nuevos <span class="badge bg-white text-primary ms-2"><?php  echo $total_nuevos;?> </span> </button></div>
                            <div class="col-6 col-md-2"><button type="submit" class="btn text-white fw-bold w-100 py-2 border-0" name="A" style="background-color: #1abc9c;">Abiertos</button></div>
                            <div class="col-6 col-md-2"><button type="submit" class="btn text-white fw-bold w-100 py-2 border-0" name="R" style="background-color: #f39c12;">En Revisión</button></div>
                            <div class="col-6 col-md-2"><button type="submit" class="btn text-white fw-bold w-100 py-2 border-0" name="C" style="background-color: #d63031;">Cerrado</button></div>
                            <div class="col-6 col-md-2"><button type="submit" class="btn text-white fw-bold w-100 py-2 border-0" name="T" style="background-color: #636e72;">Todos</button></div>
                </div> 
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="example" width="100%">
                    <thead >
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Nro. Ticket</th>
                            <th>Asunto</th>
                            <th> Producto Relacionado</th>
                            <th>Soporte</th>
                            <th class="text-center">Estado</th>
                            <th>Fecha</th>
                            <th>Ticket SAP</th>
                            <th class="text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $contador = 1;
$base_query = "SELECT ts.*, 
                      t.nombre AS tipo_soporte_nombre, 
                      u.name AS user_name,
                      sap.num_tick_sap AS sap_numero_real,
                      fc.codigo AS producto_codigo
               FROM ticket_soporte ts
               JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
               JOIN users u ON ts.id_user = u.id
               LEFT JOIN tickt_soporte_sap sap ON ts.id = sap.id_soporte
                LEFT JOIN filtro_codificacion fc ON ts.id_producto = fc.id";

// 2. Aplicamos los filtros manteniendo la nueva estructura
$orden = "ORDER BY ts.id DESC";

if(isset($_POST['C'])) { 
    $wsqli = "$base_query WHERE ts.stado ='C' $orden"; 
} else if(isset($_POST['R'])) { 
    $wsqli = "$base_query WHERE ts.stado = 'R' $orden"; 
} else if(isset($_POST['T'])) { 
    $wsqli = "$base_query $orden"; 
} else { 
    // Aquí puedes decidir: ¿Si pulsan "Nuevos" filtrar solo los 'N'?
    if(isset($_POST['N'])) {
        $wsqli = "$base_query WHERE ts.visto_admin = 'N' $orden";
    } else {
        $wsqli = "$base_query WHERE ts.stado ='A' $orden"; 
    }
}

$result = $base_de_datos->query($wsqli);

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id'];
    $stado_raw = $row['stado'];
    
    // 3. Ahora usamos el alias 'sap_numero_real' que viene de la tabla unida
    $sap_num = !empty($row['sap_numero_real']) ? $row['sap_numero_real'] : 'Sin asignar';
    
    $color_bg = "#636e72"; $st_texto = "---";
    if ($stado_raw == 'A') { $color_bg = "#1abc9c"; $st_texto = "Abierto"; }
    elseif ($stado_raw == 'R') { $color_bg = "#f39c12"; $st_texto = "En Revisión"; }
    elseif ($stado_raw == 'C') { $color_bg = "#d63031"; $st_texto = "Cerrado"; }
?>
<tr>
    <td><?php echo $contador++; ?></td>
    <td class="fw-bold"><?php echo $row['user_name']; ?></td>
    <td><span class="fw-bold"><?php echo $id; ?></span></td>
    <td><small><?php echo $row['asunto']; ?></small></td>
   <td>
    <small>
        <?php if (!empty($row['producto_codigo'])): ?>
            <span class="badge bg-secondary mb-2">
                Codigo: <?php echo $row['producto_codigo']; ?> 
            </span>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </small>
</td>

    
    
    <td><span class="badge bg-light text-muted border fw-normal"><?php echo $row['tipo_soporte_nombre']; ?></span></td>
    <td><span class="badge w-100 py-2" style="background-color: <?php echo $color_bg; ?>;"><?php echo $st_texto; ?></span></td>
    <td><small><?php echo date('d/m/Y', strtotime($row['fecha_creado'])); ?></small></td>
    <td><span class="badge bg-light text-dark border"><i class="bx bx-barcode-reader me-1"></i><?php echo $sap_num; ?></span></td>
    <td class="text-center"> 
       <button type="button" 
        class="btn btn-primary" 
        data-bs-toggle="modal" 
        data-bs-target="#editarTicketModal-<?php echo $row['id']; ?>">
    <i class="bx <?php echo ($stado_raw == 'C') ? 'bx-search' : 'bx-edit'; ?>"></i>
</button>
    </td>
</tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/menutables.js"></script>

<?php   
    include("modal_editar_ticket.php");
    include("../index/footer.php");
?>


<script>
//Logica para marcar como visto al ABRIR el modal
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', function () {
        const ticketId = this.id.replace('editarTicketModal-', '');
        
        fetch('./actualizar_estado.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_visto=' + ticketId
        })
        .then(response => response.text())
        .then(data => {
           // console.log('Respuesta del servidor:', data);
        })
        .catch(error => console.error('Error en fetch:', error));
    });

    // Logica para recargar la pagina al CERRAR el modal
    modal.addEventListener('hidden.bs.modal', function () {
        window.location.reload();
    });
});
</script>
