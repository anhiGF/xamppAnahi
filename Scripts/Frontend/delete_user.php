<?php
header('Content-Type: application/json');
include("conexion.php");

// Verifica la conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Captura el número de control enviado por POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['num_control'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$num_control = $data['num_control'];

// Eliminar usuario
$sql = "DELETE FROM usuario WHERE num_control = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $num_control);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Usuario eliminado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "No se encontró el usuario."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar el usuario: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
