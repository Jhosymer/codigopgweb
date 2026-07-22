<div class="modal fade" id="nuevoTicketModal" tabindex="-1" aria-labelledby="nuevoTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header header-custom-red p-3">
                <h5 class="modal-title text-white" id="nuevoTicketModalLabel">
                    <i class='bx bx-plus-circle'></i> Nuevo Ticket de Soporte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="ticketForm" method="POST" action="sistemas/soporte/crud.php" enctype="multipart/form-data">
                <div class="modal-body p-4 bg-white">
                    <input type="hidden" name="modo" value="Insertar">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="tipoSoporte" class="label-caps mb-1 titulo_bold_ms text-muted">Tipo de Soporte</label>
                            <select class="form-select shadow-sm border-light-subtle fw-semibold" id="tipoSoporte" name="id_tp_soporte" required>
                                <option value="" selected disabled>Seleccione una opción...</option>
                                <option value="1">Soporte Técnico</option>
                                <option value="2">Ventas</option>
                            </select>
                        </div>

                        <div id="contenedorFormulario" class="d-none">
                            <div id="divVentas" class="d-none mb-3">
                                <label class="titulo_bold_ms label-caps mb-1 text-muted">Asunto</label>
                                <input type="text" class="form-control" name="asunto" id="inputAsunto" placeholder="¿Qué sucede?">
                            </div>

                            <div id="divSoporte" class="d-none mb-3">
                                <label class="titulo_bold_ms label-caps mb-1 text-muted">Asunto Técnico</label>
                                <select class="form-select form-select-sm" name="asunto" id="selectAsunto">
                                    <option value="Solicitud Garantía de producto">Solicitud Garantía de producto</option>
                                    <option value="Producto deteriorado en Recepcion">Producto deteriorado en Recepción</option>
                                    <option value="Producto con identificacion erronia">Producto con identificación errónea</option>
                                </select>

                                <label class="titulo_bold_ms label-caps mb-1 text-muted">Buscar Código</label>
                                <div class="mb-2">
                                    <small class="text-secondary fst-italic">
                                        <i class='bx bx-info-circle'></i> Producto relacionado: Ingrese el código para asociarlo al ticket.
                                    </small>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light text-muted fw-bold"><i class='bx bx-search'></i> Código:</span>
                                    <input type="text" id="busquedaFiltro" class="form-control" placeholder="Escriba aquí..." required>
                                </div>
                                <div id="listaResultados" class="list-group mt-1 shadow-sm position-absolute w-100" style="z-index: 1050; max-height: 180px; overflow-y: auto;"></div>
                                <input type="hidden" name="codigo_filtro" id="codigoSeleccionado" required>
                                <small id="textoSeleccionado" class="text-success fw-bold mt-1 d-block"></small>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="p-3 rounded-3 border" style="background-color: #f8faff; border-left: 4px solid #E2001A !important;">
                                    <label class="label-caps mb-1 text-muted titulo_bold_ms">Evidencia</label>
                                    <input type="file" class="form-control form-control-sm" name="archivo" accept=".pdf,.jpg,.jpeg,.png">
                                     <small class="text-secondary fst-italic">
                                        <i class='bx bx-info-circle'></i> Formatos permitidos (PDF, JPG, PNG).
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="label-caps mb-1 text-muted titulo_bold_ms">Detalle del Soporte</label>
                                <textarea class="form-control" name="detalle" rows="4" id="txtDetalle" placeholder="Explique detalladamente..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 px-4">
                    <button type="button" class="btn btn-secondary px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-custom-red px-4 shadow fw-bold rounded-2">Crear Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>


