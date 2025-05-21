<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

$id_remitente = $_SESSION['num_control'];
$id_destinatario = htmlspecialchars(trim($_GET['id_destinatario']));

if (!$id_destinatario) {
    echo json_encode(["error" => "No se especificó un destinatario"]);
    exit();
}

// Consulta SQL para obtener mensajes
$query = "SELECT * FROM Mensaje 
          WHERE (id_remitente = ? AND id_destinatario = ?) 
          OR (id_remitente = ? AND id_destinatario = ?) 
          ORDER BY fecha_envio ASC";

if ($stmt = $conexion->prepare($query)) {
    $stmt->bind_param("ssss", $id_remitente, $id_destinatario, $id_destinatario, $id_remitente);
    $stmt->execute();
    $result = $stmt->get_result();

    $mensajes = [];
    while ($row = $result->fetch_assoc()) {
        $mensajes[] = $row;
    }
    echo json_encode($mensajes);
    $stmt->close();
} else {
    echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
}

$conexion->close();
?>
