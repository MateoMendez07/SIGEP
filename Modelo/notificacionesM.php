<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../Config/conexion.php';

header('Content-Type: application/json');

class Notificaciones
{
    private $conexion;

    public function __construct()
    {
        // Obtiene la conexión a la base de datos
        $this->conexion = Fn_getConnect();
    }

    public function obtenerNotificaciones()
    {
        try {
            // Preparar la consulta SQL
            $query = "SELECT c.codigo_mcs, c.numero_nino, c.observaciones, n.nombre_completo AS nombre_nino, g.nombre_completo AS nombre_gestor
                    FROM carta c
                    JOIN niño n ON c.numero_nino = n.numero_nino
                    JOIN gestor g ON c.id_gestor = g.cedula";

            // Ejecutar la consulta
            $stmt = $this->conexion->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
            }

            // Ejecutar la consulta preparada
            $stmt->execute();

            // Obtener los resultados
            $result = $stmt->get_result();

            if ($result) {
                $notificaciones = $result->fetch_all(MYSQLI_ASSOC);

                if (!empty($notificaciones)) {
                    echo json_encode($notificaciones);
                } else {
                    echo json_encode(['error' => 'No se encontraron notificaciones.']);
                }
            } else {
                throw new Exception("Error al obtener los resultados: " . $stmt->error);
            }

            // Cerrar el statement
            $stmt->close();
        } catch (Exception $e) {
            // Manejo de errores
            echo json_encode(['error' => 'Error al obtener las notificaciones: ' . $e->getMessage()]);
        }
    }
}

// Instancia la clase y llama al método para obtener las notificaciones
$notificaciones = new Notificaciones();
$notificaciones->obtenerNotificaciones();
