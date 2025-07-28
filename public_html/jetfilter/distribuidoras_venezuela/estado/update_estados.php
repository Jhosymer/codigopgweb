<?php 
    if( !isset($_POST['id']) ){
        header("location: ./estados_distribuidores.php");
    }

    try {
        session_start();
        $url_base_datos = './../../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            header("location: ./estados_distribuidores.php?errorBase=true");
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(PDOException $e){
        echo "
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema con la base de datos',
                timer: 1250,
                }).then(() => {
                    window.location.href('estados_distribuidores.php');
                })
            </script>
        ";

        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }


    if( isset( $_POST['enviar_principal'] ) ){
        $estado = $_POST['estado'];
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];
        $id = $_POST['id'];
        $id_distribuidora = $_POST['id_distribuidora'];

        $sql = "UPDATE distribuidora_estado SET estado = :estado, direccion = :direccion, ciudad = :ciudad
                        WHERE id = :id";
        $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
        $seleccionado->bindParam(':estado', $estado, PDO::PARAM_STR);
        $seleccionado->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $seleccionado->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
        $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
        $seleccionado->execute();

        $estados_distribucion = $_POST['estados_distribucion'];

        $sql = "SELECT id FROM distribuidora_estado WHERE id = :id";
        $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
        $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
        $seleccionado->execute();
        $seleccionado = $seleccionado->fetch();
        $id_padre = $seleccionado['id'];

        $sql = "SELECT estado FROM distribuidora_estado WHERE ( id_padre = :id_padre ) and ( deleted_at is null )";
        $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
        $seleccionado->bindParam(':id_padre', $id_padre, PDO::PARAM_INT);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
        $seleccionado->execute();
        $numFilaDistribuidores = $seleccionado->rowCount();
        $i = 0;
        while ( $fila = $seleccionado->fetch() ) {
            $distribuciones[$i] = $fila['estado'];
            $i++;
        }

        // $resultado = array_merge($estados_distribucion, $distribuciones);
        $resultado = array_unique($estados_distribucion);
        if( $numFilaDistribuidores > 0){
            $resultado = array_merge($estados_distribucion, $distribuciones);
        }
        $resultado = array_unique($resultado);

        $i = 0;
        $index = 0;
        if( $numFilaDistribuidores > 0){
            foreach ($resultado as $resul){
                if( in_array($resul, $distribuciones) ){

                }
                else {
                    $resultado_final[$index] = $resul;
                    $index++;
                }
                $i++;
            }

            foreach( $resultado_final as $res_final ){
                $argumentos = [$id_distribuidora, $id, $res_final];
                $seleccionado = $base_de_datos->prepare("INSERT INTO distribuidora_estado (id_distribuidora, id_padre, estado, principal) VALUES (?, ?, ?, 0)");
                $seleccionado->execute($argumentos); 
               
            }
        }
        else {
            foreach( $resultado as $res_final ){
                $argumentos = [$id_distribuidora, $id, $res_final];
                $seleccionado = $base_de_datos->prepare("INSERT INTO distribuidora_estado (id_distribuidora, id_padre, estado, principal) VALUES (?, ?, ?, 0)");
                $seleccionado->execute($argumentos); 
            }
        }

       $_SESSION['actualizado'] = true;
        header("location: ./estados_distribuidores.php");
    }
    else if( isset($_POST['enviar_distribuidora']) ){
        $estado = $_POST['estados_venezuela'];
        $id = $_POST['id'];

        $sql = "UPDATE distribuidora_estado SET estado = :estado
                        WHERE id = :id";
        $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
        $seleccionado->bindParam(':estado', $estado, PDO::PARAM_STR);
        $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
        $seleccionado->execute();

        $_SESSION['actualizado'] = true;
        header("location: ./estados_distribuidores.php");
    }
    else if( isset($_POST['enviar_nacional']) ){
        $estado = $_POST['estado'];
        $direccion = $_POST['direccion'];
        $ciudad = $_POST['ciudad'];
        $id = $_POST['id'];

        $sql = "UPDATE distribuidora_estado SET estado = :estado, direccion = :direccion, ciudad = :ciudad
                        WHERE id = :id";
        $seleccionado = $base_de_datos->prepare($sql) or die("Error al actualizar");
        $seleccionado->bindParam(':estado', $estado, PDO::PARAM_STR);
        $seleccionado->bindParam(':direccion', $direccion, PDO::PARAM_STR);
        $seleccionado->bindParam(':ciudad', $ciudad, PDO::PARAM_STR);
        $seleccionado->bindParam(':id', $id, PDO::PARAM_INT);
        $seleccionado->execute();

        $_SESSION['actualizado'] = true;
        header("location: ./estados_distribuidores.php");
    }
?>