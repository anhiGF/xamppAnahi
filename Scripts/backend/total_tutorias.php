<?php
session_start();
include("conexion.php");
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

// Llamar al procedimiento almacenado
$sql = "CALL ObtenerTotalTutorias(@total)";
if (!$conexion->query($sql)) {
    echo json_encode(["error" => "Error en la ejecución del procedimiento: " . $conexion->error]);
    exit();
}

// Obtener el valor de la variable de salida
$result = $conexion->query("SELECT @total AS total_tutorias");
$row = $result->fetch_assoc();

$totalTutorias = $row['total_tutorias'];

$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode(["total_tutorias" => $totalTutorias]);
?>
