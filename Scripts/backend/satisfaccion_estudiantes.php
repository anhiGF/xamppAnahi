<?php
// satisfaccion_estudiantes.php
include('conexion.php');

// Consulta para obtener la satisfacción promedio de los estudiantes
$query = "SELECT AVG(puntaje) AS satisfaccion_promedio
          FROM evaluacion e
          JOIN tutoria t ON e.id_tutoria = t.id_tutoria
          JOIN usuario u ON t.id_estudiante = u.num_control
          WHERE u.tipo_usuario = 'Estudiante'";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
