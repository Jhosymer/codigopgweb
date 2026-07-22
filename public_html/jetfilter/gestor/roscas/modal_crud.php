<div class="modal fade" id="modalRoscas" tabindex="-1" aria-labelledby="modalRoscasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header header-red p-3">
                <h5 class="modal-title fw-bold text-white" id="modalRoscasLabel">Verificación Obligatoria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="formModalRoscas" action="crud.php" method="POST">
                <div class="modal-body p-4">
                    
                    <div id="contenedorAlerta">
                        <div class="alert alert-info border-0 shadow-sm mb-4">
                            <p class="mb-3 text-muted" style="font-size: 0.9rem;">
                                Antes de proceder, es obligatorio validar el formato de la rosca en la <strong>documentación de Jetfilter:</strong>
                            </p>
                            <div class="d-grid gap-2">
                                <a href="https://jetfilter.info/link/33#bkmrk-nomenclatura-de-rosc" target="_blank" class="a_web">
                                    <i class="bi bi-1-circle-fill me-2 text-info"></i> Nomenclatura General
                                </a>
                                <a href="https://jetfilter.info/books/roscas-tipo-sierra/page/nomenclatura" target="_blank" class="a_web">
                                    <i class="bi bi-2-circle-fill me-2 text-info"></i> Roscas Métricas / Sierra
                                </a>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn-icon w-100" onclick="mostrarFormulario()">
                                He verificado, continuar <i class="bi bi-arrow-right-short"></i>
                            </button>
                        </div>
                    </div>

                    <div id="contenedorFormulario" class="d-none">
                        <input type="hidden" id="id_rosca" name="id">

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

                <div class="modal-footer bg-light border-0 d-none" id="footerFormulario">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn-icon" id="btnGuardar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>