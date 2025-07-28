<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde filtro_equivalencia.class.php<br>";

   
    class filtro_equivalencia extends conexion{
       
        private $table  ="filtro_equivalencia";
        private $id     ="";
        private $codigo ="";

        // funciones GET
        public function listarfiltro_equivalencia(){
            $_respuestas = new respuestas;
            $query = "SELECT filtro_equivalencia.*, equivalencia_marca.marca AS nombre_marca, equivalencia_marca.mostrar AS mostrar
            FROM " . $this->table . " AS filtro_equivalencia 
            JOIN equivalencia_marca ON filtro_equivalencia.id_marca = equivalencia_marca.id 
            WHERE filtro_equivalencia.deleted_at IS NULL";
            // $query ="SELECT * FROM " . $this->table . " where deleted_at IS NULL";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarfiltro_equivalenciaId($id){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where id='$id'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }

        public function listarfiltro_equivalenciaCodigo($codigo){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where codigo_marca like '%$codigo%'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
         
        public function listarfiltro_equivalenciaTotal(){
            $_respuestas = new respuestas;
            $query ="SELECT count(*) FROM " . $this->table ;
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>