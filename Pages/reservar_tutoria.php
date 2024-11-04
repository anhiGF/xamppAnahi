<?php
session_start();
include("conexion.php");

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprueba que todos los datos necesarios estén presentes en el arreglo POST
    if (!isset($_POST['titulo'], $_POST['descripcion'], $_POST['fecha'], $_POST['hora'], $_POST['id_tutor'])) {
        die("Faltan datos en el formulario. Asegúrate de completar todos los campos.");
    }

    // Recoge los datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $duracion = 60; // Puedes ajustar la duración como un campo si es necesario
    $estado = 'Pendiente';
    $id_tutor = intval($_POST['id_tutor']);
    $id_estudiante = $_SESSION['num_control']; // Suponiendo que el ID del estudiante está en la sesión

    // Prepara la consulta de inserción
    $sql = "INSERT INTO Tutoria (titulo, descripcion, fecha, hora, duracion, estado, id_tutor, id_estudiante)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssisii", $titulo, $descripcion, $fecha, $hora, $duracion, $estado, $id_tutor, $id_estudiante);
        if ($stmt->execute()) {
            echo "Tutoría reservada con éxito";
        } else {
            echo "Error al reservar la tutoría: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    $conexion->close();
}
?>
