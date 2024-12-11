<?php
session_start();
include("conexion.php");

// Verifica si la sesión contiene el ID del usuario
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

// Prepara la consulta SQL para obtener el total de tutorías
$sql = "SELECT COUNT(*) AS total_tutorias FROM Tutoria";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalTutorias = $row['total_tutorias'];

$stmt->close();
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode(["total_tutorias" => $totalTutorias]);
?>
