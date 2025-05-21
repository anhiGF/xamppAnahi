<?php
session_start();
include("conexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (
        empty($_POST['titulo']) || 
        empty($_POST['fecha']) || 
        empty($_POST['hora']) || 
        empty($_POST['id_tutor']) || 
        empty($_SESSION['num_control'])
    ) {
        die("Faltan datos obligatorios. Asegúrate de completar todos los campos.");
    }

    // Recoge los datos del formulario
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']) ?: null;  
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $duracion = 60;
    $estado = 'Pendiente';
    $id_tutor = $_POST['id_tutor']; 
    $id_estudiante = $_SESSION['num_control'];

    $sql = "INSERT INTO Tutoria (titulo, descripcion, fecha, hora, duracion, estado, id_tutor, id_estudiante)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("ssssisss", $titulo, $descripcion, $fecha, $hora, $duracion, $estado, $id_tutor, $id_estudiante);
        
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
