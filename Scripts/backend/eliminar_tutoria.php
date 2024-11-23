<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tutoria'])) {
    $id_tutoria = intval($_POST['id_tutoria']);

    // Elimina los registros en el historial primero
    $sql_historial = "DELETE FROM HistorialModificaciones WHERE id_tutoria = ?";
    $stmt_historial = $conexion->prepare($sql_historial);
    $stmt_historial->bind_param("i", $id_tutoria);
    $stmt_historial->execute();
    $stmt_historial->close();

    // Ahora elimina la tutoría
    $sql = "DELETE FROM Tutoria WHERE id_tutoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_tutoria);

    if ($stmt->execute()) {
        echo "Tutoría y su historial eliminados exitosamente.";
    } else {
        echo "Error al eliminar la tutoría: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
