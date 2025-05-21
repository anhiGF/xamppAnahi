<?php
header('Content-Type: application/json');

include("conexion.php");

// Verifica la conexión
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

// Consulta para obtener todos los usuarios (agregar los nuevos campos)
$sql = "SELECT num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, semestre, fecha_nac, tipo_usuario FROM usuario";
$result = $conexion->query($sql);

// Verifica si hay resultados
if ($result && $result->num_rows > 0) {
    $users = [];

    // Obtiene los datos de cada usuario y los agrega al array
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            "num_control" => $row["num_control"],
            "nombre" => $row["nombre"],
            "primer_apellido" => $row["primer_apellido"],
            "segundo_apellido" => $row["segundo_apellido"],
            "correo_electronico" => $row["correo_electronico"],
            "semestre" => $row["semestre"],
            "fecha_nac" => $row["fecha_nac"],
            "tipo_usuario" => $row["tipo_usuario"]
        ];
    }

    // Devuelve los datos de los usuarios en formato JSON
    echo json_encode(["success" => true, "users" => $users]);
} else {
    // Si no se encuentran usuarios, devuelve un mensaje de error
    echo json_encode(["success" => false, "message" => "No se encontraron usuarios."]);
}

// Cierra la conexión
$conexion->close();
?>
