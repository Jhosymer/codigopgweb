<div class="modal fade" id="nuevoTicketModal" tabindex="-1" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulo-ms" id="nuevoTicketModalLabel">Nuevo Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ticketForm" method="POST" action="sistemas/soporte/crud.php" enctype="multipart/form-data">
                    <input type="hidden" name="modo" value="Insertar">
                    <div class="mb-3">
                        <label for="asunto" class="form-label subtito_ms">Asunto</label>
                        <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Ingrese el asunto" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipoSoporte" class="form-label subtito_ms">Tipo de Soporte</label>
                        <select class="form-select" id="tipoSoporte" name="id_tp_soporte" required>
                            <?php include_once("sistemas/combos/tipoSoporte.php")?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="archivo" class="form-label subtito_ms">Subir Archivo</label>
                        <input type="file" class="form-control" id="archivo" name="archivo" accept=".pdf,.jpg,.jpeg,.png" >
                    </div>
                    <div class="mb-3">
                        <label for="detalle" class="form-label subtito_ms">Detalle de Soporte</label>
                        <textarea class="form-control" id="detalle" name="detalle" rows="3" placeholder="Escriba el detalle del soporte" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="./../../js/js_vende/validarmodalsoporte.js"></script>