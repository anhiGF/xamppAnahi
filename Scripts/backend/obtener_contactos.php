<?php
session_start();
include("conexion.php");

header('Content-Type: application/json');

if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$userId = $_SESSION['num_control'];

// Consulta para obtener los contactos
$query = "
    SELECT DISTINCT u.num_control, u.nombre, u.primer_apellido 
    FROM Usuario u
    JOIN Tutoria t ON (u.num_control = t.id_tutor OR u.num_control = t.id_estudiante)
    WHERE (t.id_tutor = ? OR t.id_estudiante = ?)
    AND u.num_control != ?";

if ($stmt = $conexion->prepare($query)) {
    $stmt->bind_param("iii", $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $contactos = [];
    while ($row = $result->fetch_assoc()) {
        $contactos[] = $row;
    }

    echo json_encode($contactos);
    $stmt->close();
} else {
    echo json_encode(["error" => "Error en la consulta SQL: " . $conexion->error]);
}
$conexion->close();
?>
