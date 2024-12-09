<?php
require '../Config/conexion.php'; // Archivo de conexión a la base de datos

class GestorModel {
  private $conexion;

  public function __construct() {
    $this->conexion = Fn_getConnect(); // Función para obtener la conexión a la base de datos
  }

  // Método genérico para el CRUD de usuarios
  public function crudUsuarios($accion, $cedula = null, $nombreCompleto = null, $correoElectronico = null, $idRol = null, $contrasena = null) {
    // Hashea la contraseña si se proporciona
    $contrasenaHash = $contrasena ? password_hash($contrasena, PASSWORD_BCRYPT) : null;

    // Prepara la llamada al procedimiento almacenado
    $query = "CALL sp_crudUsuarios(?, ?, ?, ?, ?, ?)";
    $stmt = $this->conexion->prepare($query);
    
    // Asigna los parámetros al procedimiento almacenado
    $stmt->bind_param("sissis", $accion, $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasenaHash);

    if ($stmt->execute()) {
        if ($accion === 'mostrar' || $accion === 'obtenerPorCedula') {
            $resultado = $stmt->get_result();
            $datos = [];
            while ($fila = $resultado->fetch_assoc()) {
                $datos[] = $fila;
            }
            return $datos;  // Retorna los resultados (usuarios)
        }
        return true;  // Para las acciones de insertar, actualizar, y eliminar
    } else {
        return "Error en la operación: " . $this->conexion->error;
    }
  }

  // Método para obtener todos los usuarios
  public function obtenerUsuarios() {
    return $this->crudUsuarios('mostrar');
  }

  // Método para obtener roles
  public function obtenerRoles() {
    $query = "SELECT id, tipo_rol FROM rol";
    $resultado = $this->conexion->query($query);
    $roles = [];

    if ($resultado && $resultado->num_rows > 0) {
      while ($fila = $resultado->fetch_assoc()) {
        $roles[] = $fila;
      }
    }

    return $roles;
  }

  // Método para insertar un nuevo usuario
  public function insertarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena) {
    return $this->crudUsuarios('insertar', $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena);
  }

  // Método para actualizar un usuario
  public function actualizarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena) {
    return $this->crudUsuarios('actualizar', $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena);
  }

  // Método para eliminar un usuario
  public function eliminarUsuario($cedula) {
    return $this->crudUsuarios('eliminar', $cedula);
  }

  // Método para obtener un usuario por cédula
  public function obtenerUsuarioPorCedula($cedula) {
    $resultado = $this->crudUsuarios('obtenerPorCedula', $cedula);
    return $resultado ? $resultado[0] : null;  // Retorna el primer usuario si existe
  }
}
?>
