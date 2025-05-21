<?php
session_start();
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tutoria'])) {
    $id_tutoria = intval($_POST['id_tutoria']);
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $usuario_id = $_SESSION['num_control'];

    $sql = "UPDATE Tutoria SET titulo = ?, descripcion = ?, fecha = ?, hora = ? WHERE id_tutoria = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssi", $titulo, $descripcion, $fecha, $hora, $id_tutoria);
        if ($stmt->execute()) {
            // Registrar la acción en el historial
            $accion = "editar";
            $sql_historial = "INSERT INTO HistorialModificaciones (id_tutoria, accion, usuario_id) VALUES (?, ?, ?)";
            $stmt_historial = $conexion->prepare($sql_historial);
            $stmt_historial->bind_param("isi", $id_tutoria, $accion, $usuario_id);
            $stmt_historial->execute();
            $stmt_historial->close();

            echo "Tutoría actualizada exitosamente.";
        } else {
            echo "Error al actualizar la tutoría: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
