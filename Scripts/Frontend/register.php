<?php
header('Content-Type: application/json');
include("conexion.php");

// Verifica la conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Captura los datos enviados por POST
$data = json_decode(file_get_contents("php://input"), true);

// Verifica si faltan datos obligatorios
if (!isset($data['num_control'], $data['nombre'], $data['primer_apellido'], $data['correo_electronico'], $data['contraseña'], $data['tipo_usuario'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$num_control = $data['num_control'];
$nombre = $data['nombre'];
$primer_apellido = $data['primer_apellido'];
$segundo_apellido = isset($data['segundo_apellido']) ? $data['segundo_apellido'] : null;
$correo_electronico = $data['correo_electronico'];
$contraseña = password_hash($data['contraseña'], PASSWORD_DEFAULT);
$semestre = isset($data['semestre']) ? $data['semestre'] : null;
$fecha_nac = isset($data['fecha_nac']) ? $data['fecha_nac'] : null;
$tipo_usuario = $data['tipo_usuario'];
$fecha_registro = date("Y-m-d");
$ultima_sesion = null; // Puedes ajustar si deseas establecer una fecha por defecto
// Verificar si el número de control ya está registrado
$sql_check_num_control = "SELECT COUNT(*) FROM usuario WHERE num_control = ?";
$stmt_check_num_control = $conexion->prepare($sql_check_num_control);
$stmt_check_num_control->bind_param("s", $num_control);
$stmt_check_num_control->execute();
$stmt_check_num_control->bind_result($count_num_control);
$stmt_check_num_control->fetch();
$stmt_check_num_control->close();

if ($count_num_control > 0) {
    echo json_encode(["success" => false, "message" => "El número de control ya está registrado."]);
    exit();
}

// Verificar si el correo electrónico ya está registrado
$sql_check_email = "SELECT COUNT(*) FROM usuario WHERE correo_electronico = ?";
$stmt_check_email = $conexion->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $correo_electronico);
$stmt_check_email->execute();
$stmt_check_email->bind_result($count_email);
$stmt_check_email->fetch();
$stmt_check_email->close();

if ($count_email > 0) {
    echo json_encode(["success" => false, "message" => "El correo electrónico ya está registrado."]);
    exit();
}

// Inserta el usuario en la base de datos
$sql = "INSERT INTO usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro, ultima_sesion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssssss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro, $ultima_sesion);

// Depuración: Verifica los datos antes de la ejecución
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error al preparar la consulta: " . $conexion->error]);
    exit();
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registro exitoso."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al registrar: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
