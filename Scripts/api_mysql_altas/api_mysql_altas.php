<?php

   include('conexion.php');

   $conexion = Conexion::getInstancia();
    //METODOS HTTP DE PETICION: POST, GET, PUT, PATCH, DELETE
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //
        $cadenaJSON = file_get_contents('php://input');//Extraer la cadena JSON de la peticion

        if($cadenaJSON == false){

            echo "No hay cadena JSON";

        }else{
            
            $datos_alumno = json_decode($cadenaJSON, true); // se carga la cadena JSON que viene desde los datos de android

            $num_control = $datos_alumno ['num_control'];
            $nombre = $datos_alumno ['nombre'];
            $primer_apellido = $datos_alumno ['primer_apellido'];
            $segundo_apellido = $datos_alumno ['segundo_apellido'];
            $correo_electronico = $datos_alumno ['correo_electronico'];
            $contraseña = $datos_alumno ['contraseña'];
            $confirmar_contraseña = $datos_alumno ['confirmar_contraseña'];
            $semestre = $datos_alumno ['semestre'];
            $fecha_nac = $datos_alumno ['fecha_nac'];
            $tipo_usuario = $datos_alumno ['tipo_usuario'];

            //insercion directa del modelo DAO
            $sql = "INSERT INTO Usuario (num_control, nombre, primer_apellido, segundo_apellido, correo_electronico, contraseña, semestre, fecha_nac, tipo_usuario, fecha_registro)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $res = mysqli_query($conexion, $sql);

            //configurar RESPUESTA JSON (RESPONSE)
            $respuesta = array();
            if($res){
                $respuesta['alta'] = 'exito';
                
            }else{
                $respuesta['alta'] = 'error';
                    
            }

            $respuestaJSON = json_encode($respuesta);

            echo $respuestaJSON;

        }

    }

?>