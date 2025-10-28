<?php
$wsqli_ver_tik = "SELECT ts.*, t.nombre AS tipo_soporte_nombre
FROM ticket_soporte ts
JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
WHERE ts.id_user = '$id_users'";

$result_ver_tik = $linki->query($wsqli_ver_tik);
if ($linki->errno) die($linki->error);

while ($row_ver_tik = $result_ver_tik->fetch_array()) {
    $id = $row_ver_tik['id']; 
    $stado = $row_ver_tik['stado'];
    if ($stado == 'A') {
        $stado = 'Abierto';
    } elseif ($stado == 'R') {
        $stado = 'En Revisión';
    } elseif ($stado == 'C') {
        $stado = 'Cerrado';
    }
    $fecha_creado = $row_ver_tik['fecha_creado'];
    $fecha_revision = $row_ver_tik['fecha_revision'];
    $fecha_cerrado = $row_ver_tik['fecha_cerrado'];
    $tipo = $row_ver_tik['tipo_soporte_nombre'];
    $anexo = $row_ver_tik['anexo'];
    $detalle = $row_ver_tik['detalle'];
    $anexo_r = $row_ver_tik['anexo_r'];

    if ($detalle === null || $detalle === '') {
    $detalle = "Sin Detalle";
}
    $detalle_revision = $row_ver_tik['detalle_revision'];
?>
<div class="modal fade" id="verTicketModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="verTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo-ms" id="verTicketModalLabel">Ticket Soporte </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <strong class="subtito_ms me-2">Nro Ticket:</strong> <span class="parrafo_md"><?php echo $id; ?></span>
                    </div>
                    <div class="col">
                         <strong class="subtito_ms me-2">Estado:</strong><span class="parrafo_md"> <?php echo $stado; ?></span>
                    </div>
                </div>

                 <div class="row mb-3">
                    <div class="col">
                      <strong class="subtito_ms me-2">Tipo de Soporte:</strong><span class="parrafo_md"> <?php echo $tipo; ?></span>
                    </div>
                    <div class="col">
                        <strong class="subtito_ms me-2">Fecha Creación:</strong><span class="parrafo_md"> <?php echo $fecha_creado; ?></span>
                    </div>
                </div>

                <div class="stats-progress progress mb-3" style="height:3px"></div>
                
                <div class="mb-3">
                    <strong class="subtito_ms me-2">Anexo:</strong> 
                    <?php if (!empty($anexo)) { ?>
                        <button class="btn btn-primary " type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexo-<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAnexo-<?php echo $id; ?>">
                            Ver
                        </button>
                    <?php } else { ?>
                       <span class="parrafo_md"> Sin anexo cargado</span>
                    <?php } ?>
                </div>
                <div class="collapse" id="collapseAnexo-<?php echo $id; ?>">
                    <div class="card card-body">
                        <?php if (strpos($anexo, '.pdf') !== false) { ?>
                            <iframe src="./../img/soporte/<?php echo $anexo; ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                        <?php } else { ?>
                            <img src="./../img/soporte/<?php echo $anexo; ?>" class="img-fluid" alt="Anexo">
                        <?php } ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong class="subtito_ms">Detalle</strong>
                    <div class="p-3 alert alert-secondary mt-3 mb-4">
                        <span class="parrafo_md"><?php echo nl2br(htmlspecialchars($detalle)); ?></span>
                    </div>
                </div>
                

                <?php if (!empty($fecha_revision) || !empty($fecha_cerrado) || !empty($detalle_revision)) { ?>
                    <div class="stats-progress progress mb-3" style="height:3px"></div>
                 <div class="row mb-3">
                    <div class="col">
                      <?php if (!empty($fecha_revision) ) { ?>
                        <strong class="subtito_ms me-2">Fecha Revisión:</strong><span class="parrafo_md"><?php echo $fecha_revision; ?></span>
                      <?php } ?>
                    </div>
                    <div class="col">
                        <?php if (!empty($fecha_cerrado)) { ?>
                          <strong class="subtito_ms me-2">Fecha Cerrado:</strong><span class="parrafo_md"> <?php echo $fecha_cerrado; ?></span>
                        <?php } ?>
                    </div>
                </div>

                    <div class="mt-3">
                        <?php if (!empty($detalle_revision)) { ?>
                            <strong class="subtito_ms">Detalle de Revisión</strong>
                            <div class="p-3 alert alert-secondary mt-2">
                               <span class="parrafo_md"> <?php echo nl2br(htmlspecialchars($detalle_revision)); ?></span>
                            </div>
                        <?php } ?>
                    </div>
                <?php } 
                
                if (!empty($anexo_r)) { ?>
                    <div class="mb-3">
                        <strong class="subtito_ms">Anexo de Revisión</strong>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexoRevision-<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAnexoRevision-<?php echo $id; ?>">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <div class="collapse" id="collapseAnexoRevision-<?php echo $id; ?>">
                        <div class="card card-body">
                            <?php if (strpos($anexo_r, '.pdf') !== false) { ?>
                                <iframe src="./../img/soporte/revision/<?php echo $anexo_r; ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                            <?php } else { ?>
                                <img src="./../img/soporte/revision/<?php echo $anexo_r; ?>" class="img-fluid" alt="Anexo de Revisión">
                            <?php } ?>
                        </div>
                    </div>
                   
                <?php } ?>

                <!-- Collapse para mostrar el anexo -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>



<?php
}
?>
