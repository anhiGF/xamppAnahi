<?php
include("conexion.php");
if (isset($_GET['num_control'])) {
    $num_control = $_GET['num_control'];

    $sql = "SELECT * FROM Usuario WHERE num_control = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $num_control);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        echo json_encode($usuario);
    } else {
        echo json_encode(["error" => "Usuario no encontrado"]);
    }

    $stmt->close();
    $conexion->close();
}
?>
