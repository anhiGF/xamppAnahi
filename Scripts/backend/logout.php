<?php
// Inicia la sesión
session_start();

// Destruye todas las variables de sesión
$_SESSION = array();

// Destruye la sesión completamente
session_destroy();

// Redirige al usuario a la página de inicio de sesión o página principal
header("Location: ../../Index.html"); 
exit();
?>
