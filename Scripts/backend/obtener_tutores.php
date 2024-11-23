<?php
include("conexion.php");

// Consulta para obtener solo los usuarios con el rol de "Tutor"
$sql = "SELECT num_control, nombre, primer_apellido, segundo_apellido FROM Usuario WHERE tipo_usuario = 'Tutor'";
$result = $conexion->query($sql);

$tutores = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tutores[] = $row;
    }
}

// Cierra la conexión
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($tutores);
?>
