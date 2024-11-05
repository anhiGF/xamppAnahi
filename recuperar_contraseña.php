<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['correo_electronico'])) {
    $correo_electronico = trim($_POST['correo_electronico']);

    // Verifica si el correo electrónico existe en la base de datos
    $sql = "SELECT num_control FROM Usuario WHERE correo_electronico = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo_electronico);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($num_control);
        $stmt->fetch();

        // Genera un token de recuperación único
        $token = bin2hex(random_bytes(16)); // Genera un token seguro
        $expira_en = date("Y-m-d H:i:s", strtotime('+1 hour')); // Expira en 1 hora

        // Almacena el token y la fecha de expiración en la base de datos
        $sql_insert_token = "INSERT INTO RecuperacionContraseña (num_control, token, expira_en) VALUES (?, ?, ?)
                             ON DUPLICATE KEY UPDATE token = ?, expira_en = ?";
        $stmt_token = $conexion->prepare($sql_insert_token);
        $stmt_token->bind_param("issss", $num_control, $token, $expira_en, $token, $expira_en);
        $stmt_token->execute();

        // Enlace de recuperación
        $enlace = "http://localhost/restablecer_contrase%C3%B1a.php?token=$token";

        // Configurar PHPMailer para enviar el correo
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'naniflores1509@gmail.com';
            $mail->Password = 'ajgo hpay pvsy mokq';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('naniflores1509@gmail.com', 'ITSJ-tutorias');
            $mail->addAddress($correo_electronico);
            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña';
            $mail->Body = "Hola,<br><br>Haz clic en el siguiente enlace para restablecer tu contraseña:<br><a href='$enlace'>$enlace</a><br><br>Este enlace expirará en 1 hora.";

            $mail->send();
            echo "Correo de recuperación enviado. Revisa tu bandeja de entrada.";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró una cuenta con ese correo electrónico.";
    }

    $stmt->close();
    $conexion->close();
}
?>
