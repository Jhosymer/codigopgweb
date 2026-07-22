

<div class="container">
    <h1 class="titulo text-center">Consulta Disponibilidad</h1>
    
    <div class="search-container">
        <div class="card p-4">
    <div class="input-group">
        <select class="form-select" id="tipo_busqueda">
            <option value="codigo" selected>Por Código</option>
            <option value="descripcion">Por Descripción</option>
        </select>

        <input type="text" id="campo_busqueda" class="form-control" placeholder="Escriba para buscar disponibilidad..." autocomplete="off">
        
        <span class="input-group-text bg-white">
            <i class="bx bx-search text-primary"></i>
        </span>
    </div>
    <small class="text-muted mt-2">La búsqueda se realiza automáticamente mientras escribe. <b> Selecione la Busqueda por código o por descripción.</b></small>
    
</div>



</div>

<div id="contenedor_resultados" style="display: none;">
    <div class="table-container card mt-4">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Código</th>
                        <th>Descripción</th>
                        <th style="width: 150px;">Precio Und.</th>
                        <th>Disponibilidad</th>
                        <th class="pe-3">Empaque</th>
                    </tr>
                </thead>
                <tbody id="tabla_resultados">
                    </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-top-0 py-3">
            <div class="d-flex justify-content-center flex-wrap gap-4 border rounded p-2 bg-light shadow-sm">
                <div class="d-flex align-items-center">
                    <span class="dot-status dot-success me-2"></span>
                    <small class="fw-bold text-muted">Disponibilidad Inmediata</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="dot-status dot-warning me-2"></span>
                    <small class="fw-bold text-muted">Poca Disponibilidad</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="dot-status dot-danger me-2"></span>
                    <small class="fw-bold text-muted">Consulta con Ventas</small>
                </div>
                <div class="d-flex align-items-center">
                    <span class="dot-status dot-info me-2"></span>
                    <small class="fw-bold text-muted">Stock no configurado</small>
                </div>
    </div>

    
    </div>

    
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-10">
        <div class="p-3 border rounded shadow-sm bg-white">
            <div class="d-flex align-items-center mb-2">
                <strong class="text-danger uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                    NOTA IMPORTANTE 
                </strong>
            </div>
            
           <p class="text-secondary mb-0" style="font-size: 0.8rem;">
                Puedes procesar tu solicitud aunque los artículos estén en 
                <span class="dot-status dot-danger me-2 ms-2" style="width: 10px; height: 10px;"></span>  o 
                <span class="dot-status dot-warning me-2 ms-2" style="width: 10px; height: 10px;"></span> 
                Esto puede indicar que el artículo se encuentra actualmente en proceso de producción o en fase de planificación.
            </p>
            
            <div class="pt-2 border-top">
                <div class="align-items-center">
                    <p class="text-secondary mb-0" style="font-size: 0.8rem;">
                        Que el indicador no esté en <span class="dot-status dot-success me-2 ms-2" style="width: 8px; height: 8px;"></span> verde no significa falta de existencias futuras; 
                        al realizar tu pedido, aseguras la asignación y prioridad en nuestra próxima disponibilidad programada.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


