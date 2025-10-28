
<?php 
    //Función de alerta al eliminar imagenes
    function alerta_imagenes_eliminada($mensaje = "La imagen ha sido eliminada"){
        if( isset( $_SESSION["imagen_eliminada"] ) )
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
            unset( $_SESSION['imagen_eliminada'] );
        }
    }