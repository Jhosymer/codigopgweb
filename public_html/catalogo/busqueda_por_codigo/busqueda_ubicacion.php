<?php
            $token = '89656c6690d8ff'; // Reemplaza con tu token de acceso
            $Ciudad = '';
            $estado = '';

            $ip = $_SERVER['REMOTE_ADDR'];
        $ip_datos_json = @file_get_contents("https://ipinfo.io/$ip/json?token=$token");
        $ip_datos = json_decode($ip_datos_json);

        if ($ip_datos && isset($ip_datos->city)) {
            $Ciudad = $ip_datos->city;
        }

        if ($ip_datos && isset($ip_datos->region)) {
            $estado = $ip_datos->region;
        }
        ?>