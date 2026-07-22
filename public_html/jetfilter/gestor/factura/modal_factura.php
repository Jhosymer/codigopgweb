<?php
$stmt_modal = $base_de_datos->prepare($sql); 
$stmt_modal->execute();

while ($row = $stmt_modal->fetch(PDO::FETCH_ASSOC)) {
    $modalID = "modalFactura" . $row['id'];
?>
 
<div class="modal fade" id="<?php echo $modalID; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-red p-3">
                <h5 class="modal-title fw-bold text-white"><i class='bx bx-receipt'></i> Factura Nro. <?php echo $row['num_fact']; ?></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="update.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <div class="modal-body">
                    <div class="mb-3 mt-3 row align-items-center">
                        <label for="nota_credito_<?php echo $row['id']; ?>" class="col-sm-5 col-form-label fw-bold">
                            Nota de Crédito Nro: 
                        </label>
                        
                        <div class="col-sm-7">
                            <input type="text" 
                                class="form-control" 
                                id="nota_credito_<?php echo $row['id']; ?>" 
                                name="nota_credito" 
                                value="<?php echo $row['nota_credito'] ?? ''; ?>" 
                                placeholder="Ingrese nota de crédito...">
                        </div>

                        <div class="col-12 mt-3 alert alert-info">
                            <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.2;">
                                <i class='bx bx-info-circle'></i> 
                                Si se agrega la nota de crédito, esta factura <strong>no será visible para el cliente</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-custom-red px-4 shadow fw-bold">Actualizar</button>
                </div>
            </form> </div>
    </div>
</div>

<?php
}
?>