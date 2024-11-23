<?php
include("conexion.php");

$id_tutoria = isset($_GET['id_tutoria']) ? intval($_GET['id_tutoria']) : 0;

$sql = "SELECT id_historial, accion, fecha_modificacion, usuario_id 
        FROM HistorialModificaciones 
        WHERE id_tutoria = ?
        ORDER BY fecha_modificacion DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_tutoria);
$stmt->execute();
$result = $stmt->get_result();

$historial = [];
while ($row = $result->fetch_assoc()) {
    $historial[] = $row;
}

$stmt->close();
$conexion->close();

header('Content-Type: application/json');
echo json_encode($historial);
?>
