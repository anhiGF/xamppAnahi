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

// Captura los datos enviados por POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['num_control'], $data['nombre'], $data['primer_apellido'], $data['correo_electronico'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$num_control = $data['num_control'];
$nombre = $data['nombre'];
$primer_apellido = $data['primer_apellido'];
$segundo_apellido = $data['segundo_apellido'] ?? null; // Opcional
$correo_electronico = $data['correo_electronico'];
$semestre = $data['semestre'] ?? null; // Opcional

// Actualizar usuario
$sql = "UPDATE usuario SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, correo_electronico = ?, semestre = ? WHERE num_control = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $semestre, $num_control);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el usuario: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>