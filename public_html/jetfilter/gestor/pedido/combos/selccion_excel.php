<script>
  const fileInput = document.getElementById('excelFile');
  const button = document.querySelector('label[for="excelFile"]');
  const bt_actualizar_stock = document.getElementById('bt_actualizar_stock');

  fileInput.addEventListener('change', function() {
    const fileName = fileInput.files[0].name;
    const fileExtension = fileName.split('.').pop().toLowerCase();
    const allowedExtensions = ['xlsx', 'xls'];

    if (allowedExtensions.includes(fileExtension)) {
      button.textContent = fileName;
      bt_actualizar_stock.style.display = 'inline-block';
    } else {
      button.textContent = 'Actualizar stock';
      bt_actualizar_stock.style.display = 'none';
      alert('Por favor, selecciona un archivo de Excel válido.');
    }
  });
</script>