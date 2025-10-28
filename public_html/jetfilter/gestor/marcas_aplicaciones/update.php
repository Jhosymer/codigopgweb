<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: marcas_aplicaciones.php");
    }
    session_start();

    include_once('../../../config/conexion.php');

    if(isset($_POST['btnimg'])){
        $marca = $_POST['marca'];
        $id = $_POST['id'];
        $sincronizado = date("Ymd");
        $fecha_updated = date("Y-m-d H:i:s");   

        //Se comprueba si la marca ya existe
        $sql = "SELECT COUNT(*) as total FROM aplicacion_marca WHERE ( marca = :marca ) and ( id != :id ) and ( deleted_at is null )";
        $numMarcas = $base_de_datos->prepare($sql);
        $numMarcas->bindParam(':marca', $marca, PDO::PARAM_STR);
        $numMarcas->bindParam(':id', $id, PDO::PARAM_INT);
        $numMarcas->setFetchMode(PDO::FETCH_ASSOC); 
        $numMarcas->execute();
        $numMarcas = $numMarcas->fetch();
        $numMarcas = $numMarcas['total'];

        //Si no hay coincidencia, se agrega el registro (No se toma en cuenta el que se esta editando)
        if( $numMarcas == 0 ){
            //Se crea la nueva marca
            $argumentos = [$marca, $sincronizado, $fecha_updated, $id];

            $actualizando = $base_de_datos->prepare("UPDATE aplicacion_marca SET marca = ?, sincronizado = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos);

            

            $_SESSION['actualizado'] = true;
            header("location: marcas_aplicaciones.php");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: editar.php?id=$id");
        }
    }
    else {
        header("location: marcas_aplicaciones.php");
    }

?>