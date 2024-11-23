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
    $fecha_registro = date("Y-m-d");

    // Validación de contraseñas
    if ($contraseña !== $confirmar_contraseña) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Encripta la contraseña antes de guardarla en la base de datos
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Verificar si el número de control o el correo ya existen
    $check_query = "SELECT num_control, correo_electronico FROM Usuario WHERE num_control = ? OR correo_electronico = ?";
    if ($stmt = $conexion->prepare($check_query)) {
        $stmt->bind_param("is", $num_control, $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

        // Si hay resultados, verificar cuál ya existe
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existing_num_control, $existing_email);
            $stmt->fetch();

            if ($existing_num_control == $num_control) {
                echo "Ya existe un perfil para ese número de control.";
            } elseif ($existing_email == $correo_electronico) {
                echo "Ya existe un perfil para ese correo.";
            }
            $stmt->close();
            $conexion->close();
            exit();
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta de verificación: " . $conexion->error;
        $conexion->close();
        exit();
    }

    // Prepara la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO Usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("isssssisss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña_hash, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro);

        if ($stmt->execute()) {
            echo "Registro exitoso";
        } else {
            echo "Error en el registro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta de inserción: " . $conexion->error;
    }

    $conexion->close();
}
?>
