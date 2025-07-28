<script src="./../js/sweetAlerta.js"></script>
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
                        timer: 1250,
                    }) 
                </script>
            <?php
            unset( $_SESSION['actualizado'] );
        }
    }
    