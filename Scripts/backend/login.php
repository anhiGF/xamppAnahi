<?php
session_start();
include("conexion.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de reCAPTCHA
    $captcha = $_POST['g-recaptcha-response'];
    $secretKey = "6LdZ0J4qAAAAAJ390iTRDANRVq7QSfinq0HYhL1f";
    $respuestaCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha");
    $resultado = json_decode($respuestaCaptcha, true);

    if (!$resultado['success']) {
        echo "<script>alert('Por favor verifica el CAPTCHA.'); window.location.href = '../../Index.html';</script>";
        exit();
    }

    // Recoge los datos del formulario
    $correo_electronico = trim($_POST['correo_electronico']);
    $contraseña = $_POST['contraseña'];

    // Prepara la consulta SQL
    $sql = "SELECT num_control, nombre, tipo_usuario, contraseña FROM Usuario WHERE correo_electronico = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("s", $correo_electronico);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($num_control, $nombre, $tipo_usuario, $contraseña_hash);
            $stmt->fetch();

            // Verifica la contraseña
            if (password_verify($contraseña, $contraseña_hash)) {
                $_SESSION['num_control'] = $num_control;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                // Actualiza la última sesión
                $sqlActualizarSesion = "UPDATE Usuario SET ultima_sesion = NOW() WHERE num_control = ?";
                $stmtActualizar = $conexion->prepare($sqlActualizarSesion);
                $stmtActualizar->bind_param("i", $num_control);
                $stmtActualizar->execute();
                $stmtActualizar->close();

                // Redirige al usuario
                if ($tipo_usuario == 'Tutor') {
                    header("Location: ../../Pages/panelPrincipalDelTutor.php");
                } elseif ($tipo_usuario == 'Estudiante') {
                    header("Location: ../../Pages/gestionTutorias.php");
                } elseif ($tipo_usuario == 'Administrador') {
                    header("Location: ../../Pages/AdministracionSistema.php");
                }
                exit();
            } else {
                echo "<script>alert('Contraseña incorrecta.'); window.location.href = '../../Index.html';</script>";
            }
        } else {
            echo "<script>alert('No se encontró una cuenta con ese correo electrónico.'); window.location.href = '../../Index.html';</script>";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
