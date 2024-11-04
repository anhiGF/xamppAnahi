<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión
include("conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge y sanitiza los datos del formulario
    $correo_electronico = trim($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];

    // Prepara la consulta SQL para buscar al usuario
    $sql = "SELECT num_control, nombre, contraseña, tipo_usuario FROM Usuario WHERE correo_electronico = ?";
    
    // Prepara la consulta con el método prepare
    if ($stmt = $conexion->prepare($sql)) {
        // Liga los parámetros a la consulta
        $stmt->bind_param("s", $correo_electronico);

        // Ejecuta la consulta
        $stmt->execute();
        
        // Guarda el resultado
        $stmt->store_result();

        // Verifica si se encontró un usuario con ese correo electrónico
        if ($stmt->num_rows == 1) {
            // Liga los resultados a las variables
            $stmt->bind_result($num_control, $nombre, $contraseña_hash, $tipo_usuario);
            $stmt->fetch();

            // Verifica si la contraseña es correcta
            if (password_verify($contraseña, $contraseña_hash)) {
                // La contraseña es correcta, iniciar sesión
                $_SESSION['num_control'] = $num_control;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                // Redirigir al usuario a la página de inicio o panel
                header("Location: ./Pages/gestionTutorias.html");
                exit();
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta.";
            }
        } else {
            // No se encontró el usuario
            echo "No se encontró una cuenta con ese correo electrónico.";
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
