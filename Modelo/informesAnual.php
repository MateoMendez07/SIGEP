<?php
require_once '../Config/conexion.php';

class InformesAnualesModelo
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect(); // Asegúrate de que Fn_getConnect devuelve la conexión MySQLi
    }

    // Obtener años disponibles
    public function obtenerAniosDisponibles()
    {
        try {
            $stmt = $this->conexion->prepare("CALL ObtenerAniosDisponibles()");
            $stmt->execute();
            $result = $stmt->get_result();
            $anios = $result->fetch_all(MYSQLI_ASSOC);
            return $anios; // Corregido: devolver $anios en lugar de echo
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener los años: ' . $e->getMessage()]);
        }
    }

    

    // Obtener informe anual
    public function obtenerInformeAnual($anio) {
        try {
            // Validar formato del año
            if (!preg_match('/^\d{4}$/', $anio)) {
                throw new Exception("Formato de año inválido: $anio");
            }

            // Llamar al procedimiento almacenado
            $stmt = $this->conexion->prepare("CALL ObtenerInformeAnualT(?)");
            $stmt->bind_param("i", $anio);
            $stmt->execute();
            $result = $stmt->get_result();

            // Obtener todos los registros como un arreglo asociativo
            $informes = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return $informes;
        } catch (Exception $e) {
            return ['error' => 'Error al obtener el informe: ' . $e->getMessage()];
        }
    }
}
?>
