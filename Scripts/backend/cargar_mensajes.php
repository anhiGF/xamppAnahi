<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

// Obtener el id_remitente desde la sesión
$id_remitente = $_SESSION['num_control'];

// Obtener el id_destinatario desde los parámetros GET
$id_destinatario = isset($_GET['id_destinatario']) ? $_GET['id_destinatario'] : null;

if (!$id_destinatario) {
    echo json_encode(["error" => "No se especificó un destinatario"]);
    exit();
}

// Consultar los mensajes entre el remitente y el destinatario
$query = "SELECT * FROM Mensaje WHERE (id_remitente = ? AND id_destinatario = ?) OR (id_remitente = ? AND id_destinatario = ?) ORDER BY fecha_envio ASC";
$stmt = $conexion->prepare($query);
$stmt->bind_param("iiii", $id_remitente, $id_destinatario, $id_destinatario, $id_remitente);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];

while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

$stmt->close();

// Enviar los mensajes como JSON
echo json_encode($mensajes);
?>
