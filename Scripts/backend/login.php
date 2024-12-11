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
                // Actualiza la última sesión
                $sqlActualizarSesion = "UPDATE Usuario SET ultima_sesion = NOW() WHERE num_control = ?";
                $stmtActualizar = $conexion->prepare($sqlActualizarSesion);
                $stmtActualizar->bind_param("i", $num_control);
                $stmtActualizar->execute();
                $stmtActualizar->close();

                // Redirige al usuario según su rol
                if ($tipo_usuario == 'Tutor') {
                    header("Location: ../../Pages/panelPrincipalDelTutor.php");
                } elseif ($tipo_usuario == 'Estudiante') {
                    header("Location: ../../Pages/gestionTutorias.php");
                } elseif ($tipo_usuario == 'Administrador') {
                    header("Location: ../../Pages/AdministracionSistema.php");
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
         
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
