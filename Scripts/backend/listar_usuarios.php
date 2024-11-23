<?php
include("conexion.php");

// Consulta para obtener todos los usuarios
$sql = "SELECT num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, tipo_usuario FROM Usuario";
$result = $conexion->query($sql);

$usuarios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

// Cierra la conexión
$conexion->close();

// Envía los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
?>
