<?php
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_control'])) {
    $num_control = $_POST['num_control'];

    try {
        // Inicia la transacción
        $conexion->begin_transaction();

        $sql = "DELETE FROM Usuario WHERE num_control = ?";
        $stmt = $conexion->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
        }

        $stmt->bind_param("i", $num_control);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el usuario: " . $stmt->error);
        }

        // Verifica si se eliminó alguna fila
        if ($stmt->affected_rows === 0) {
            throw new Exception("El usuario con número de control $num_control no existe.");
        }

        // Confirma la transacción
        $conexion->commit();
        echo "Usuario eliminado exitosamente.";

    } catch (Exception $e) {
        // Reversión en caso de error
        $conexion->rollback();
        echo "Error: " . $e->getMessage();
    } finally {
        // Cierra la declaración y la conexión
        if (isset($stmt)) {
            $stmt->close();
        }
        $conexion->close();
    }
}
?>