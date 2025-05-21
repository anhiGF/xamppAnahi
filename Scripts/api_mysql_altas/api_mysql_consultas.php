<?php

include('conexion.php');
$conexion = Conexion::getInstancia();

    //METODOS HTTP DE PETICION: POST, GET, PUT, PATCH, DELETE
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        //recibir la PETICION (REQUEST) con JSON a travez de HTTP
        $cadenaJSON = file_get_contents('php://input');//Extraer la cadena JSON de la peticion

        if($cadenaJSON == false){

            echo "No hay cadena JSON";

        }else{
            
            $consulta_filtros = json_decode($cadenaJSON, true); // se carga la cadena JSON que viene desde los datos de android

            $filtro_nc = $consulta_filtros['filtro_nc'];
            $filtro_n = $consulta_filtros['filtro_n'];

            //insercion directa del modelo DAO
            $sql = "SELECT * FROM usuario";
            $res = mysqli_query($conexion, $sql);

            //configurar RESPUESTA JSON (RESPONSE)
            $respuesta['usuario'] = array();

            if($res){

                while($fila = mysqli_fetch_assoc($res)){
                    $alumno = array();
                    $alumno['nc'] = $fila['Num_Control'];
                    $alumno['n'] = $fila['Nombre'];
                    //... demas campos
                    array_push($respuesta['usuario'], $alumno);
                }

                $respuesta['consulta'] = 'exito';
                
            }else{
                $respuesta['consulta'] = 'no hay regisro';
                    
            }

            $respuestaJSON = json_encode($respuesta);

            echo $respuestaJSON;

        }

    }

?>