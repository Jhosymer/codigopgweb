<?php 
     /* -----ARCHIVO PARA GUARDAR UN REGISTRO EN AIRE AUTOMOTRIZ-------- */
    date_default_timezone_set('America/Caracas');
    session_start();

    include_once('./../conexion/conexion.php');
    
    //Si se ha enviado un formulario con el campo código
    if( isset( $_POST['codigo'] ) ){

        include_once('./../plantillas/comprobar_existencia_filtro.php');
        //Se ejecuta la funcion, que devolverá el numero de filtros
        //que no esten eliminados que contengas ese código
        //Parametro $codigo (String): El codigo que se va a comprobar.
        //Parametro $base_de_datos (object): Es el objeto que tiene la conexión a la base de datos
        $numeroFiltros = comprobarExistenciaFiltro($codigo, $base_de_datos);

        //Si no existe filtros con ese código se creará
        if( $numeroFiltros == 0 ){
            $descripcion = "";
            $fecha = date("d-m-y");  
            $sincronizado = date("Ymd");
            $id_tipo = $_POST['tipo'];

            //Verificá si se escogio un tipo o se dejo la opción N/D (que no tiene).
            if( $id_tipo == 'N/D' ){
                $tipo = 'N/D';
                $id_tipo = null;
            }
            else {
                //Si se seleccionó se busca en la base de datos y se guarda el id y el tipo
                $seleccionado = $base_de_datos->prepare("SELECT id, tipo
                                                        FROM tipos
                                                        WHERE id = :id_tipo"); 
                $seleccionado->bindParam(':id_tipo', $id_tipo, PDO::PARAM_INT);                                        
                $seleccionado->execute();
                $seleccionado_tipo = $seleccionado->fetch();
                $tipo = $seleccionado_tipo['tipo'];
                $id_tipo = $seleccionado_tipo['id'];
            }

            // Se guardan los elementos enviados por el formulario
            $codigo = $_POST['codigo'];
            $detalle1 = ( $_POST['detalle1'] == '' ) ? 'N/D' : $_POST['detalle1'];
            $detalle2 = ( $_POST['detalle2'] == '' ) ? 'N/D' : $_POST['detalle2'];

            //Se eliminan los caracteres especiales y espacios, para la variable $codigo_buscar.
            $caracteres_a_reemplazar = ['-'," ","_"];
            $codigo_buscar = str_replace($caracteres_a_reemplazar,'',$codigo);
            
            //Componente que guarda en un arreglo $imagen las imagenes que se enviaron del formulario
            //Estan ordenados las imagenes en el arreglo (0 => imagen, 1 => imagen1, 2 => imagen2, 3 => imagen3)
            include_once('./../componentes/galeria_update.php');

            //Busca el id del ultimo filtro y le suma 1. Ese sera el valor del $id_codigo
            $sql = "SELECT MAX(id) FROM filtro_codificacion";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->execute();
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC);
            $id_codigo = $seleccionado->fetch();
            $max_id_codigo = $id_codigo['MAX(id)'] + 1;

            //Datos a guardar en la tabla de fluidos
            $argumentos = [$max_id_codigo, $codigo, $codigo_buscar, $tipo, $detalle1, $detalle2, $sincronizado, $imagen[0], $imagen[1], $imagen[2], $imagen[3]];
            //Datos a guardar en la tabla de filtro codificación
            $argumentos_filtro_codificacion = [$max_id_codigo, $codigo, $codigo_buscar, $id_tipo, $descripcion, $fecha, $sincronizado];

            try {
                //Se crearan los registros en la tabla de aire automotriz y de filtro codificación.
                //En caso de que falle alguna subida, se cancelara todo
                $base_de_datos->beginTransaction();

                $sql = "INSERT INTO espec_fluidos (id_codigo, codigo, codigo_buscar, tipo, detalle1, detalle2, sincronizado, imagen, imagen1, imagen2, imagen3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $seleccionado = $base_de_datos->prepare($sql);
                $seleccionado->execute($argumentos);

                $sql = "INSERT INTO filtro_codificacion (id, clase, codigo, codigo_buscar, id_tipo, descripcion, precio, und_empaque, fecha_actualiza, sincronizado, deleted_at, updated_at) VALUES (?, 'fluidos', ?, ?, ?, ?, 0, 0, ?, ?, null, null)";
                $seleccionado = $base_de_datos->prepare($sql);
                $seleccionado->execute($argumentos_filtro_codificacion);
                
                //Si todo va bien
                $base_de_datos->commit();

                $_SESSION['nuevo'] = true;
                header("location: espec_fluidos.php") or die('Error');
            }
            catch(PDOException $exception){
                //En case de que suceda algún error
                $base_de_datos->rollback();
                $_SESSION['error'] = true;
                header("location: ./nuevo.php");
            }
        }
        //Si ya existe filtro, te regresa y da una alerta
        else {
            $_SESSION['yaExistencia'] = true;
            header("location: ./nuevo.php");
        }
    }
    //Si no se ha enviado un formulario con el campo código
    else {
        header("location: ./nuevo.php");
    }