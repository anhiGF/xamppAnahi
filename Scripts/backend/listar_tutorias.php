<?php
session_start();
include("conexion.php");

// Verifica si la sesión contiene el ID del estudiante
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$id_estudiante = $_SESSION['num_control'];

// Prepara la consulta SQL
$sql = "SELECT id_tutoria, titulo, descripcion, fecha, hora, estado, id_tutor FROM Tutoria WHERE id_estudiante = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    exit();
}

$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$result = $stmt->get_result();

$tutorias = [];
while ($row = $result->fetch_assoc()) {
    $tutorias[] = $row;
}

$stmt->close();
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($tutorias);
?>
