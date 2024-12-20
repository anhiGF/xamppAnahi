<?php
session_start();
include("conexion.php");

// Verifica si la sesión contiene el ID del usuario
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$sql = "SELECT AVG(puntaje) AS satisfaccion_promedio FROM Evaluacion"; 
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$satisfaccionPromedio = $row['satisfaccion_promedio'];

$stmt->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode(["satisfaccion_promedio" => number_format($satisfaccionPromedio, 2)]);  // Se formatea el promedio con 2 decimales
?>
