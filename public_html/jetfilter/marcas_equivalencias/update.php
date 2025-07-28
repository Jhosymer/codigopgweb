<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: marcas_equivalencias.php");
    }
    session_start();

    include_once('./../conexion/conexion.php');

    if(isset($_POST['btnimg'])){
        $marca = $_POST['marca'];
        $mostrar = $_POST['mostrar'];
        $id = $_POST['id'];
        $sincronizado = date("Ymd");
        $fecha_updated = date("Y-m-d H:i:s");   

        //Se comprueba si la marca ya existe
        $sql = "SELECT COUNT(*) as total FROM equivalencia_marca WHERE ( marca = :marca ) and ( id != :id ) and ( deleted_at is null )";
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
            $argumentos = [$marca, $mostrar, $sincronizado, $fecha_updated, $id];

            $actualizando = $base_de_datos->prepare("UPDATE equivalencia_marca SET marca = ?, mostrar =?, sincronizado = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos);

             // Actualizar el campo marca en filtro_equivalencia
            $actualizandoFiltro = $base_de_datos->prepare("UPDATE filtro_equivalencia SET marca = ? WHERE id_marca = ?") or die("Error al actualizar filtro_equivalencia");
            $actualizandoFiltro->execute([$marca, $id]);

            $_SESSION['actualizado'] = true;
            header("location: marcas_equivalencias.php");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: editar.php?id=$id");
        }
    }
    else {
        header("location: marcas_equivalencias.php");
    }

?>