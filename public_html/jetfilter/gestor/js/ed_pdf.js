document.getElementById('boletin').addEventListener('change', function() {
  var archivo = this.files[0];
  var nombreArchivo = archivo.name;
  var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();
  
  if (extension === 'pdf') {
    document.getElementById('archivoboletin').innerHTML = nombreArchivo;
    document.getElementById('h3-boletin').innerHTML = 'Vista previa Boletín Informativo';
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('vistaPreviaboletin').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(archivo);
  } else {
    alert('Por favor, seleccione un archivo PDF.');
  }
});

document.getElementById('vistaPreviaboletin').addEventListener('error', function() {
  this.setAttribute('src', '<?php echo $loc; ?>informacion_adicional/boletin/<?php echo $codigo;?>.pdf');
});

    // instalacion
document.getElementById('instalacion').addEventListener('change', function() {
  var archivo = this.files[0];
  var nombreArchivo = archivo.name;
  var extension = nombreArchivo.substring(nombreArchivo.lastIndexOf('.') + 1).toLowerCase();
  
  if (extension === 'pdf') {
    document.getElementById('archivoinstalacion').innerHTML = nombreArchivo;
    document.getElementById('h3-instalacion').innerHTML = 'Vista previa Instrucciones de Instalación';
    var reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('vistaPreviainstalacion').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(archivo);
  } else {
    alert('Por favor, seleccione un archivo PDF.');
  }
});

document.getElementById('vistaPreviainstalacion').addEventListener('error', function() {
  this.setAttribute('src', '<?php echo $loc; ?>informacion_adicional/instalacion/<?php echo $codigo;?>.pdf');
});