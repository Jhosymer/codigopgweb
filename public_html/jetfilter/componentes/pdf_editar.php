<!-- Es el componente de las imagenes de los filtros -->

<div class="galeria"></br>
    <div class="contenedor-imagenes">


<div class="imag">
    <h3 class='text-center' id='h3-boletin'>Boletín Informativo  </h3>
<?php

    if( $boletin != "" ){ 
       if( file_exists("./../../informacion_adicional/boletin/$codigo.pdf") ){?>
     <embed id="vistaPreviaboletin" src="./../../informacion_adicional/boletin/<?php echo $codigo;?>.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%"></embed>

     

    <?php } else { ?>
      <embed id="vistaPreviaboletin" src="./../../informacion_adicional/no-pdf.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%"></embed>

         
  <?php  } 
    
       }  else { ?>
          <embed id="vistaPreviaboletin" src="./../../informacion_adicional/no-pdf.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%"></embed>

 <?php  } 
       ?>

<input type="file" name="boletin" id="boletin" accept=".pdf" class="inputfile inputfile-1" style="display: none;">
<label for="boletin" class="file-1">
<svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
<span id="archivoboletin" class="iborrainputfile">Seleccionar archivo</span></label>
</div>

<div class="imag">
<h3 class='text-center' id='h3-instalacion'>Instrucciones de Instalación</h3>
<?php
    if(  $instalacion != "" ){ 
       if(file_exists("./../../informacion_adicional/instalacion/$codigo.pdf") ){?>
     
     <embed id="vistaPreviainstalacion" src="./../../informacion_adicional/instalacion/<?php echo $codigo;?>.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%"></embed>


    <?php } else { ?>
      <embed id="vistaPreviainstalacion" src="./../../informacion_adicional/no-pdf.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%" ></embed>

  <?php  } 
    
       }  else { ?>
         <embed id="vistaPreviainstalacion" src="./../../informacion_adicional/no-pdf.pdf?t=<?php echo $rann?>" type="application/pdf" width="100%" height="100%" ></embed>
 <?php  } 
       ?>


<input type="file" name="instalacion" id="instalacion" accept=".pdf" class="inputfile inputfile-1" style="display: none;">
<label for="instalacion" class="file-1">
<svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
<span id="archivoinstalacion" class="iborrainputfile">Seleccionar archivo</span></label>

<script>
    //boletin

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
  this.setAttribute('src', './../../informacion_adicional/boletin/<?php echo $codigo;?>.pdf');
});
</script>
<script>
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
  this.setAttribute('src', './../../informacion_adicional/instalacion/<?php echo $codigo;?>.pdf');
});


</script>


                
                  
</div>

         
    </div>
</div>