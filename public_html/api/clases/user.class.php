<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class users extends conexion {
    private $table = "users";

    public function listarusers() {
        
         $query = "SELECT * FROM " . $this->table . " WHERE rol = 2 AND (id = 34 OR id = 51 OR id=49 OR id = 40)";
        $datos = parent::obtenerDatos($query);
        return $datos;
    }

   
}
?>