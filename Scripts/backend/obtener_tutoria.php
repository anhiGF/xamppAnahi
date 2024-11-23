<?php
include("conexion.php");

if (isset($_GET['id_tutoria'])) {
    $id_tutoria = intval($_GET['id_tutoria']);
    $sql = "SELECT id_tutoria, titulo, descripcion, fecha, hora FROM Tutoria WHERE id_tutoria = ?";
    
    if ($stmt = $conexion->prepare($sql)) {
        $stmt->bind_param("i", $id_tutoria);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $tutoria = $result->fetch_assoc();
            echo json_encode($tutoria);
        } else {
            echo json_encode(["error" => "Tutoría no encontrada."]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(["error" => "Error en la consulta: " . $conexion->error]);
    }

    $conexion->close();
} else {
    echo json_encode(["error" => "ID de tutoría no especificado."]);
}
?>
