
<?php 
    //Función de Alerta de que el código ya existe, verificará si existe la variable de sessión y mostrará la alerta, 
    //al final eliminará la variable de session
    //Se usara cuando se intente registrar un tipo o categoria con información ya existente
    function alerta_ya_existe($mensaje = "Ya existe un registro con esta información"){
        if( isset( $_SESSION["existencia"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ya existe un registro con está información',
                        text: '<?php echo $mensaje; ?>',
                         confirmButtonText: 'Aceptar' // Botón para aceptar
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes agregar cualquier acción adicional que quieras realizar después de que el usuario haga clic en "Aceptar"
                    }
                });
                </script>
            <?php
            unset( $_SESSION['existencia'] );
        }
    }
    