
<?php 
    //Función de Actualizado, verificará si existe la variable de sessión y mostrará la alerta, 
    //al final eliminará la variable de session
    function alerta_actualizado($mensaje = "La información se ha actualizado"){
        if( isset( $_SESSION["actualizado"] ) )
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
            unset( $_SESSION['actualizado'] );
        }
    }
    