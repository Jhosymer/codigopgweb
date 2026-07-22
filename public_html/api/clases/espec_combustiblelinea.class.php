<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde espec_combustiblelinea.class.php<br>";

   
    class espec_combustiblelinea extends conexion{
       
        private $table = "espec_combustiblelinea";
        private $table1 = "roscas"; // Tabla de roscas
        private $table2 = "pulgadas"; // Tabla de pulgadas
        private $id = "";
        private $codigo = "";

        // funciones GET
       public function listarespec_combustiblelinea(){
    $_respuestas = new respuestas;
    $query = "SELECT 
            ec.*, 
            fc.filtracion, 
            fc.und_empaque, 
            fc.codigo_barra,
            r_ent.codigo AS rosca_entrada,
            r_sal.codigo AS rosca_salida,
            p_ent.codigo AS pulgada_entrada,
            p_sal.codigo AS pulgada_salida
          FROM " . $this->table . " ec 
          LEFT JOIN filtro_codificacion fc ON ec.id_codigo = fc.id 
          LEFT JOIN " . $this->table1 . " r_ent ON ec.id_rosca_entrada = r_ent.id
          LEFT JOIN " . $this->table1 . " r_sal ON ec.id_rosca_salida = r_sal.id
          LEFT JOIN " . $this->table2 . " p_ent ON ec.id_pulgada_entrada = p_ent.id
          LEFT JOIN " . $this->table2 . " p_sal ON ec.id_pulgada_salida = p_sal.id
          WHERE ec.deleted_at IS NULL";
          
    $datos = parent::obtenerDatos($query);
    return($datos);
}
        public function listarespec_combustiblelineaId($id){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where id='$id'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }

        public function listarespec_combustiblelineaCodigo($codigo){
            $_respuestas = new respuestas;
            $query ="SELECT * FROM " . $this->table . " where codigo like '%$codigo%'";
            $datos=parent::obtenerDatos($query);
            return($datos);
        }
         
        public function listarespec_combustiblelineaTotal(){
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