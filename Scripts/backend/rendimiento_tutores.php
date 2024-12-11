<?php
session_start();
include("conexion.php");

// Verifica si la sesión contiene el ID del usuario
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

// Prepara la consulta SQL para obtener el rendimiento de los tutores (solo los usuarios con tipo_usuario = 'Tutor')
$sql = "
    SELECT 
        u.nombre,
        COUNT(t.id_tutoria) AS tutorias_realizadas,
        AVG(e.puntaje) AS calificacion_promedio
    FROM Usuario u
    LEFT JOIN Tutoria t ON u.num_control = t.id_tutor
    LEFT JOIN Evaluacion e ON t.id_tutoria = e.id_tutoria
    WHERE u.tipo_usuario = 'Tutor'  -- Filtrar solo los tutores
    GROUP BY u.num_control
";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();

$tutores = [];
while ($row = $result->fetch_assoc()) {
    // Verificar si la calificación promedio es nula (si no hay evaluaciones) y asignar 0
    $row['calificacion_promedio'] = $row['calificacion_promedio'] ? number_format($row['calificacion_promedio'], 2) : '0.00';
    $tutores[] = $row;
}

$stmt->close();
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($tutores);
?>
