<?php
session_start();
include("conexion.php");

// Verifica si el usuario está autenticado
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenido = trim($_POST['contenido']);
    $id_remitente = $_SESSION['num_control']; // Usamos el ID de la sesión como remitente
    $id_destinatario = intval($_POST['id_destinatario']);
    $fecha_envio = date("Y-m-d H:i:s");

    // Inserta el mensaje en la base de datos
    $sql = "INSERT INTO Mensaje (contenido, fecha_envio, id_remitente, id_destinatario) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssii", $contenido, $fecha_envio, $id_remitente, $id_destinatario);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Mensaje enviado"]);
    } else {
        echo json_encode(["error" => "Error al enviar mensaje: " . $stmt->error]);
    }

    $stmt->close();
    $conexion->close();
}
?>
