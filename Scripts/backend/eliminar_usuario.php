<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_control'])) {
    $num_control = $_POST['num_control'];

    $sql = "DELETE FROM Usuario WHERE num_control = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $num_control);

    if ($stmt->execute()) {
        echo "Usuario eliminado exitosamente.";
    } else {
        echo "Error al eliminar el usuario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>
