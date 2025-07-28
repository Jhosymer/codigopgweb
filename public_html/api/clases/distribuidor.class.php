<?php

    require_once "conexion/conexion.php";
    require_once "respuestas.class.php";
   // echo "hola desde distribuidor.class.php<br>";

   
    class distribuidor extends conexion{
       

        // funciones GET
        public function listardistribuidor(){
            $_respuestas = new respuestas;
            $query = "
                    SELECT 
            d.id AS distribuidora_id,
            d.nombre AS distribuidora_nombre,
            d.email AS distribuidora_email,
            d.email2 AS distribuidora_email2,
            d.pais AS distribuidora_pais,
            d.telefono AS distribuidora_telefono,
            d.telefono2 AS distribuidora_telefono2,
            d.calificacion AS distribuidora_calificacion,
            d.facebook AS distribuidora_facebook,
            d.twitter AS distribuidora_twitter,
            d.instagram AS distribuidora_instagram,
            d.direcmaps AS distribuidora_direcmaps,
            d.video_instagram AS distribuidora_video_instagram,
            GROUP_CONCAT(CASE WHEN de.principal = 1 THEN de.estado END) AS estado_principal_1,
            GROUP_CONCAT(CASE WHEN de.principal = 1 THEN de.ciudad END) AS ciudad_principal_1,
            GROUP_CONCAT(CASE WHEN de.principal = 1 THEN de.direccion END) AS direccion_principal_1,
            GROUP_CONCAT(CASE WHEN de.principal = 2 THEN de.estado END) AS estado_principal_2,
            GROUP_CONCAT(CASE WHEN de.principal = 2 THEN de.ciudad END) AS ciudad_principal_2,
            GROUP_CONCAT(CASE WHEN de.principal = 2 THEN de.direccion END) AS direccion_principal_2,
            GROUP_CONCAT(CASE WHEN de.principal = 0 THEN de.estado END) AS estado_distribucion,
            GROUP_CONCAT(CASE WHEN de.principal = 0 THEN de.ciudad END) AS ciudad_distribucion,
            GROUP_CONCAT(CASE WHEN de.principal = 0 THEN de.direccion END) AS direccion_distribucion
        FROM 
            distribuidoras d
        LEFT JOIN 
            distribuidora_estado de ON d.id = de.id_distribuidora
        WHERE 
            d.deleted_at IS NULL
            AND de.deleted_at IS NULL
        GROUP BY 
            d.id
                ";

    $datos = parent::obtenerDatos($query);
    return $datos;
        }
          

    }


?>