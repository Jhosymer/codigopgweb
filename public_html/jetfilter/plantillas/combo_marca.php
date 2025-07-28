<?php
// Conexión a la base de datos y consulta
$servername = "localhost";
$username = "webfiltr_admin";
$password = "123abc*1";
$dbname = "webfiltr_webfiltros";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "select * from aplicacion_marca where (deleted_at is null) ORDER BY marca ASC";
$result = $conn->query($sql);

// Obtener los datos y devolverlos como JSON
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

// Devolver los datos como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>