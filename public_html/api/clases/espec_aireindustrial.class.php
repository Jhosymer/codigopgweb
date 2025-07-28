<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde espec_aireindustrial.class.php<br>";

   
    class espec_aireindustrial extends conexion{
       
        private $table  ="espec_aireindustrial";
        private $id     ="";
        private $codigo ="";

        // funciones GET
        public function listarespec_aireindustrial(){
            $_respuestas = new respuestas;
            $query = "SELECT ec.*, fc.filtracion, fc.und_empaque, fc.codigo_barra FROM " . $this->table . " ec LEFT JOIN filtro_codificacion fc ON ec.id_codigo = fc.id WHERE ec.deleted_at IS NULL";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarespec_aireindustrialId($id){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where id='$id'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }

        public function listarespec_aireindustrialCodigo($codigo){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where codigo like '%$codigo%'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
         
        public function listarespec_aireindustrialTotal(){
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