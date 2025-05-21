<?php
// Incluye el archivo de conexión
include("conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge y sanitiza los datos del formulario
    $num_control = trim($_POST['num_control']);
    $nombre = trim($_POST['nombre']);
    $primer_apellido = trim($_POST['primer_apellido']);
    $segundo_apellido = trim($_POST['segundo_apellido']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];
    $semestre = isset($_POST['semestre']) ? intval($_POST['semestre']) : null;
    $fecha_nac = trim($_POST['fecha_nac']);
    $tipo_usuario = $_POST['tipo_usuario'];
    $fecha_registro = date("Y-m-d");

    // Validación del número de control: solo números y 8 a 9 dígitos
    if (!preg_match('/^\d{8,9}$/', $num_control)) {
        echo "El número de control debe contener entre 8 y 9 dígitos numéricos.";
        exit();
    }
 // Validar nombres y apellidos: solo letras y espacios
 if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
    echo "El nombre solo puede contener letras y espacios.";
    exit();
}
if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $primer_apellido)) {
    echo "El primer apellido solo puede contener letras y espacios.";
    exit();
}
if (!empty($segundo_apellido) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $segundo_apellido)) {
    echo "El segundo apellido solo puede contener letras y espacios.";
    exit();
}
    // Validación del correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no es válido.";
        exit();
    }

    // Validación de la contraseña: 8+ caracteres, una mayúscula, una minúscula y un número
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $contraseña)) {
        echo "La contraseña debe tener al menos 8 caracteres, incluir una letra mayúscula, una minúscula y un número.";
        exit();
    }

    // Validación de coincidencia de contraseñas
    if ($contraseña !== $confirmar_contraseña) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Sanitización y encriptación de la contraseña
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Verifica duplicados en la base de datos
    $check_query = "SELECT num_control, correo_electronico FROM Usuario WHERE num_control = ? OR correo_electronico = ?";
    if ($stmt = $conexion->prepare($check_query)) {
        $stmt->bind_param("ss", $num_control, $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

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

    // Inserta el nuevo usuario
    $sql = "INSERT INTO Usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssssisss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña_hash, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro);

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
