<?php
class Conexion {
    private static $instancia = null;
    private $conexion;

    private $host = "localhost";     
    private $usuario = "AdminTec";  
    private $contraseña = "AdminTec"; 
    private $base_datos = "tutorias";  

    // Constructor privado
    private function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contraseña, $this->base_datos);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8");
    }

    // Obtener la instancia única
    public static function getInstancia() {
        if (self::$instancia === null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia->conexion;
    }
}

// Crear la instancia de la conexión global
$conexion = Conexion::getInstancia();
?>
