<?php
require_once "../Config/conexion.php";

class ModeloTiempo
{
    // Ejecutar operación mediante el SP
    private static function ejecutarOperacion($operacion, $codigo = null, $tipocarta = null, $tiempo_estimado = null)
    {
        $conexion = Fn_getConnect();
        $query = "CALL sp_tipocarta_operations(?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("isss", $operacion, $codigo, $tipocarta, $tiempo_estimado);

        $stmt->execute();
        $result = $stmt->get_result();
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }

        $stmt->close();
        $conexion->close();
        return $datos;
    }

    // Obtener los tiempos definidos
    public static function obtenerTiempos()
    {
        return self::ejecutarOperacion(1);
    }

    // Actualizar el tiempo máximo para un tipo de carta
    public static function actualizarTiempo($codigo, $tiempo_estimado)
    {
        self::ejecutarOperacion(2, $codigo, null, $tiempo_estimado);
    }

    // Obtener los tipos de carta para el combobox
    public static function obtenerTiposCarta()
    {
        return self::ejecutarOperacion(3);
    }
}
