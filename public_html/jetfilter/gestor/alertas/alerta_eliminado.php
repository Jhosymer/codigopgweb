
<?php 
//Función de alerta cuando se elimine un registro, verificará si existe la variable de sessión 
//y mostrará la alerta, al final eliminará la variable de session
    function alerta_eliminado($mensaje = "El registro se ha eliminado"){
        if( isset( $_SESSION["eliminado"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado!',
                        text: '<?php echo $mensaje; ?>',
                        confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
                </script>
            <?php
            unset( $_SESSION['eliminado'] );
        }
    }