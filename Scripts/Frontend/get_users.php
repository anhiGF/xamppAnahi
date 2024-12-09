<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "root"; 
$password = "ARNIAK123"; 
$dbname = "tutorias";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

// Consulta para obtener todos los usuarios
$sql = "SELECT num_control, nombre, correo_electronico, tipo_usuario FROM usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = [
            "num_control" => $row["num_control"],
            "nombre" => $row["nombre"],
            "email" => $row["correo_electronico"],
            "rol" => $row["tipo_usuario"]
        ];
    }

    echo json_encode(["success" => true, "users" => $users]);
} else {
    echo json_encode(["success" => false, "message" => "No se encontraron usuarios."]);
}

$conn->close();
?>
