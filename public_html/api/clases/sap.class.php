<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde espec_producto.class.php<br>";

   
    class espec_producto extends conexion{
       
        private $table  ="oitm";
        private $id     ="";
        private $nombre ="";

        // funciones GET
        public function listarespec_producto(){
            $_respuestas = new respuestas;
            $query ="SELECT p1.ItemCode, p1.ItemName, p1.U_Desc1,p1. U_Esp1, p1.U_Desc2, p1.U_Esp2, p1.U_Desc3, p1.U_Escp3, p1.U_Desc4, p1.U_Esp4,
            p1.U_Desc5, p1.U_Esp5, p1.U_Desc6, p1.U_Esp6, p1.U_webpub, p1.U_webtipo, p1.SalPackUn, p1.CodeBars, p2.ItemName as ItemName_emp, p2.U_Desc1 as U_Desc1_emp, p2. U_Esp1 as U_Esp1_emp, p2.U_Desc2 as U_Desc2_emp, p2.U_Esp2 as U_Esp2_emp , 
            p2.U_Desc3 as  U_Desc3_emp, p2.U_Escp3 as  U_Escp3_emp
            FROM " . $this->table . " p1
            JOIN " . $this->table . " p2 ON p1.U_Esp6 = p2.ItemCode 
            where p1.U_webpub = 'S'";
        
            $datos = parent::obtenerDatos($query);
        
            $_respuestas1 = new respuestas;
            $query1 = "SELECT ItemCode, ItemName, U_Desc1, U_Esp1, U_Desc2, U_Esp2, U_Desc3, U_Escp3, U_Desc4, U_Esp4,
            U_Desc5, U_Esp5, U_Desc6, U_Esp6, U_webpub, U_webtipo, SalPackUn, CodeBars
            FROM " . $this->table ." where U_webpub = 'S'" ;
            $datos1 = parent::obtenerDatos($query1);
        
            // Remove duplicate ItemCode from $datos1
            $uniqueDatos1 = [];
            foreach ($datos1 as $dato1) {
                if (!in_array($dato1['ItemCode'], array_column($datos, 'ItemCode'))) {
                    $uniqueDatos1[] = $dato1;
                }
            }
        
            $result = array_merge($datos, $uniqueDatos1); // Concatenate the two result sets into a single array
        
            return $result;
        }
     
       public function listarespec_productoId($id){
                $_respuestas = new respuestas;
                $query ="SELECT p1.ItemCode, p1.ItemName, p1.U_Desc1,p1. U_Esp1, p1.U_Desc2, p1.U_Esp2, p1.U_Desc3, p1.U_Escp3, p1.U_Desc4, p1.U_Esp4,
                p1.U_Desc5, p1.U_Esp5, p1.U_Desc6, p1.U_Esp6, p1.U_webpub, p1.U_webtipo, p1.SalPackUn, p1.CodeBars, p2.ItemName as ItemName_emp, p2.U_Desc1 as U_Desc1_emp, p2. U_Esp1 as U_Esp1_emp, p2.U_Desc2 as U_Desc2_emp, p2.U_Esp2 as U_Esp2_emp , 
                p2.U_Desc3 as  U_Desc3_emp, p2.U_Escp3 as  U_Escp3_emp
                FROM " . $this->table . " p1
                JOIN " . $this->table . " p2 ON p1.U_Esp6 = p2.ItemCode
                WHERE p1.ItemCode = '$id' "; // Add the condition to check if U_Desc6 is null or empty
            
                $datos = parent::obtenerDatos($query);
            
                $_respuestas1 = new respuestas;
                $query1 = "SELECT ItemCode, ItemName, U_Desc1, U_Esp1, U_Desc2, U_Esp2, U_Desc3, U_Escp3, U_Desc4, U_Esp4,
                U_Desc5, U_Esp5, U_Desc6, U_Esp6, U_webpub, U_webtipo, SalPackUn, CodeBars
                FROM " . $this->table ." where ItemCode = '$id' ";
                $datos1 = parent::obtenerDatos($query1);
            
                // Remove duplicate ItemCode from $datos1
                $uniqueDatos1 = [];
                foreach ($datos1 as $dato1) {
                    if (!in_array($dato1['ItemCode'], array_column($datos, 'ItemCode'))) {
                        $uniqueDatos1[] = $dato1;
                    }
                }
            
                $result = array_merge($datos, $uniqueDatos1); // Concatenate the two result sets into a single array
            
                return $result;
        }
        
     public function listarespec_productoCodigo($codigo){
            $_respuestas = new respuestas;
    
            $query ="SELECT p1.ItemCode, p1.ItemName, p1.U_Desc1,p1. U_Esp1, p1.U_Desc2, p1.U_Esp2, p1.U_Desc3, p1.U_Escp3, p1.U_Desc4, p1.U_Esp4,
            p1.U_Desc5, p1.U_Esp5, p1.U_Desc6, p1.U_webpub, p1.U_webtipo, p1.SalPackUn, p1.p1.CodeBars, p2.ItemName as ItemName_emp, p2.U_Desc1 as U_Desc1_emp, p2. U_Esp1 as U_Esp1_emp, p2.U_Desc2 as U_Desc2_emp, p2.U_Esp2 as U_Esp2_emp , 
            p2.U_Desc3 as  U_Desc3_emp, p2.U_Escp3 as  U_Escp3_emp
            FROM " . $this->table . " p1
            JOIN " . $this->table . " p2 ON p1.U_Esp6 = p2.ItemCode
            WHERE p1.ItemCode like '%$codigo%' "; 
            $datos = parent::obtenerDatos($query);
        
            $_respuestas1 = new respuestas;
            $query1 = "SELECT ItemCode, ItemName, U_Desc1, U_Esp1, U_Desc2, U_Esp2, U_Desc3, U_Escp3, U_Desc4, U_Esp4,
            U_Desc5, U_Esp5, U_Desc6, U_Esp6, U_webpub, U_webtipo, SalPackUn, p1.CodeBars
            FROM " . $this->table ." where ItemCode like '%$codigo%'";
            $datos1 = parent::obtenerDatos($query1);
        
            // Remove duplicate ItemCode from $datos1
            $uniqueDatos1 = [];
            foreach ($datos1 as $dato1) {
                if (!in_array($dato1['ItemCode'], array_column($datos, 'ItemCode'))) {
                    $uniqueDatos1[] = $dato1;
                }
            }
        
            $result = array_merge($datos, $uniqueDatos1); // Concatenate the two result sets into a single array
        
            return $result;
        }

        public function listarespec_productoTotal(){
            $_respuestas = new respuestas;
            $query ="SELECT count(*) FROM " . $this->table . " where U_webpub = 'S'" ;
            $datos=parent::obtenerDatos($query);
            return($datos);

        }
     
                    
        // funciones post para insertar datos (insert)

        // funciones put para modificar datos (update)

        // funciones delete para eliminar datos (delete)

    }









?>