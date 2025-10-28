
<?php 
    function alerta_actualizado_imagenes($mensaje = "La información se ha actualizado"){
        if( isset( $_SESSION["actualizado_imagenes"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: '<?php echo $mensaje; ?>',
                        confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
                </script>
            <?php
            unset( $_SESSION['actualizado_imagenes'] );
        }
    }