<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde lista_pedidos.class.php<br>";

   
    class lista_pedidos extends conexion{
       
         private $table  = "lista_pedidos";
        private $table2 = "filtro_codificacion"; // Se añade una nueva variable para la segunda tabla
        private $id     = "";
        private $nombre = "";

        // Funciones GET
        public function listarlista_pedidos() {
            $_respuestas = new respuestas;
            
            // Se modifica la consulta para seleccionar campos específicos y usar un JOIN.
            // Se usa INNER JOIN para traer el 'codigo' de la tabla 'filtro_codificacion'.
            $query = "SELECT 
                        T1.id, 
                        T1.id_pedido,
                        T2.codigo AS codigo_producto,
                        T1.cantidad
                      FROM " . $this->table . " T1
                      INNER JOIN " . $this->table2 . " T2 ON T1.id_producto = T2.id"; // Se usa la nueva variable
            
            $datos = parent::obtenerDatos($query);
            return($datos);
        }
        
        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>