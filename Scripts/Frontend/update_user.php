<?php
header('Content-Type: application/json');

include("conexion.php");

// Verifica la conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Captura los datos enviados por POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica si los datos obligatorios están presentes
if (!isset($data['num_control'], $data['nombre'], $data['primer_apellido'], $data['correo_electronico'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$num_control = $data['num_control'];
$nombre = $data['nombre'];
$primer_apellido = $data['primer_apellido'];
$segundo_apellido = isset($data['segundo_apellido']) ? $data['segundo_apellido'] : null;
$correo_electronico = $data['correo_electronico'];
$semestre = isset($data['semestre']) ? $data['semestre'] : null;

// Validación de correo electrónico
if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "El correo electrónico no es válido."]);
    exit();
}

// Verificar si el correo ya está asociado a otro número de control
$sql_check_email = "SELECT num_control FROM usuario WHERE correo_electronico = ? AND num_control != ?";
$stmt_check_email = $conexion->prepare($sql_check_email);

if ($stmt_check_email === false) {
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta de verificación de correo: " . $conexion->error]);
    exit();
}

$stmt_check_email->bind_param("si", $correo_electronico, $num_control);
$stmt_check_email->execute();
$result = $stmt_check_email->get_result();

// Si el correo ya está registrado con otro número de control, se retorna un error
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está asociado a otro usuario."]);
    exit();
}

$stmt_check_email->close();

// Si el semestre es proporcionado, verificar que sea un número
if ($semestre !== null && !is_numeric($semestre)) {
    echo json_encode(["success" => false, "message" => "El semestre debe ser un número."]);
    exit();
}

// Consulta para actualizar el usuario
$sql = "UPDATE usuario SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, correo_electronico = ?, semestre = ? WHERE num_control = ?";
$stmt = $conexion->prepare($sql);

if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conexion->error]);
    exit();
}

$stmt->bind_param("ssssii", $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $semestre, $num_control);

// Ejecuta la consulta
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Usuario actualizado correctamente."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al actualizar el usuario: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
