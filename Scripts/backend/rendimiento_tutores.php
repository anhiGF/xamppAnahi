<?php
// rendimiento_tutores.php
include('conexion.php');

// Consulta para obtener el rendimiento de los tutores
$query = "SELECT u.nombre, u.primer_apellido, COUNT(t.id_tutoria) AS tutorias_realizadas, 
                 AVG(e.puntaje) AS calificacion_promedio
          FROM usuario u
          LEFT JOIN tutoria t ON u.num_control = t.id_tutor
          LEFT JOIN evaluacion e ON t.id_tutoria = e.id_tutoria
          WHERE u.tipo_usuario = 'Tutor'
          GROUP BY u.num_control";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
