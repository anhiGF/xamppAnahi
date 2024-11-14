<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoge los datos del formulario de inicio de sesión
    $correo_electronico = trim($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];

    // Prepara la consulta SQL para buscar al usuario
    $sql = "SELECT num_control, nombre, tipo_usuario, contraseña FROM Usuario WHERE correo_electronico = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

        // Verifica si se encontró un usuario con ese correo electrónico
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($num_control, $nombre, $tipo_usuario, $contraseña_hash);
            $stmt->fetch();

            // Verifica la contraseña
            if (password_verify($contraseña, $contraseña_hash)) {
                // La contraseña es correcta, inicia la sesión
                $_SESSION['num_control'] = $num_control;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['tipo_usuario'] = $tipo_usuario;
                $num_control = $_POST['num_control']; 

                // Redirige al usuario según su rol
                if ($tipo_usuario == 'Tutor') {
                    header("Location: ./Pages/panelPrincipalDelTutor.html");
                } elseif ($tipo_usuario == 'Estudiante') {
                    header("Location: ./Pages/gestionTutorias.html");
                } elseif ($tipo_usuario == 'Administrador') {
                    header("Location: ./Pages/AdministracionSistema.html");
                }
                
                exit();
            } else {
                // Contraseña incorrecta
                echo "Contraseña incorrecta.";
            }
        } else {
            // No se encontró el usuario
            echo "No se encontró una cuenta con ese correo electrónico.";
        }

        $stmt->close();
         // Actualiza el campo `ultima_sesion` a la fecha y hora actuales
         $sqlActualizarSesion = "UPDATE Usuario SET ultima_sesion = NOW() WHERE num_control = ?";
         $stmt = $conexion->prepare($sqlActualizarSesion);
         $stmt->bind_param("i", $num_control);
         $stmt->execute();
         $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
