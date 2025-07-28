<script src="./../js/sweetAlerta.js"></script>
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
                        timer: 1250,
                    }) 
                </script>
            <?php
            unset( $_SESSION['eliminado'] );
        }
    }