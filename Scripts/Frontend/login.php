<?php
header('Content-Type: application/json');

include("conexion.php");

// Verifica la conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Captura los datos enviados por POST
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['correo_electronico'], $data['contraseña'])) {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
    exit();
}

$correo_electronico = $data['correo_electronico'];
$contraseña = $data['contraseña'];

// Busca al usuario por correo electrónico
$sql = "SELECT * FROM usuario WHERE correo_electronico = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo_electronico);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Verifica la contraseña
    if (password_verify($contraseña, $user['contraseña'])) {
        // Actualiza última sesión
        $sql_update = "UPDATE usuario SET ultima_sesion = NOW() WHERE num_control = ?";
        $stmt_update = $conexion->prepare($sql_update); 
        $stmt_update->bind_param("i", $user['num_control']);
        $stmt_update->execute();

        echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso.", "user" => $user]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
}

$stmt->close();
$conexion->close();
?>
