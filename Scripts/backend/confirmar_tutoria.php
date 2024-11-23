<?php
session_start();
include("conexion.php");
 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tutoria'])) {
    $id_tutoria = intval($_POST['id_tutoria']);
    $sql = "UPDATE Tutoria SET estado = 'Completada' WHERE id_tutoria = ?";
    $usuario_id = $_SESSION['num_control'];
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id_tutoria);
        if ($stmt->execute()) {
             // Registrar la acción en el historial
             $accion = "confirmar";
             $sql_historial = "INSERT INTO HistorialModificaciones (id_tutoria, accion, usuario_id) VALUES (?, ?, ?)";
             $stmt_historial = $conexion->prepare($sql_historial);
             $stmt_historial->bind_param("isi", $id_tutoria, $accion, $usuario_id);
             $stmt_historial->execute();
             $stmt_historial->close();
            echo "Tutoría confirmada exitosamente.";
        } else {
            echo "Error al confirmar la tutoría: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
