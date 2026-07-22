<h1 class="titulo text-center">Ticket Soporte</h1>



<?php
   
    include('tabla_soportes.php');  

   if (isset($_SESSION['mensajeLista'])): ?>
    <script>
        Swal.fire({
            // Si 'tm' es alert-success usa 'success', de lo contrario usa 'error'
            icon: '<?php echo ($_SESSION['tm'] == "alert-success") ? "success" : "error"; ?>',
            title: '<?php echo ($_SESSION['tm'] == "alert-success") ? "¡Operación Exitosa!" : "¡Atención!"; ?>',
            html: '<?php echo $_SESSION['mensajeLista']; ?>',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#E2001A'
        });
    </script>
    <?php 
        unset($_SESSION['mensajeLista']);
        unset($_SESSION['tm']);
    ?>
<?php endif; ?>

<script>
document.querySelectorAll('[id^="verTicketModal-"]').forEach(modal => {
    modal.addEventListener('shown.bs.modal', function () {
        // Extraemos el ID del ticket del atributo ID del modal
        const ticketId = this.id.replace('verTicketModal-', '');
        
        // Petición al archivo que hace el UPDATE
        fetch('./sistemas/soporte/crud.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_visto=' + ticketId
        })
        .then(response => response.text())
        .then(data => {
          //  console.log('Ticket ' + ticketId + ' marcado como visto.');
            
            // OPCIONAL: Si el ticket era "nuevo", al cerrar el modal recargamos
            // para que el número en el botón "Actualizados" disminuya
            modal.addEventListener('hidden.bs.modal', function () {
                window.location.reload();
            }, { once: true }); // 'once: true' para que no se ejecute múltiples veces
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

