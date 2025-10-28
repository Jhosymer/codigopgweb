
<?php
    //Alerta de Registro ha sido agregado
    function alerta_nuevo( $mensaje = "El registro ha sido agregado" ){
        if( isset( $_SESSION["nuevo"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo $mensaje; ?>',
                        confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
                </script>
            <?php
            unset( $_SESSION['nuevo'] );
        }
    }