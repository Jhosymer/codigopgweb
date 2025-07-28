<script src="./../js/sweetAlerta.js"></script>
<?php 
    //Función de Alerta de error, verificará si existe la variable de sessión y mostrará la alerta, 
    //al final eliminará la variable de session
    function alerta_error($mensaje = "No se pudo completar el proceso"){
        if( isset( $_SESSION["error"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '<?php echo $mensaje; ?>',
                        timer: 1250,
                    })
                </script>
            <?php
            unset( $_SESSION['error'] );
        }
    }