<?php
session_start(); 
$conexion = Conexion::getInstancia();
// Verifica si el usuario está autenticado
if (!isset($_SESSION['num_control'])) {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de entrada
    if (empty($_POST['contenido']) || empty($_POST['id_destinatario'])) {
        echo json_encode(["error" => "Todos los campos son obligatorios"]);
        exit();
    }

    $contenido = htmlspecialchars(trim($_POST['contenido']));
    $id_remitente = $_SESSION['num_control']; 
    $id_destinatario = htmlspecialchars(trim($_POST['id_destinatario']));
    $fecha_envio = date("Y-m-d H:i:s");

    // Inserción en la base de datos
    $sql = "INSERT INTO Mensaje (contenido, fecha_envio, id_remitente, id_destinatario) 
            VALUES (?, ?, ?, ?)";
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssss", $contenido, $fecha_envio, $id_remitente, $id_destinatario);
        if ($stmt->execute()) {
            echo json_encode(["success" => "Mensaje enviado con éxito"]);
        } else {
            echo json_encode(["error" => "Error al enviar mensaje: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error en la preparación de la consulta: " . $conexion->error]);
    }
    $conexion->close();
}
?>
