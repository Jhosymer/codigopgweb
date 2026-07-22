<?php


$wsqli_ver_tik = "SELECT ts.*, u.rif, u.name, t.nombre AS tipo_soporte_nombre, 
                  sap.id AS sap_id, 
                  sap.asunto AS sap_asunto, 
                  sap.prioridad AS sap_prioridad, 
                  sap.tipo AS sap_tipo, 
                  sap.comentario_interno AS sap_comentario,
                  sap.num_tick_sap AS sap_numero,
                  fc.codigo AS producto_codigo
                  FROM ticket_soporte ts
                  JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                  JOIN users u ON ts.id_user = u.id
                  LEFT JOIN tickt_soporte_sap sap ON ts.id = sap.id_soporte
                  LEFT JOIN filtro_codificacion fc ON ts.id_producto = fc.id";

$result_ver_tik = $base_de_datos->query($wsqli_ver_tik);

while ($row_ver_tik = $result_ver_tik->fetch(PDO::FETCH_ASSOC)) {
    $id = $row_ver_tik['id']; 

    
    $stado = $row_ver_tik['stado'];
    $tipo = $row_ver_tik['tipo_soporte_nombre'];

    $producto_codigo = $row_ver_tik['producto_codigo'] ?? null;
    
    // Fechas dinámicas
    $f_creado = $row_ver_tik['fecha_creado']; 
    $f_revision = $row_ver_tik['fecha_revision'] ?? null;

    $detalle = $row_ver_tik['detalle'] ?: "Sin Detalle";
    $detalle_revision = ($row_ver_tik['detalle_revision'] == "Sin Detalle" || empty($row_ver_tik['detalle_revision'])) ? "" : $row_ver_tik['detalle_revision'];
    $anexo = $row_ver_tik['anexo'];
    $anexo_r = $row_ver_tik['anexo_r'];

    // BLOQUEO SAP
    $tiene_sap = !empty($row_ver_tik['sap_id']); 
    
    $asunto_sap = $row_ver_tik['sap_asunto'] ?? '';
    $prioridad_sap = $row_ver_tik['sap_prioridad'] ?? 'Medio';
    $tipo_sap = $row_ver_tik['sap_tipo'] ?? 'Reclamos de Filtros';
    $comentario_sap = $row_ver_tik['sap_comentario'] ?? '';
    $num_sap = $row_ver_tik['sap_numero'] ?? 'N/A';

    $activar_cliente = (!empty($detalle_revision) || !empty($anexo_r)) ? 'si' : 'no';
    $activar_sap = ($tiene_sap) ? 'si' : 'no';

    // Atributos de bloqueo
    $readonly_sap = ($tiene_sap) ? 'readonly' : '';
    $disabled_sap = ($tiene_sap) ? 'disabled' : '';
    $clase_bloqueo = ($tiene_sap) ? 'is-blocked' : '';
?>

<div class="modal fade" id="editarTicketModal-<?php echo $id; ?>" tabindex="-1" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom shadow-lg">
            
            <div class="modal-header header-red p-3">
                <h5 class="modal-title fw-bold text-white"><i class='bx bx-purchase-tag-alt text-white'></i> Ticket <?php echo $id; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form method="POST" action="actualizar_estado.php" enctype="multipart/form-data">
                <div class="modal-body p-4 bg-white">

                <div class="alert alert-secondary p-2 mb-4 shadow-sm">
        <small class="text-uppercase fw-bold text-muted d-block">Datos del Cliente:</small>
        <span class="fw-bold text-dark"><?php echo htmlspecialchars($row_ver_tik['name']); ?></span> | 
        <span class="text-secondary"><?php echo htmlspecialchars(ltrim($row_ver_tik['rif'], 'C-')); ?></span>
    </div>
                    <input type="hidden" name="ticket_id" value="<?php echo $id; ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-7 border-end">
                            <label class="label-caps mb-1 d-block">Tipo de Soporte / Registro</label>
                            <span class="fw-bold text-dark d-block mb-2"><?php echo $tipo; ?></span>
                            <?php if ($producto_codigo): ?>
        <span class="badge bg-secondary mb-2">
             Codigo: <?php echo htmlspecialchars($producto_codigo); ?>
        </span>
    <?php endif; ?>
                            <div class="fechas-container">
                                <div class="fecha-item"><span class="fecha-label">Creación:</span> <?php echo date("d/m/Y", strtotime($f_creado)); ?></div>
                                <?php if($f_revision): ?>
                                    <div class="fecha-item"><span class="fecha-label">Revisión:</span> <?php echo date("d/m/Y", strtotime($f_revision)); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-5 ps-md-4">
                            <label class="label-caps mb-2 d-block">Cambiar Estado Actual</label>
                            <select name="nuevo_estado" class="form-select fw-bold border-2 shadow-sm">
                                <option value="A" <?php echo ($stado == 'A') ? 'selected' : ''; ?>>Abierto</option>
                                <option value="R" <?php echo ($stado == 'R') ? 'selected' : ''; ?>>En Revisión</option>
                                <option value="C" <?php echo ($stado == 'C') ? 'selected' : ''; ?>>Cerrado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="label-caps m-0">Requerimiento del Cliente</label>
                            <?php if (!empty($anexo)): ?>
                                <button class="btn btn-sm btn-info py-0 px-2 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collAnexoC-<?php echo $id; ?>">
                                    <i class="bx bx-paperclip"></i> Ver adjunto
                                </button>
                            <?php endif; ?>
                        </div>
                        <textarea class="form-control bg-light text-muted" rows="2" readonly><?php echo htmlspecialchars($detalle); ?></textarea>
                        <div class="collapse mt-2" id="collAnexoC-<?php echo $id; ?>">
                            <div class="card card-body p-1 text-center bg-light border">
                                <img src="./../../img/soporte/<?php echo $anexo; ?>" class="img-fluid rounded mx-auto" style="max-height: 300px;">
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6 border-end">
                            <label class="label-caps mb-3 d-block text-center">Respuesta al Cliente</label>
                            <div class="btn-group w-100 mb-3">
                                <input type="radio" class="btn-check toggle-btn" name="usar_rev" id="revN-<?php echo $id; ?>" value="no" <?php echo ($activar_cliente == 'no') ? 'checked' : ''; ?> data-target="box-rev-<?php echo $id; ?>">
                                <label class="btn btn-outline-red-toggle" for="revN-<?php echo $id; ?>">No</label>

                                <input type="radio" class="btn-check toggle-btn" name="usar_rev" id="revS-<?php echo $id; ?>" value="si" <?php echo ($activar_cliente == 'si') ? 'checked' : ''; ?> data-target="box-rev-<?php echo $id; ?>">
                                <label class="btn btn-outline-red-toggle" for="revS-<?php echo $id; ?>">Sí, responder</label>
                            </div>

                            <div id="box-rev-<?php echo $id; ?>" style="<?php echo ($activar_cliente == 'si') ? '' : 'display:none;'; ?>">
                                <textarea name="detalle_revision" class="form-control mb-3" rows="3" placeholder="Redacte respuesta para el cliente..."><?php echo htmlspecialchars($detalle_revision); ?></textarea>
                                
                                <label class="label-caps small d-block mb-1">Evidencia Técnica (PDF, JPG, PNG)</label>
                                <?php if (!empty($anexo_r)): ?>
    <?php 
        // Obtenemos la extensión del archivo
        $ext = strtolower(pathinfo($anexo_r, PATHINFO_EXTENSION));
        $esPdf = ($ext === 'pdf');
    ?>

    <button class="btn btn-sm btn-info text-white mb-2 py-0 px-2" type="button" data-bs-toggle="collapse" data-bs-target="#collR-<?php echo $id; ?>">
        <i class="bx bx-search"></i> Ver <?php echo $esPdf ? 'PDF' : 'actual'; ?>
    </button>

    <div class="collapse mb-2" id="collR-<?php echo $id; ?>">
        <div class="card card-body p-1 border">
            <?php if ($esPdf): ?>
                <!-- Visualizador para PDF -->
                <embed src="./../../img/soporte/revision/<?php echo $anexo_r; ?>" 
                       type="application/pdf" 
                       width="100%" 
                       height="500px" />
            <?php else: ?>
                <!-- Visualizador para Imágenes -->
                <img src="./../../img/soporte/revision/<?php echo $anexo_r; ?>" class="img-fluid rounded">
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
                                <input type="file" class="form-control form-control-sm" name="archivo">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="label-caps mb-3 d-block text-center text-primary">Integración SAP </label>
                            
                            <div class="btn-group w-100 mb-3 <?php echo $clase_bloqueo; ?>">
                                <input type="radio" class="btn-check toggle-btn" name="usar_sap" id="sapN-<?php echo $id; ?>" value="no" <?php echo ($activar_sap == 'no') ? 'checked' : ''; ?> data-target="box-sap-<?php echo $id; ?>" <?php echo $disabled_sap; ?>>
                                <label class="btn btn-outline-blue-sap" for="sapN-<?php echo $id; ?>">No</label>

                                <input type="radio" class="btn-check toggle-btn" name="usar_sap" id="sapS-<?php echo $id; ?>" value="si" <?php echo ($activar_sap == 'si') ? 'checked' : ''; ?> data-target="box-sap-<?php echo $id; ?>" <?php echo $disabled_sap; ?>>
                                <label class="btn btn-outline-blue-sap" for="sapS-<?php echo $id; ?>">Sí, integrar</label>
                            </div>

                            <div id="box-sap-<?php echo $id; ?>" style="<?php echo ($activar_sap == 'si') ? '' : 'display:none;'; ?>" class="p-3 bg-sap-blue">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="sap-title m-0"><i class="bx bx-cog"></i> Datos Técnicos SAP</h6>
                                    <?php if($tiene_sap): ?>
                                        <span class="badge bg-primary">Ticket SAP <?php echo $num_sap; ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label class="small fw-bold opacity-75">Asunto Ticket SAP</label>
                                        <small class="text-muted" id="count-asunto-<?php echo $id; ?>">0/150</small>
                                    </div>
                                    <input type="text" name="asunto_sap" id="input-asunto-<?php echo $id; ?>"
                                           class="form-control form-control-sm sap-input <?php echo ($tiene_sap) ? 'is-blocked-input' : ''; ?>" 
                                           value="<?php echo htmlspecialchars($asunto_sap); ?>" 
                                           maxlength="150" onkeyup="actualizarContador(this, 'count-asunto-<?php echo $id; ?>')"
                                           <?php echo $readonly_sap; ?>>
                                </div>
                                
                                <div class="row g-2 mb-2">
                                    <div class="col-12">
                                        <label class="small fw-bold opacity-75">Prioridad</label>
                                        <select name="prioridad_sap" class="form-select form-select-sm sap-input <?php echo ($tiene_sap) ? 'is-blocked-input' : ''; ?>" <?php echo $disabled_sap; ?>>
                                            <option value="baja" <?php echo ($prioridad_sap == 'baja') ? 'selected' : ''; ?>>Baja</option>
                                            <option value="media" <?php echo ($prioridad_sap == 'media') ? 'selected' : ''; ?>>Media</option>
                                            <option value="alta" <?php echo ($prioridad_sap == 'alta') ? 'selected' : ''; ?>>Alta</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="small fw-bold opacity-75">Comentario Interno</label>
                                        <small class="text-muted" id="count-coment-<?php echo $id; ?>">0/16000</small>
                                    </div>
                                    <textarea name="comentario_sap" id="input-coment-<?php echo $id; ?>"
                                              class="form-control form-control-sm sap-input <?php echo ($tiene_sap) ? 'is-blocked-input' : ''; ?>" 
                                              rows="3" maxlength="16000" onkeyup="actualizarContador(this, 'count-coment-<?php echo $id; ?>')"
                                              <?php echo $readonly_sap; ?>><?php echo htmlspecialchars($comentario_sap); ?></textarea>
                                </div>
                                
                                <?php if($tiene_sap): ?>
                                    <div class="text-center mt-2">
                                        <small class="text-muted"><i class='bx bxs-lock-alt'></i> Sincronizado con SAP</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-custom-red px-4 shadow fw-bold">Actualizar Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<script>
// Función única para actualizar contadores de caracteres
function actualizarContador(input, targetId) {
    const max = input.getAttribute('maxlength');
    const actual = input.value.length;
    const el = document.getElementById(targetId);
    if(el) el.innerText = `${actual}/${max}`;
}

// Control de visibilidad de paneles (Toggle)
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('change', function() {
        const target = document.getElementById(this.getAttribute('data-target'));
        if(target) {
            target.style.display = (this.value === 'si') ? 'block' : 'none';
        }
    });
});

// Inicializar contadores al abrir/cargar para campos con texto previo
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.sap-input').forEach(input => {
        const targetId = input.id.replace('input-', 'count-');
        actualizarContador(input, targetId);
    });
});
</script>