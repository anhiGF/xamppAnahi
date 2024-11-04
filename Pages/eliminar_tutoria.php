<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_tutoria'])) {
    $id_tutoria = intval($_POST['id_tutoria']);
    $sql = "DELETE FROM Tutoria WHERE id_tutoria = ?";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id_tutoria);
        if ($stmt->execute()) {
             
            echo "Tutoría eliminada exitosamente.";
        } else {
            echo "Error al eliminar la tutoría: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>

