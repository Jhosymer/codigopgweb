<?php
$wsqli_ver_tik = "SELECT ts.*, t.nombre AS tipo_soporte_nombre
FROM ticket_soporte ts
JOIN tipo_soporte t ON ts.id_tp_soporte = t.id";
$result_ver_tik = $base_de_datos->query($wsqli_ver_tik);
if ($base_de_datos->errorCode() !== '00000') {
    $errorInfo = $base_de_datos->errorInfo();
    die("Query failed: " . $errorInfo[2]);
}

while ($row_ver_tik = $result_ver_tik->fetch(PDO::FETCH_ASSOC)) {
    $id = $row_ver_tik['id']; 
    $stado = $row_ver_tik['stado'];
    $stado_texto = ($stado == 'A') ? 'Abierto' : (($stado == 'R') ? 'En Revisión' : 'Cerrado');
    $fecha_creado = $row_ver_tik['fecha_creado'];
    $fecha_revision = $row_ver_tik['fecha_revision'];
    $fecha_cerrado = $row_ver_tik['fecha_cerrado'];
    $tipo = $row_ver_tik['tipo_soporte_nombre'];
    $anexo = $row_ver_tik['anexo'];
    $detalle = $row_ver_tik['detalle'];
    $detalle_revision = $row_ver_tik['detalle_revision'];
    $anexo_r = $row_ver_tik['anexo_r']; // Suponiendo que este es el campo para el anexo de revisión

    if ($detalle === null || $detalle === '') {
    $detalle = "Sin Detalle";
         }

         if ($detalle_revision === null || $detalle_revision === '') {
    $detalle_revision = "Sin Detalle";
         }
?>

<div class="modal fade" id="editarTicketModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="editarTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo-ms" id="editarTicketModalLabel">Ticket Soporte </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col">
                        <strong class="subtito_ms me-2">Nro Ticket:</strong> <span class="parrafo_md"><?php echo $id; ?></span>
                    </div>
                    <div class="col">
                        <strong class="subtito_ms me-2">Estado:</strong>
                      <form method="POST" action="actualizar_estado.php" enctype="multipart/form-data">
                            <input type="hidden" name="ticket_id" value="<?php echo $id; ?>">
                            <select name="nuevo_estado" class="form-select d-inline" style="display: inline-block; width: auto;">
                                <option value="A" <?php echo ($stado == 'A') ? 'selected' : ''; ?>>Abierto</option>
                                <option value="R" <?php echo ($stado == 'R') ? 'selected' : ''; ?>>En Revisión</option>
                                <option value="C" <?php echo ($stado == 'C') ? 'selected' : ''; ?>>Cerrado</option>
                            </select>
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
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexo-<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAnexo-<?php echo $id; ?>">
                            <i class="bx bx-search"></i>
                        </button>
                        
                    <?php } else { ?>
                        <span class="parrafo_md"> Sin anexo cargado</span>
                    <?php } ?>
                </div>
                <div class="collapse" id="collapseAnexo-<?php echo $id; ?>">
                    <div class="card card-body">
                        <?php if (strpos($anexo, '.pdf') !== false) { ?>
                            <iframe src="./../../img/soporte/<?php echo $anexo; ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                        <?php } else { ?>
                            <img src="./../../img/soporte/<?php echo $anexo; ?>" class="img-fluid" alt="Anexo">
                        <?php } ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong class="subtito_ms">Detalle</strong>
                    <div class="p-3 alert alert-secondary mt-3 mb-4">
                        <span class="parrafo_md"><?php echo nl2br(htmlspecialchars($detalle)); ?></span>
                    </div>
                </div>

               

                <?php if (!empty($fecha_revision) || !empty($fecha_cerrado) || !empty($stado)) { ?>
                    <div class="stats-progress progress mb-3" style="height:3px"></div>
                    <div class="row mb-3">
                        <div class="col">
                            <?php if (!empty($fecha_revision)) { ?>
                                <strong class="subtito_ms me-2">Fecha Revisión:</strong><span class="parrafo_md"><?php echo $fecha_revision; ?></span>
                            <?php } ?>
                        </div>
                        <div class="col">
                            <?php if (!empty($fecha_cerrado)) { ?>
                                <strong class="subtito_ms me-2">Fecha Cerrado:</strong><span class="parrafo_md"> <?php echo $fecha_cerrado; ?></span>
                            <?php } ?>
                        </div>
                    </div>
                     <div class="mb-3">
                        
                    <strong class="subtito_ms">Detalle de Revisión</strong>
                    <?php if ($stado == 'C'): ?>
                        <div class="p-3 alert alert-secondary mt-3 mb-4">
                            <span class="parrafo_md"><?php echo nl2br(htmlspecialchars($detalle_revision)); ?></span>
                        </div>
                    <?php else: ?>
                        <textarea name="detalle_revision" class="form-control" rows="4"><?php echo htmlspecialchars($detalle_revision); ?></textarea>
                    <?php endif; ?>
                </div>

                

                <!-- Mostrar anexo de revisión si existe -->
                <?php if (!empty($anexo_r)) { ?>
                    <div class="mb-3">
                        <strong class="subtito_ms">Anexo de Revisión</strong>
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAnexoRevision-<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAnexoRevision-<?php echo $id; ?>">
                            <i class="bx bx-search"></i>
                        </button>
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#collapsesubirAnexoRevision-<?php echo $id; ?>" aria-expanded="false" aria-controls="collapseAnexoRevision-<?php echo $id; ?>">
                            <i class="bx bx-edit"></i>
                        </button>
                    </div>
                     <div class="collapse" id="collapsesubirAnexoRevision-<?php echo $id; ?>">
                        <div class="mb-3">
                            <label for="archivo" class="form-label subtito_ms">Subir Archivo Revisión</label>
                            <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                    <div class="collapse" id="collapseAnexoRevision-<?php echo $id; ?>">
                        <div class="card card-body">
                            <?php if (strpos($anexo_r, '.pdf') !== false) { ?>
                                <iframe src="./../../img/soporte/revision/<?php echo $anexo_r; ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                            <?php } else { ?>
                                <img src="./../../img/soporte/revision/<?php echo $anexo_r; ?>" class="img-fluid" alt="Anexo de Revisión">
                            <?php } ?>
                        </div>
                    </div>
                   
                   


                <?php }  else  {?>
                    <!-- Campo para subir archivo -->
                     
                <div class="mb-3">
                    <label for="archivo" class="form-label subtito_ms">Subir Archivo Revisión</label>
                    <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <?php }} ?>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php
}
?>
