<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde categorias.class.php<br>";

   
    class productos extends conexion{
       
        private $table  ="productos";
        private $id     ="";
        private $nombre ="";

        // funciones GET
        public function listarProductos(){
            if(empty($_GET)){
                $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where deleted_at IS NULL";
            $datos=parent::obtenerDatos($query);
            return($datos);
            }
            
        }
        public function listarProductosNombre($nombre){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " WHERE nombre like '%$nombre%'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarProductosId($id){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where idproducto='$id'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }

        public function listarProductosSeg($idseg){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " WHERE idsegmento='$idseg'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
        public function listarProductosMar($idmar){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where idmarcavehiculo='$idmar'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }

        public function listarProductosTotal(){
            $_respuestas = new respuestas;
           // $query ="SELECT count(*) FROM " . $this->table ;
            $query ="SELECT count(*) as totalproductos,sum(existencia) as existenciatotal,sum(existencia * precio3) as totaldolar FROM " . $this->table;
            $datos=parent::obtenerDatos($query);
            return($datos);
        }



        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>