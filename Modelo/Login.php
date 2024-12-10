<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../Config/conexion.php";

class Usuario {
    private $conexion;

    // Constructor para inicializar la conexión
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Verificar credenciales y rol del usuario usando el procedimiento almacenado
    public function verificar($cedula) {
        // Sanitizar entrada
        $cedula = mysqli_real_escape_string($this->conexion, $cedula);

        // Preparar la llamada al procedimiento almacenado
        $stmt = $this->conexion->prepare("CALL verificar_rol(?)");

        if (!$stmt) {
            error_log("Error preparing statement: " . mysqli_error($this->conexion)); // Registrar el error
            return null;
        }

        // Vincular parámetros
        $stmt->bind_param("i", $cedula);
        
        // Ejecutar
        if (!$stmt->execute()) {
            error_log("Error executing stored procedure: " . mysqli_error($this->conexion)); // Registrar el error
            return null;
        }

        $result = $stmt->get_result();
        
        // Verificar si se encontraron resultados
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Obtener datos del usuario
        } else {
            error_log("No user found for cedula: $cedula"); // Registrar el error
            return null; // Usuario no encontrado
        }
    }
}
?>
