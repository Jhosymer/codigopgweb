<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde filtro_codificacion.class.php<br>";

   
    class filtro_codificacion extends conexion{
       
        private $table  ="filtro_codificacion";
        private $id     ="";
        private $nombre ="";

        // funciones GET
        public function listarfiltro_codificacion(){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where deleted_at is null ";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarfiltro_codificacionId($id){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where id='$id' and deleted_at is null";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
         
        public function listarfiltro_codificacionTotal(){
            $_respuestas = new respuestas;
            $query ="SELECT count(*) FROM " . $this->table . " WHERE deleted_at is null" ;
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarfiltro_codificacionNombre($nombre){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " WHERE codigo like '%$nombre%' and deleted_at is null";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>