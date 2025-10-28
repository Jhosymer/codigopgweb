<?php
$contraseña = "";
$usuario = "root";
$nombreBaseDeDatos = "robot_chat";
$rutaServidor = "localhost";

try {
    $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las preguntas
    $query = $base_de_datos->prepare("SELECT palabra_clave, id, id_respuesta FROM pregunta");
    $query->execute();
    $preguntas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Crear un array para almacenar las terminaciones y respuestas
    $terminaciones = [];
    $respuestas = [];

    // Obtener las terminaciones y respuestas
    foreach ($preguntas as $pregunta) {
        $idPregunta = $pregunta['id'];
  
        // Obtener terminaciones para la pregunta actual
        $queryTerm = $base_de_datos->prepare("SELECT terminacion FROM terminacion WHERE id_pregunta = :idPregunta");
        $queryTerm->bindParam(':idPregunta', $idPregunta);
        $queryTerm->execute();
        $terminaciones[$pregunta['palabra_clave']] = $queryTerm->fetchAll(PDO::FETCH_COLUMN);

        // Obtener respuesta para la pregunta actual
        $idRespuesta = $pregunta['id_respuesta'];
        $queryResp = $base_de_datos->prepare("SELECT respuesta, url_link, link FROM respuestas WHERE id = :idRespuesta");
        $queryResp->bindParam(':idRespuesta', $idRespuesta);
        $queryResp->execute();
        $respuestas[$idPregunta] = $queryResp->fetch(PDO::FETCH_ASSOC);
    }


    
    // Pasar los datos a JavaScript
    echo "<script>
        const preguntas = " . json_encode($preguntas) . ";
        const terminaciones = " . json_encode($terminaciones) . ";
        const respuestas = " . json_encode($respuestas) . ";
    </script>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
