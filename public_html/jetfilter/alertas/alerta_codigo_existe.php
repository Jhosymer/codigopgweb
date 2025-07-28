<script src="./../js/sweetAlerta.js"></script>
<?php 
    //Función de Alerta de que el código ya existe, verificará si existe la variable de sessión y mostrará la alerta, 
    //al final eliminará la variable de session
    //Se usara cuando se intente registrar un filtro con un código ya utilizado
    function alerta_codigo_existe($mensaje = "Un filtro con este codigo ya existe"){
        if( isset( $_SESSION["yaExistencia"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'No se puede repetir el código',
                        text: '<?php echo $mensaje; ?>',
                        timer: 2000,
                    }) 
                </script>
            <?php
            unset( $_SESSION['yaExistencia'] );
        }
    }
    