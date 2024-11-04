<?php
// Incluye el archivo de conexión
include("conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge y sanitiza los datos del formulario
    $num_control = intval($_POST['num_control']);
    $nombre = trim($_POST['nombre']);
    $primer_apellido = trim($_POST['primer_apellido']);
    $segundo_apellido = trim($_POST['segundo_apellido']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];
    $semestre = isset($_POST['semestre']) ? intval($_POST['semestre']) : null;
    $fecha_nac = $_POST['fecha_nac'];
    $tipo_usuario = $_POST['tipo_usuario'];

    // Fecha de registro automática (opcional)
    $fecha_registro = date("Y-m-d");

    // Validación de contraseñas
    if ($contraseña !== $confirmar_contraseña) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Encripta la contraseña antes de guardarla en la base de datos
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Prepara la consulta SQL
    $sql = "INSERT INTO Usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara la consulta con el método prepare
    if ($stmt = $conexion->prepare($sql)) {
        // Liga los parámetros a la consulta
        $stmt->bind_param("isssssisss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña_hash, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro);

        // Ejecuta la consulta
        if ($stmt->execute()) {
            echo "Registro exitoso";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }
        // Cierra la declaración
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    // Cierra la conexión
    $conexion->close();
}
?>
