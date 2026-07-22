<div class="modal fade" id="modalPulgadas" tabindex="-1" aria-labelledby="modalPulgadasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header header-red p-3">
                <h5 class="modal-title fw-bold text-white" id="modalPulgadasLabel">Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formModalPulgadas" action="crud.php" method="POST">
                <div class="modal-body p-4">
                    <div id="contenedorFormulario">
                        <input type="hidden" id="id_pulgadas" name="id">

                        <div class="row align-items-center mb-4">
                            <div class="col-auto" style="width: 140px;">
                                <label class="form-label mb-0 fw-bold text-secondary">Código</label>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" id="codigo" name="codigo" required>
                                <div id="text_codigo" class="form-control-plaintext d-none fw-bold border-bottom fs-5"></div>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-auto" style="width: 140px;">
                                <label class="form-label mb-0 fw-bold text-secondary">Nominal</label>
                            </div>
                            <div class="col">
                                <div id="input_group_valor" class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="valor_nominal" name="valor_nominal" required>
                                    <span class="input-group-text">mm</span>
                                </div>
                                <div id="text_valor" class="form-control-plaintext d-none fw-bold border-bottom fs-5"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0" id="footerFormulario">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn-icon" id="btnGuardar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>