<?php 
    date_default_timezone_set('America/Caracas');
    include_once('../../../config/conexion.php');
    session_start();

    if(isset($_POST['vehiculo_nuevo_editar'])){
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $motor = $_POST['motor'];
        $cilindrada = $_POST['cilindrada'];
        $ano = $_POST['ano'];
        $sincronizado = date("Ymd");
        $id = $_POST['id'];

        $argumentos = [$marca, $modelo, $motor, $cilindrada, $ano, $sincronizado];

        //Se comprueba si la marca ya existe
        $sql = "SELECT COUNT(*) as total FROM aplicacion_vehiculo WHERE ( modelo = :modelo ) 
                            and ( id_marca = :marca ) and ( motor = :motor ) and ( cilindrada = :cilindrada ) and 
                            ( ano = :ano ) and ( deleted_at is null )";
        $numVehiculos = $base_de_datos->prepare($sql);
        $numVehiculos->bindParam(':marca', $marca, PDO::PARAM_INT);
        $numVehiculos->bindParam(':modelo', $modelo, PDO::PARAM_STR);
        $numVehiculos->bindParam(':motor', $motor, PDO::PARAM_STR);
        $numVehiculos->bindParam(':cilindrada', $cilindrada, PDO::PARAM_STR);
        $numVehiculos->bindParam(':ano', $ano, PDO::PARAM_STR);
        $numVehiculos->setFetchMode(PDO::FETCH_ASSOC); 
        $numVehiculos->execute();
        $numVehiculos = $numVehiculos->fetch();
        $numVehiculos = $numVehiculos['total'];

        //Si no hay coincidencia, se agrega el registro
        if( $numVehiculos == 0 ){
            $insertar_vehiculo = $base_de_datos->prepare("INSERT INTO aplicacion_vehiculo (id_marca, modelo, motor, cilindrada, ano, sincronizado) VALUES (?, ?, ?, ?, ?, ?)");
            $insertar_vehiculo->execute($argumentos);
            $_SESSION['nuevo'] = true;
            header("location: ./editar.php?id=$id");
        }
        //Si hay coincidencia, no se agrega el registro
        else {
            $_SESSION['existencia'] = true;
            header("location: ./editar.php?id=$id");
        }
    }
    else {
        $_SESSION['error'] = true;
        header("location: ./editar.php?id=$id");
    }