<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde aplicacion_comercial.class.php<br>";

   
    class aplicacion_comercial extends conexion{
       
        private $table  ="aplicacion_comercial";
        private $id     ="";
        private $codigo ="";

        // funciones GET
        public function listaraplicacion_comercial(){
            $_respuestas = new respuestas;
            $query = "SELECT a.id, a.id_tipo, a_v.modelo, a_v.motor,a_v.ano, a_v.cilindrada, a_m.marca, a.aplicacion, a.codigo, a.id_codigo, a.detalle,a.id_vehiculo FROM aplicacion as a JOIN aplicacion_marca as a_m ON a_m.id = a.id_marca JOIN aplicacion_vehiculo as a_v ON a_v.id = a.id_vehiculo WHERE (a.id_tipo = 2) and (a.deleted_at is null) ORDER BY a.id ASC";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
       
        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>