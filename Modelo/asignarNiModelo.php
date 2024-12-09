<?php
require_once "../Config/conexion.php";

class Nino {
    public static function obtenerResumenAldeas() {
        try {
            $conexion = Fn_getConnect();
            
            if (!$conexion) {
                throw new Exception("Error de conexión a la base de datos");
            }

            $sql = "CALL AsignarNinos('obtener', NULL, NULL)";
            $resultado = $conexion->query($sql);

            if (!$resultado) {
                throw new Exception("Error al ejecutar la consulta");
            }

            $aldeas = [];
            while ($fila = $resultado->fetch_assoc()) {
                $aldeas[] = [
                    'aldea' => htmlspecialchars($fila['aldea']),
                    'total_ninos' => (int)$fila['total_ninos'],
                    'ninos_asignados' => (int)$fila['ninos_asignados']
                ];
            }

            return $aldeas;
        } catch (Exception $e) {
            error_log("Error en obtenerResumenAldeas: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public static function obtenerNinosPorAldea($aldea) {
        try {
            $conexion = Fn_getConnect();
            
            if (!$conexion) {
                throw new Exception("Error de conexión a la base de datos");
            }

            $sql = "CALL AsignarNinos('obtener_ninos_aldea', ?, NULL)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $aldea);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if (!$resultado) {
                throw new Exception("Error al ejecutar la consulta");
            }

            $ninos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ninos[] = [
                    'numero_nino' => $fila['numero_nino'],
                    'nombre_completo' => htmlspecialchars($fila['nombre_completo']),
                    'esta_asignado' => (bool)$fila['esta_asignado']
                ];
            }

            return $ninos;
        } catch (Exception $e) {
            error_log("Error en obtenerNinosPorAldea: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public static function obtenerNinosAsignados($id_gestor) {
        try {
            $conexion = Fn_getConnect();
            
            if (!$conexion) {
                throw new Exception("Error de conexión a la base de datos");
            }

            $sql = "CALL AsignarNinos('obtener_ninos_asignados', NULL, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id_gestor); // Changed from "s" to "i" for integer
            $stmt->execute();
            $resultado = $stmt->get_result();

            if (!$resultado) {
                throw new Exception("Error al ejecutar la consulta");
            }

            $fila = $resultado->fetch_assoc();
            return ['total_ninos' => (int)$fila['total_ninos']];
        } catch (Exception $e) {
            error_log("Error en obtenerNinosAsignados: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public static function asignarMultiplesNinos($ninos, $id_gestor) {
        try {
            $conexion = Fn_getConnect();
            
            if (!$conexion) {
                throw new Exception("Error de conexión a la base de datos");
            }

            $conexion->begin_transaction();

            $sql = "CALL AsignarNinos('asignar', ?, ?)";
            $stmt = $conexion->prepare($sql);
            
            foreach ($ninos as $numero_nino) {
                $stmt->bind_param("si", $numero_nino, $id_gestor);
                if (!$stmt->execute()) {
                    throw new Exception("Error al asignar niño: " . $numero_nino);
                }
            }

            $conexion->commit();
            return true;
        } catch (Exception $e) {
            if (isset($conexion)) {
                $conexion->rollback();
            }
            error_log("Error en asignarMultiplesNinos: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}