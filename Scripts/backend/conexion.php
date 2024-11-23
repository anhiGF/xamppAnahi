<?php
// Configuración de los datos de acceso a la base de datos
$host = "localhost";     
$usuario = "root";  
$contraseña = "ARNIAK123"; 
$base_datos = "tutorias";  

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar si la conexión tuvo éxito
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
