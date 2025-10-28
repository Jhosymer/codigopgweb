<?php 
    date_default_timezone_set('America/Caracas');
    include_once('../../../config/conexion.php');
    session_start();

    //Se envia $_POST['vehiculos'], cuando se pulsa el boton enviar en nuevo.php
    if( isset($_POST['vehiculos']) ){
        $sincronizado = date("Ymd");

        //Se cargan las variables del formulario
        $modelo = $_POST['modelo'];
        $motor = $_POST['motor'];
        $marca = $_POST['marca'];
        $cilindrada = $_POST['cilindrada'];
        $ano = $_POST['ano'];

        //Se comprueba si la marca ya existe
        //Se hace la consulta para buscar cuantos vehículos coinciden en modelo, motor y marca con el que se va a crear
        $sql = "SELECT COUNT(*) as total FROM aplicacion_vehiculo WHERE ( modelo = :modelo ) and ( motor = :motor ) and ( cilindrada = :cilindrada ) and ( ano = :ano ) and ( id_marca = :marca ) and ( deleted_at is null )";
        $numVehiculos = $base_de_datos->prepare($sql);
        $numVehiculos->bindParam(':modelo', $modelo, PDO::PARAM_STR);
        $numVehiculos->bindParam(':motor', $motor, PDO::PARAM_STR);
        $numVehiculos->bindParam(':cilindrada', $cilindrada, PDO::PARAM_STR);
        $numVehiculos->bindParam(':ano', $ano, PDO::PARAM_STR);
        $numVehiculos->bindParam(':marca', $marca, PDO::PARAM_INT);
        $numVehiculos->setFetchMode(PDO::FETCH_ASSOC); 
        $numVehiculos->execute();
        $numVehiculos = $numVehiculos->fetch();
        $numVehiculos = $numVehiculos['total'];

        //Si no hay coincidencia, se agrega el registro
        if( $numVehiculos == 0 ){
            //Se crea la nueva marca

            $argumentos = [$marca, $modelo, $motor, $cilindrada, $ano, $sincronizado];

            $seleccionado = $base_de_datos->prepare("INSERT INTO aplicacion_vehiculo (id_marca, modelo, motor, cilindrada, ano, sincronizado) VALUES (?, ?, ?, ?, ?, ?)");
            $seleccionado->execute($argumentos);

            $_SESSION['nuevo'] = true;
            header("location: ./vehiculos.php");
        }
        else {
            $_SESSION['existencia'] = true;
            header("location: ./nuevo.php");
        }
    }
    //Si no se encuentra que se pulso el boton de enviar, te redigirá
    else {
        header("location: ./vehiculos.php");
    }