<?php 
// Función de Alerta de error, verificará si existe la variable de sesión y mostrará la alerta, 
// al final eliminará la variable de sesión
function alerta_error($mensaje = "No se pudo completar el proceso"){
    if( isset( $_SESSION["error"] ) )
    {
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo $mensaje; ?>',
                    confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
            </script>
        <?php
        unset( $_SESSION['error'] );
    }
}
?>
