


<!-- Button trigger modal -->
<div class="alert alert-secondary mt-2 ">
<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Cambiar Status
</button>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Cambiar Status</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
    <form action="crud.php" method="post" id="myform">
    <label for="idstatus" class="form-label">Status :</label>
    <select name="idstatus" id="idstatus" class="form-select form-control">
      <?php include_once("combos/status.php")?>
    </select>
    <input type="hidden" name="id_pedido" value="<?php echo $id_pedido ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerra</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>