<?php
require_once '../Config/conexion.php';

class Informes
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect();
    }

    public function obtenerMesesDisponibles()
    {
        try {
            $stmt = $this->conexion->prepare("CALL ObtenerMesesDisponibles()");
            $stmt->execute();
            $result = $stmt->get_result();
            $meses = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($meses);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener los meses: ' . $e->getMessage()]);
        }
    }

    public function obtenerInformeMensual($mes)
    {
        try {
            if (!preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $mes)) {
                throw new Exception("Formato de mes inválido: $mes");
            }

            $stmt = $this->conexion->prepare("CALL ObtenerInformeMensual(?)");
            $stmt->bind_param("s", $mes);
            $stmt->execute();
            $result = $stmt->get_result();
            $informe = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($informe);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener el informe: ' . $e->getMessage()]);
        }
    }
}
?>
