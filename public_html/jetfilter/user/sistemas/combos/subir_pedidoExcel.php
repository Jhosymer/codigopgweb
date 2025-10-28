<div class="row">
    <div class="col-8 col-md-4">
        <div class="accordion" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Subir Pedido Por Excel
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body text-center">
                        Aquí puedes descargar un <a href="#" id="descargar-excel-btn" class ="btn btn-info">Excel Ejemplo</a>
                        <p class="small mt-2">
                            <b class="text-danger">Advertencias importantes al subir el archivo:</b><br/>
                            1. El campo <b>"Codigo"</b> debe ser un código de producto válido y no puede estar vacío. Si un codigo no es válido, la fila se omitirá.<br/>
                            2. El campo <b>"Cantidad"</b> debe ser un número entero y un <b>múltiplo de la unidad de empaque</b>. Por ejemplo, si un producto viene en cajas de 12 unidades, la cantidad debe ser 12, 24, 36, etc.<br/>
                            3. El archivo debe mantener la misma estructura y los encabezados del archivo de ejemplo descargado. <b>Los campos Codigo y Cantidad son obligatorios.<br/>
                        </p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalSubirArchivo">
                            Subir Archivo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-labelledby="modalSubirArchivoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formSubirArchivo" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubirArchivoLabel">Subir Archivo de Pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="loading-spinner" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Validando archivo, por favor espere...</p>
                    </div>
                    <div id="form-upload-area">
                        <div class="mb-3">
                            <label for="inputArchivo" class="form-label">Selecciona el archivo Excel (.xlsx, .xls)</label>
                            <input class="form-control" type="file" id="inputArchivo" name="archivoPedido" accept=".xlsx, .xls">
                             <p class="small mt-2">
                                <b class="text-danger">Advertencias importantes al subir el archivo:</b><br/>
                                1. El campo <b>"Codigo"</b> debe ser un codigo de producto válido y no puede estar vacío. Si un codigo no es válido, la fila se omitirá.<br/>
                                2. El campo <b>"Cantidad"</b> debe ser un número entero y un <b>múltiplo de la unidad de empaque</b>. Por ejemplo, si un producto viene en cajas de 12 unidades, la cantidad debe ser 12, 24, 36, etc.<br/>
                                3. El archivo debe mantener la misma estructura y los encabezados del archivo de ejemplo descargado. <b>Los campos Codigo y Cantidad son obligatorios.<br/>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" id="btnSubmitFile" class="btn btn-primary">Subir Archivo</button>
                        </div>
                    </div>
                    <div id="resultados-area" class="d-none">
                        <div id="resultados-ajax"></div>
                        <div id="dynamic-footer-area" class="modal-footer mt-4"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="./../../js/js_vende/subir_pedidos_excel.js"></script>

