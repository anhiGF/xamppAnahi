
<?php

// Verifica si $_POST está recibiendo los valores correctos
echo "<pre>";
print_r($_POST); // Muestra todo el contenido de $_POST
echo "</pre>";
exit;

// Configuración de la conexión a la base de datos
$servername =  "sql202.infinityfree.com";
$username =  "if0_37251725";
$password = "MINY00NGI1993";
$dbname = "if0_37251725_tutorias";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);
$development = true; // Cambia a false en producción

// Verificar conexión
if ($conn->connect_error) {
    $errorMessage = "Error de conexión a MySQL: " . $conn->connect_error;
    error_log($errorMessage, 3, __DIR__ . "/errores.log");
    if ($development) {
        die($errorMessage);
    } else {
        die("Error de conexión, por favor intente más tarde.");
    }
}

// Validar y sanitizar datos del formulario
$num_control = filter_var($_POST['num_control'], FILTER_SANITIZE_NUMBER_INT);
$nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
$primer_apellido = filter_var($_POST['primer_apellido'], FILTER_SANITIZE_STRING);
$segundo_apellido = filter_var($_POST['segundo_apellido'], FILTER_SANITIZE_STRING);
$correo_electronico = filter_var($_POST['correo_electronico'], FILTER_SANITIZE_EMAIL);
$semestre = filter_var($_POST['semestre'], FILTER_SANITIZE_NUMBER_INT);
$fecha_nac = filter_var($_POST['fecha_nac'], FILTER_SANITIZE_STRING);
$tipo_usuario = filter_var($_POST['tipo_usuario'], FILTER_SANITIZE_STRING);
$fecha_registro = date("Y-m-d"); // Fecha actual

// Validar formato de correo electrónico y dominio
if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL) || !preg_match('/@tecjerez\.edu\.mx$/i', $correo_electronico)) {
    // Imprimir el correo electrónico recibido para verificar
    echo "Correo recibido: " . htmlspecialchars($correo_electronico) . "<br>";
    echo "El correo electrónico debe ser institucional (terminar en @TECjerez.edu.mx).";
    exit;
}

// Validar tipo de usuario permitido
if ($tipo_usuario !== 'Estudiante') {
    echo "Tipo de usuario no permitido.";
    exit;
}

// Verificar que la contraseña tenga al menos 8 caracteres
$contraseña = $_POST['contraseña'];
if (strlen($contraseña) < 8) {
    echo "La contraseña debe tener al menos 8 caracteres.";
    exit;
}
$contraseña = password_hash($contraseña, PASSWORD_DEFAULT);

// Usar una consulta preparada para insertar datos
$stmt = $conn->prepare("INSERT INTO Usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    error_log("Error al preparar la consulta: " . $conn->error, 3, __DIR__ . "/errores.log");
    echo "Hubo un error al registrar, por favor intente de nuevo.";
    exit;
}

$stmt->bind_param("isssssiiss", $num_control, $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $contraseña, $semestre, $fecha_nac, $tipo_usuario, $fecha_registro);

if ($stmt->execute()) {
    echo "Registro exitoso";
} else {
    error_log("Error al ejecutar la consulta: " . $stmt->error, 3, __DIR__ . "/errores.log");
    echo "Hubo un error al registrar, por favor intente de nuevo.";
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
