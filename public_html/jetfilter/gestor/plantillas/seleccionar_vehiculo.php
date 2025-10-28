<?php 
    /*-------ARCHIVO PARA BUSCAR EL CODIGO DEL SELECT DE LOS VEHICULOS DE APLICACIÓN, DE ACUERDO A LA MARCA------- */
    //NECESITA: El parametro marca
    //RETORNA: La variable $output['vehiculo'], que lleva el codigo HTML para colocarse en el select de escoger vehículo

    //Necesita la variable marca para poder ejecutarse
    if( isset($_POST['marca']) ){
        include_once('../../../config/conexion.php');

        $marca = json_decode( $_POST['marca'] );
        
        //Consulta para Buscar Vehículos con la marca seleccionada
        $aplicacion_vehiculo = $base_de_datos->prepare("SELECT id, modelo, motor, cilindrada
                                                        FROM aplicacion_vehiculo
                                                        WHERE id_marca = :marca
                                                        ORDER BY modelo ASC");
        $aplicacion_vehiculo->bindParam(':marca', $marca, PDO::PARAM_INT); 
        $aplicacion_vehiculo->setFetchMode(PDO::FETCH_ASSOC);                                               
        $aplicacion_vehiculo->execute();

        //Se crea la variable output
        $output = [];
        $output['vehiculo'] = '';

        $output['vehiculo'] .= "<option value='' disabled selected >Modelo -- Motor - Cilindrada</option>";
        //Se insertan un vehículo en cada ciclo
        while( $vehiculo = $aplicacion_vehiculo->fetch() ){
            $id = $vehiculo['id'];
            $modelo = $vehiculo['modelo'];
            $motor = $vehiculo['motor'];
            $cilindrada = $vehiculo['cilindrada'];
            $output['vehiculo'] .= "<option value='$id' >$modelo -- $motor -- $cilindrada</option>";
        }
        $output['vehiculo'] .= "<option value='Otro' >Agregar Vehículo</option>";

        echo json_encode($output);
    }
    else {
        header('location: ./../especificaciones.php');
    }

?>