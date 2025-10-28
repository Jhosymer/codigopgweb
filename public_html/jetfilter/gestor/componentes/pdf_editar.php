<!-- Es el componente de las imagenes de los filtros -->

<div class="row">
    <div class="col-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class='subtito_ms ms-2' id='h3-boletin'>Boletín Informativo</h3>
            </div>
            <div class="card-body">
                <?php
                if (!empty($boletin)) {
               
                    if (file_exists("../../../informacion_adicional/boletin/$codigo.pdf")) { ?>
                        <embed id="vistaPreviaboletin" src="<?php echo $loc; ?>informacion_adicional/boletin/<?php echo $codigo; ?>.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                    <?php } else { ?>
                        <embed id="vistaPreviaboletin" src="<?php echo $loc; ?>informacion_adicional/no-pdf.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                    <?php }
                } else { ?>
                    <embed id="vistaPreviaboletin" src="<?php echo $loc; ?>informacion_adicional/no-pdf.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                <?php } ?>
            </div>
            <div class="card-footer">
                <div class="flex-d mt-5 mb-2" style="display: flex; align-items: center;">
                <input type="file" name="boletin" id="boletin" accept=".pdf" class="inputfile inputfile-1" style="display: none;">
                
                <label for="boletin" class="file-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span id="archivoboletin" class="iborrainputfile">Seleccionar archivo</span>
                </label>
                 <input type="hidden" name="eliminar-pdf1" value="1" >
                   
                                    
                     <div class="mt-auto">
                        <button type="button" name="button-pdf1" id="button-pdf1" class="btn_borde_rweb_form ms-2">Eliminar PDF</button>
                    </div>
               
                 </div>
            </div>
        </div>
    </div>

    <div class="col-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h3 class='subtito_ms ms-2' id='h3-instalacion'>Instrucciones de Instalación</h3>
            </div>
            <div class="card-body">
                <?php
                if (!empty($instalacion)) {
                    if (file_exists("../../../informacion_adicional/instalacion/$codigo.pdf")) { ?>
                        <embed id="vistaPreviainstalacion" src="<?php echo $loc; ?>informacion_adicional/instalacion/<?php echo $codigo; ?>.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                    <?php } else { ?>
                        <embed id="vistaPreviainstalacion" src="<?php echo $loc; ?>informacion_adicional/no-pdf.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                    <?php }
                } else { ?>
                    <embed id="vistaPreviainstalacion" src="<?php echo $loc; ?>informacion_adicional/no-pdf.pdf?t=<?php echo $rann ?>" type="application/pdf" width="100%" height="400px" style="overflow: hidden;"></embed>
                <?php } ?>
            </div>
            <div class="card-footer">
            <div class="flex-d mt-5 mb-2" style="display: flex; align-items: center;">
                <input type="file" name="instalacion" id="instalacion" accept=".pdf" class="inputfile inputfile-1" style="display: none;">
                <label for="instalacion" class="file-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                    </svg>
                    <span id="archivoinstalacion" class="iborrainputfile">Seleccionar archivo</span>
                </label>
                 <input type="hidden" name="eliminar-pdf2" value="2" >
                
                     <div class="mt-auto">
                        <button type="button" name="button-pdf2" id="button-pdf2" class="btn_borde_rweb_form ms-2">Eliminar PDF</button>
                    </div>
                
                 </div>
            </div>
        </div>
    </div>
</div>








                
                  
