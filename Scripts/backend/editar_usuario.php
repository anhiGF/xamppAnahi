<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_control = $_POST['num_control'];
    $nombre = $_POST['nombre'];
    $primer_apellido = $_POST['primer_apellido'];
    $segundo_apellido = $_POST['segundo_apellido'];
    $correo_electronico = $_POST['correo_electronico'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $semestre = isset($_POST['semestre']) ? $_POST['semestre'] : null;
    $fecha_nac = $_POST['fecha_nac'];

    if ($tipo_usuario === 'Tutor' || $tipo_usuario === 'Administrador') {
        $semestre = null;
    }

    $sql = "UPDATE Usuario SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, correo_electronico = ?, tipo_usuario = ?, semestre = ?, fecha_nac = ? WHERE num_control = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssisi", $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $tipo_usuario, $semestre, $fecha_nac, $num_control);

    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
