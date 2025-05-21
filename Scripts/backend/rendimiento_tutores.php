<?php
session_start();
include("conexion.php");
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

// Consulta utilizando la vista
$sql = "SELECT * FROM VistaRendimientoTutores";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$tutores = [];
while ($row = $result->fetch_assoc()) {
    $row['calificacion_promedio'] = number_format($row['calificacion_promedio'], 2);
    $tutores[] = $row;
}

$stmt->close();
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($tutores);
?>
