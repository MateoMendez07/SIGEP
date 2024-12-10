<?php
// modelo/NiñoModel.php
require '../Config/conexion.php'; // Archivo de conexión a la base de datos

class NiñoModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Fn_getConnect();
        if (!$this->conexion) {
            throw new Exception("Error al conectar con la base de datos.");
        }
    }

    // Otros métodos...

    /**
     * Actualiza los datos de un niño existente usando el procedimiento almacenado `GestionarNiños`.
     */
    public function actualizarNiño($numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion)
    {
        // Primero, verifica si el niño está inactivo
        $estadoActual = $this->obtenerEstadoNiño($numero_nino);
        if ($estadoActual === 'inactivo') {
            throw new Exception("No se pueden modificar los datos de un niño con estado 'inactivo'.");
        }

        // Procede con la actualización si el estado no es inactivo
        $query = "CALL GestionarNiños('actualizar', ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param(
            "isssssss",
            $numero_nino,
            $nombre_completo,
            $aldea,
            $fecha_nacimiento,
            $comunidad,
            $genero,
            $estado_patrocinio,
            $fecha_inscripcion
        );

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $stmt->error);
        }

        return "Datos actualizados correctamente.";
    }

    /**
     * Obtiene el estado actual de un niño por su número.
     */
    private function obtenerEstadoNiño($numero_nino)
    {
        $query = "CALL GestionarNiños('obtenerEstado', ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("i", $numero_nino);

        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento almacenado: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            return $row['estado_patrocinio']; // Asegúrate de que este campo coincide con tu base de datos
        } else {
            throw new Exception("No se encontró el niño con el número proporcionado.");
        }
    }
}
