
<?php 
    //Función de alerta al eliminar imagenes
    function alerta_imagenes_pdf($mensaje = "el pdf ha sido eliminada"){
        if( isset( $_SESSION["pdf_eliminada"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: '<?php echo $mensaje; ?>',
                         confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
                </script>
            <?php
            unset( $_SESSION['pdf_eliminada'] );
        }
    }