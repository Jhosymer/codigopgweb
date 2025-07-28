<?php 
    date_default_timezone_set('America/Caracas');
    if( !isset($_POST['id']) ){
        header("location: vehiculos.php");
    }
    session_start();

    include_once('./../conexion/conexion.php');
  
    //Tiene que existir $_POST['btnimg'], esta existe cuando se enviar el formulario mediante pulsar el boton enviar
    if(isset($_POST['btnimg'])){
        $id = $_POST['id'];
        $sincronizado = date("Ymd");
        $fecha_updated = date("Y-m-d H:i:s");   

        //Se cargan las variables del formulario
        $modelo = $_POST['modelo'];
        $motor = $_POST['motor'];
        $marca = $_POST['marca'];
        $cilindrada = $_POST['cilindrada'];
        $ano = $_POST['ano'];
        $fecha_updated = date("Y-m-d H:i:s"); 
        
        //Se comprueba si la marca ya existe
        //Se hace la consulta para buscar cuantos vehículos coinciden en modelo, motor y marca con el que se va a crear
        $sql = "SELECT COUNT(*) as total FROM aplicacion_vehiculo WHERE ( modelo = :modelo ) and ( motor = :motor ) and ( cilindrada = :cilindrada ) and ( ano = :ano ) and ( id_marca = :marca ) and ( id != :id ) and ( deleted_at is null )";
        $numVehiculos = $base_de_datos->prepare($sql);
        $numVehiculos->bindParam(':modelo', $modelo, PDO::PARAM_STR);
        $numVehiculos->bindParam(':motor', $motor, PDO::PARAM_STR);
        $numVehiculos->bindParam(':cilindrada', $cilindrada, PDO::PARAM_STR);
        $numVehiculos->bindParam(':ano', $ano, PDO::PARAM_STR);
        $numVehiculos->bindParam(':marca', $marca, PDO::PARAM_INT);
        $numVehiculos->bindParam(':id', $id, PDO::PARAM_INT);
        $numVehiculos->setFetchMode(PDO::FETCH_ASSOC); 
        $numVehiculos->execute();
        $numVehiculos = $numVehiculos->fetch();
        $numVehiculos = $numVehiculos['total'];

        //Si no hay coincidencia, se agrega el registro (No se toma en cuenta el que se esta editando)
        if( $numVehiculos == 0 ){
            //Se crea la nueva marca
            $argumentos = [$modelo, $motor, $marca, $cilindrada, $ano, $sincronizado, $fecha_updated, $id];

            $actualizando = $base_de_datos->prepare("UPDATE aplicacion_vehiculo SET modelo = ?, motor = ?, id_marca = ?, cilindrada = ?, ano = ?, sincronizado = ?, updated_at = ?  WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos);
            
            $_SESSION['actualizado'] = true;
            header("location: vehiculos.php");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: editar.php?id=$id");
        }
    }
    //Si no se encuentra que se pulso el boton de enviar, te redigirá
    else {
        header("location: vehiculos.php");
    }

?>