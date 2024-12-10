<?php
require_once '../Config/conexion.php';    

class IInformeSemestral
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect();
    }

    // Obtener los semestres disponibles
    public function obtenerSemestresDisponibles()
    {
        try {
            // Llamada al procedimiento almacenado para obtener los semestres
            $stmt = $this->conexion->prepare("CALL sigep.ObtenerSemestresDisponibles()");
            $stmt->execute();
            $result = $stmt->get_result();
            $semestres = $result->fetch_all(MYSQLI_ASSOC);
            
            // Devolver los semestres sin modificación si son correctos
            return $semestres ?: []; // Si no hay resultados, devolver un arreglo vacío
        } catch (Exception $e) {
            return ['error' => 'Error al obtener los semestres: ' . $e->getMessage()];
        }
    }

    // Obtener el informe semestral
    public function obtenerInformeSemestral($semestre)
    {
        try {
            if (!preg_match('/^\d{4}-(1|2)$/', $semestre)) {
                throw new Exception("Formato de semestre inválido: $semestre");
            }

            $stmt = $this->conexion->prepare("CALL ObtenerInformeSemestralT(?)");
            $stmt->bind_param("s", $semestre);
            $stmt->execute();
            $result = $stmt->get_result();
            $informe = $result->fetch_all(MYSQLI_ASSOC); // Obtener todos los registros como un arreglo

            // Verificar si el resultado contiene datos
            return $informe ?: []; // Si no hay datos, devolver un arreglo vacío
        } catch (Exception $e) {
            return ['error' => 'Error al obtener el informe semestral: ' . $e->getMessage()];
        }
    } 
}

?>
