<?php
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge y sanitiza los datos del formulario
    $num_control = trim($_POST['num_control']);
    $nombre = trim($_POST['nombre']);
    $primer_apellido = trim($_POST['primer_apellido']);
    $segundo_apellido = trim($_POST['segundo_apellido']);
    $correo_electronico = trim($_POST['correo_electronico']);
    $tipo_usuario = $_POST['tipo_usuario'];
    $semestre = isset($_POST['semestre']) ? trim($_POST['semestre']) : null;
    $fecha_nac = trim($_POST['fecha_nac']);

    // Validaciones
    // Validar número de control: solo números y entre 8 y 10 dígitos
    if (!preg_match('/^\d{8,10}$/', $num_control)) {
        echo "El número de control debe contener entre 8 y 10 dígitos numéricos.";
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

    // Validar correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        echo "El correo electrónico no tiene un formato válido.";
        exit();
    }

    // Validar tipo de usuario
    if (!in_array($tipo_usuario, ['Estudiante', 'Tutor', 'Administrador'])) {
        echo "El tipo de usuario no es válido.";
        exit();
    }

    // Si el tipo de usuario es Tutor o Administrador, se asegura que semestre sea null
    if ($tipo_usuario === 'Tutor' || $tipo_usuario === 'Administrador') {
        if($semestre != null){
            echo "El semestre debe  estar vacío.";
        exit();
        }
    } else if ( !is_numeric($semestre)) {
        echo "El semestre debe ser un número válido";
        exit();
    }

    // Validar fecha de nacimiento
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nac) || !strtotime($fecha_nac)) {
        echo "La fecha de nacimiento no tiene un formato válido (YYYY-MM-DD).";
        exit();
    }

    // Preparar consulta SQL
    $sql = "UPDATE Usuario SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, correo_electronico = ?, tipo_usuario = ?, semestre = ?, fecha_nac = ? WHERE num_control = ?";
    $stmt = $conexion->prepare($sql);

    // Bind de parámetros
    $stmt->bind_param("sssssisi", $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $tipo_usuario, $semestre, $fecha_nac, $num_control);

    // Ejecutar consulta
    if ($stmt->execute()) {
        echo "Usuario actualizado exitosamente.";
    } else {
        echo "Error al actualizar el usuario: " . $stmt->error;
    }

    // Cerrar conexiones
    $stmt->close();
    $conexion->close();
}
?>