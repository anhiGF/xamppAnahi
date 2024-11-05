<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token']) && isset($_POST['nueva_contraseña'])) {
    $token = $_POST['token'];
    $nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);

    // Verifica si el token es válido y no ha expirado
    $sql = "SELECT num_control FROM RecuperacionContraseña WHERE token = ? AND expira_en > NOW()";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($num_control);
        $stmt->fetch();

        // Actualiza la contraseña en la tabla Usuario
        $sql_update = "UPDATE Usuario SET contraseña = ? WHERE num_control = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("si", $nueva_contraseña, $num_control);
        $stmt_update->execute();

        // Elimina el token después de cambiar la contraseña
        $sql_delete_token = "DELETE FROM RecuperacionContraseña WHERE num_control = ?";
        $stmt_delete_token = $conexion->prepare($sql_delete_token);
        $stmt_delete_token->bind_param("i", $num_control);
        $stmt_delete_token->execute();

        echo "Contraseña restablecida con éxito. Ahora puedes iniciar sesión con tu nueva contraseña.";
    } else {
        echo "El enlace de recuperación es inválido o ha expirado.";
    }

    $stmt->close();
    $conexion->close();
} else {

    // Formulario para restablecer la contraseña si es una solicitud GET con un token válido
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        echo '
            <form method="POST" action="restablecer_contraseña.php">
                <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                <div class="form-group">
                    <label for="nuevaContraseña">Nueva Contraseña</label>
                    <input type="password" class="form-control" id="nuevaContraseña" name="nueva_contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Restablecer Contraseña</button>
            </form>
        ';
    } else {
        echo "Token no proporcionado.";
    }
}
?>
