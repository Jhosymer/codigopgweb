<?php
// 1. Consulta con LEFT JOIN para traer el número de ticket SAP
$wsqli_ver_tik = "SELECT ts.*, t.nombre AS tipo_soporte_nombre, 
                         sap.num_tick_sap AS sap_numero,
                         fc.codigo AS producto_nombre
                  FROM ticket_soporte ts
                  JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                  LEFT JOIN tickt_soporte_sap sap ON ts.id = sap.id_soporte
                  LEFT JOIN filtro_codificacion fc ON ts.id_producto = fc.id
                  WHERE ts.id_user = '$id_users'";

$result_ver_tik = $linki->query($wsqli_ver_tik);
if ($linki->errno) die($linki->error);

while ($row_ver_tik = $result_ver_tik->fetch_array()) {
    $id = $row_ver_tik['id']; 
    $stado_raw = $row_ver_tik['stado'];
    $num_sap = $row_ver_tik['sap_numero']; // El número de SAP
    $id_tp_soporte = $row_ver_tik['id_tp_soporte'];
    $producto_nombre = $row_ver_tik['producto_nombre'];
    // Configuración de Badge de Estado
    $badge_class = 'bg-secondary';
    $stado_texto = 'Desconocido';
    
    if ($stado_raw == 'A') {
        $stado_texto = 'Abierto';
        $badge_class = 'bg-success';
    } elseif ($stado_raw == 'R') {
        $stado_texto = 'En Revisión';
        $badge_class = 'bg-warning text-dark';
    } elseif ($stado_raw == 'C') {
        $stado_texto = 'Cerrado';
        $badge_class = 'bg-danger';
    }

    $fecha_creado = $row_ver_tik['fecha_creado'];
    $fecha_revision = $row_ver_tik['fecha_revision'];
    $fecha_cerrado = $row_ver_tik['fecha_cerrado'];
    $tipo = $row_ver_tik['tipo_soporte_nombre'];
    $anexo = $row_ver_tik['anexo'];
    $detalle = $row_ver_tik['detalle'] ?: "Sin Detalle";
    $anexo_r = $row_ver_tik['anexo_r'];
    $detalle_revision = $row_ver_tik['detalle_revision'];
?>

<div class="modal fade" id="verTicketModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="verTicketModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            
            <div class="modal-header header-custom-red p-3">
                <h5 class="modal-title titulo_bold_ms text-white" id="verTicketModalLabel">
                    <i class='bx bx-search-alt-2'></i> Detalle del Ticket #<?php echo $id; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-white">
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
    <label class="label-caps mb-1 d-block text-muted small uppercase">Estado Actual</label>
    
    <span class="badge <?php echo $badge_class; ?> p-2 px-3 w-100 fs-6 shadow-sm mb-1">
        <?php echo $stado_texto; ?>
    </span>

    <?php if (!empty($fecha_cerrado)): ?>
        <div class="text-center">
            <small class="text-danger fw-bold" style="font-size: 0.7rem;">
                <i class='bx bx-calendar-check'></i> 
                Cerrado el: <?php echo date("d/m/Y", strtotime($fecha_cerrado)); ?>
            </small>
        </div>
    <?php endif; ?>
</div>
                    <div class="col-md-3">
                        <label class="label-caps mb-1 d-block">Categoría</label>
                        <div class="p-2 border rounded bg-light fw-bold text-dark text-center"><?php echo $tipo; ?>
                        <?php if ($id_tp_soporte == 1 && !empty($producto_nombre)): ?>
                        <div class="small text-primary mt-1 border-top pt-1">
                            <?php echo $producto_nombre; ?>
                        </div>
                        <?php endif; ?>
                    
                    </div>
                    </div>
                    <div class="col-md-3">
                        <label class="label-caps mb-1 d-block">Fecha de Creación</label>
                        <div class="p-2 border rounded bg-light text-muted text-center">
                            <i class='bx bx-calendar-event'></i> <?php echo date("d/m/Y", strtotime($fecha_creado)); ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="label-caps mb-1 d-block">Ticket SAP</label>
                        <div class="p-2 border rounded bg-light text-muted text-center fw-bold">
                            <?php echo (!empty($num_sap)) ? $num_sap : 'N/A'; ?>
                        </div>
                    </div>
                </div>

                <hr class="opacity-50">

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="label-caps titulo_bold_ms text-muted m-0">Descripción del Requerimiento</label>
                        <?php if (!empty($anexo)): ?>
                            <button class="btn btn-sm btn-outline-primary fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexo-<?php echo $id; ?>">
                                <i class='bx bx-paperclip'></i> Ver Evidencia Cliente
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="p-3 rounded-3 border bg-light shadow-sm">
                        <p class="parrafo_md mb-0 text-dark"><?php echo nl2br(htmlspecialchars($detalle)); ?></p>
                    </div>
                    
                    <div class="collapse mt-2" id="collapseAnexo-<?php echo $id; ?>">
                        <div class="card card-body border-primary-subtle bg-light shadow-sm">
                            <?php if (strpos($anexo, '.pdf') !== false): ?>
                                <iframe src="./../img/soporte/<?php echo $anexo; ?>" style="width: 100%; height: 350px;" frameborder="0"></iframe>
                            <?php else: ?>
                                <img src="./../img/soporte/<?php echo $anexo; ?>" class="img-fluid rounded mx-auto d-block" style="max-height: 400px;">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if (!empty($detalle_revision) || !empty($anexo_r)): ?>
                    <div class="p-3 rounded-3 border-start border-4" style="background-color: #f0f7ff; border-left-color: #0d6efd !important;">
                        <label class="label-caps mb-2 d-block text-primary titulo_bold_ms">Respuesta del Equipo Técnico</label>
                        
                        <div class="row mb-3">
                            <?php if (!empty($fecha_revision)): ?>
                                <div class="col-6 small text-muted">
                                    <strong>Revisado el:</strong> <?php echo date("d/m/Y", strtotime($fecha_revision)); ?>
                                </div>
                            <?php endif; ?>
                            
                        </div>

                        <p class="parrafo_md text-dark mb-3"><?php echo nl2br(htmlspecialchars($detalle_revision)); ?></p>

                        <?php if (!empty($anexo_r)): ?>
                            <button class="btn btn-primary btn-sm fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexoRevision-<?php echo $id; ?>">
                                <i class='bx bx-search'></i> Ver Evidencia Técnica
                            </button>
                            <div class="collapse mt-2" id="collapseAnexoRevision-<?php echo $id; ?>">
                                <div class="card card-body bg-white border-0 shadow-sm p-1">
                                    <?php if (strpos($anexo_r, '.pdf') !== false): ?>
                                        <iframe src="./../img/soporte/revision/<?php echo $anexo_r; ?>" style="width: 100%; height: 350px;" frameborder="0"></iframe>
                                    <?php else: ?>
                                        <img src="./../img/soporte/revision/<?php echo $anexo_r; ?>" class="img-fluid rounded mx-auto d-block" style="max-height: 400px;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

            <div class="modal-footer bg-light border-0 px-4">
                <button type="button" class="btn btn-secondary px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Cerrar Vista</button>
            </div>
        </div>
    </div>
</div>

<?php } ?>