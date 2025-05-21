<?php
session_start();
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tutoria'])) {
    $id_tutoria = intval($_POST['id_tutoria']);

    try {
        // Inicia la transacción
        $conexion->begin_transaction();

        // Elimina los registros en el historial
        $sql_historial = "DELETE FROM HistorialModificaciones WHERE id_tutoria = ?";
        $stmt_historial = $conexion->prepare($sql_historial);
        if (!$stmt_historial) {
            throw new Exception("Error en la preparación de la consulta de historial: " . $conexion->error);
        }
        $stmt_historial->bind_param("i", $id_tutoria);
        if (!$stmt_historial->execute()) {
            throw new Exception("Error al eliminar el historial: " . $stmt_historial->error);
        }
        $stmt_historial->close();

        // Elimina la tutoría
        $sql = "DELETE FROM Tutoria WHERE id_tutoria = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de tutoría: " . $conexion->error);
        }
        $stmt->bind_param("i", $id_tutoria);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar la tutoría: " . $stmt->error);
        }
        $stmt->close();

        // Confirma la transacción
        $conexion->commit();
        echo "Tutoría y su historial eliminados exitosamente.";
    } catch (Exception $e) {
        // Si ocurre un error, revierte la transacción
        $conexion->rollback();
        echo "Error al eliminar: " . $e->getMessage();
    } finally {
        // Cierra la conexión
        $conexion->close();
    }
}
?>