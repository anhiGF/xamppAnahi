<?php

include('conexion.php');
$conexion = Conexion::getInstancia();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $cadenaJSON = file_get_contents('php://input');

    if($cadenaJSON == false){

        echo "No hay cadena JSON";

    }else{

        $datos_alumno = json_decode($cadenaJSON, true);

        $num_control = $datos_alumno['num_control'];
        $nombre = $datos_alumno['nombre'];
        $primer_apellido = $datos_alumno['primer_apellido'];
        $segundo_apellido = $datos_alumno['segundo_apellido'];
        $correo_electronico = $datos_alumno['correo_electronico'];
        $tipo_usuario = $datos_alumno['tipo_usuario'];
        $semestre = $datos_alumno['semestre'];
        $fecha_nac = $datos_alumno['fecha_nac'];

        

        $dato_correctos = false;

        if (isset( $nombre, $primer_apellido, $segundo_apellido, $correo_electronico, $tipo_usuario, $semestre, $fecha_nac, $num_control) && 
        !empty($nombre) && !empty($primer_apellido) && !empty($segundo_apellido) && !empty($correo_electronico) && !empty($tipo_usuario) &&
         !empty($semestre) && !empty($fecha_nac) && is_numeric($num_control) && is_numeric($semestre)) {

        $dato_correctos=true;

        }

        if($dato_correctos){

            $sql = "UPDATE Usuario SET nombre = ?, primer_apellido = ?, segundo_apellido = ?, correo_electronico = ?, tipo_usuario = ?, semestre = ?, fecha_nac = ? WHERE num_control = ?";
            
            if($res){

                $respuesta['consulta'] = "exito";
                $respuesta['mensaje'] = "El registro con numero de control $num_control fue modificado.";

            }else {

                $respuesta['consulta'] = "error";
                $respuesta['mensaje'] = "El registro no pudo ser modificado.";

            }
            
        }
    }

}

?>