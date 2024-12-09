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

if (!isset($data['num_control'], $data['nombre'], $data['primer_apellido'], $data['correo_electronico'], $data['contraseña'], $data['tipo_usuario'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$num_control = $data['num_control'];
$nombre = $data['nombre'];
$primer_apellido = $data['primer_apellido'];
$segundo_apellido = $data['segundo_apellido'] ?? null;
$correo_electronico = $data['correo_electronico'];
$contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT);
$semestre = $data['semestre'] ?? null;
$fecha_nac = $data['fecha_nac'] ?? null;
$tipo_usuario = $data['tipo_usuario'];
$fecha_registro = date("Y-m-d");

// Inserta el usuario en la base de datos
$sql = "INSERT INTO usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssssss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registro exitoso."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
