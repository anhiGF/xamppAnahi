<?php
// estadisticas.php
include('conexion.php');

$mes = $_POST['mesSeleccionado'] ?? date('m');
$ano = $_POST['anoSeleccionado'] ?? date('Y');

// Consulta para contar las tutorías por mes
$query = "SELECT COUNT(id_tutoria) AS total_tutorias, MONTH(fecha) AS mes
          FROM tutoria
          WHERE YEAR(fecha) = :ano AND MONTH(fecha) = :mes
          GROUP BY MONTH(fecha)";

$stmt = $conn->prepare($query);
$stmt->bindParam(':ano', $ano);
$stmt->bindParam(':mes', $mes);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($result);
?>
