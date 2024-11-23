<?php
include("conexion.php");

// Contar el total de usuarios
$sqlUsuariosTotales = "SELECT COUNT(*) as total FROM Usuario";
$resultUsuariosTotales = $conexion->query($sqlUsuariosTotales);
$usuariosTotales = $resultUsuariosTotales->fetch_assoc()['total'];

// Contar el total de usuarios por tipo
$sqlTiposUsuarios = "
    SELECT tipo_usuario, COUNT(*) as total 
    FROM Usuario 
    GROUP BY tipo_usuario";
$resultTiposUsuarios = $conexion->query($sqlTiposUsuarios);

$tutores = 0;
$estudiantes = 0;
$administradores = 0;

while ($row = $resultTiposUsuarios->fetch_assoc()) {
    if ($row['tipo_usuario'] === 'Tutor') {
        $tutores = $row['total'];
    } elseif ($row['tipo_usuario'] === 'Estudiante') {
        $estudiantes = $row['total'];
    } elseif ($row['tipo_usuario'] === 'Administrador') {
        $administradores = $row['total'];
    }
}

// Calcular el total de tutorías programadas
$sqlTutoriasTotales = "SELECT COUNT(*) as total FROM Tutoria WHERE estado = 'Pendiente'";
$resultTutoriasTotales = $conexion->query($sqlTutoriasTotales);
$tutoriasTotales = $resultTutoriasTotales->fetch_assoc()['total'];

// Contar los usuarios activos ESTA SEMANA
$sqlUsuariosActivosSemana = "SELECT COUNT(*) as total FROM Usuario WHERE ultima_sesion >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$resultUsuariosActivosSemana = $conexion->query($sqlUsuariosActivosSemana);
$usuariosActivosSemana = $resultUsuariosActivosSemana->fetch_assoc()['total'];

// Devolver los datos en formato JSON
echo json_encode([
    "usuariosTotales" => $usuariosTotales,
    "tutores" => $tutores,
    "estudiantes" => $estudiantes,
    "administradores" => $administradores,
    "tutoriasTotales" => $tutoriasTotales,
    "usuariosActivosSemana" => $usuariosActivosSemana
]);

$conexion->close();
?>
