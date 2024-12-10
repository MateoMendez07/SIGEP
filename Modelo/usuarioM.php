<?php
require '../Config/conexion.php'; // Archivo de conexión a la base de datos

class GestorModel {
  private $conexion;

  public function __construct() {
    $this->conexion = Fn_getConnect(); // Función para obtener la conexión a la base de datos
  }

  // Método para obtener todos los usuarios
  public function obtenerUsuarios() {
    $usuarios = [];
    // Realiza un JOIN con la tabla 'rol' para obtener el tipo de rol
    $query = "SELECT g.Cedula, g.nombre_completo, g.correo_electronico, r.tipo_rol 
              FROM gestor g 
              JOIN rol r ON g.id_rol = r.id"; // Unir con la tabla rol
    $resultado = $this->conexion->query( $query );

    if ( $resultado && $resultado->num_rows > 0 ) {
      while ( $fila = $resultado->fetch_assoc() ) {
        $usuarios[] = $fila; // Aquí tendrás el 'tipo_rol' en lugar de 'id_rol'
      }
    }

    return $usuarios; // Devuelve el array de usuarios con el tipo de rol
  }

  public function obtenerRoles() {
    $roles = [];
    $query = "SELECT id, tipo_rol FROM rol";
    $resultado = $this->conexion->query( $query );

    if ( $resultado && $resultado->num_rows > 0 ) {
      while ( $fila = $resultado->fetch_assoc() ) {
        $roles[] = $fila;
      }
    }

    return $roles;
  }

  // Método para insertar un nuevo usuario
  public function insertarUsuario( $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena ) {
    $contrasenaHash = password_hash( $contrasena, PASSWORD_BCRYPT ); // Hashear la contraseña
    $query = "INSERT INTO gestor (Cedula, nombre_completo, correo_electronico, id_rol, contrasena) VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conexion->prepare( $query );
    $stmt->bind_param( "issis", $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasenaHash );

    if ( $stmt->execute() ) {
      return true;
    } else {
      return "Error al insertar: " . $this->conexion->error;
    }
  }

  // Método para actualizar un usuario
  public function actualizarUsuario( $cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena ) {
    $contrasenaHash = password_hash( $contrasena, PASSWORD_BCRYPT ); // Hashear la nueva contraseña
    $query = "UPDATE gestor SET nombre_completo = ?, correo_electronico = ?, id_rol = ?, contrasena = ? WHERE Cedula = ?";
    $stmt = $this->conexion->prepare( $query );
    $stmt->bind_param( "ssisi", $nombreCompleto, $correoElectronico, $idRol, $contrasenaHash, $cedula );

    if ( $stmt->execute() ) {
      return true;
    } else {
      return "Error al actualizar: " . $this->conexion->error;
    }
  }

  // Método para eliminar un usuario
  public function eliminarUsuario( $cedula ) {
    $query = "DELETE FROM gestor WHERE Cedula = ?";
    $stmt = $this->conexion->prepare( $query );
    $stmt->bind_param( "i", $cedula );

    if ( $stmt->execute() ) {
      return true;
    } else {
      return "Error al eliminar: " . $this->conexion->error;
    }
  }

  // Método para obtener un usuario por cédula
  public function obtenerUsuarioPorCedula( $cedula ) {
    $query = "SELECT Cedula, nombre_completo, correo_electronico, id_rol FROM gestor WHERE Cedula = ?";
    $stmt = $this->conexion->prepare( $query );
    $stmt->bind_param( "i", $cedula );
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ( $resultado && $resultado->num_rows > 0 ) {
      return $resultado->fetch_assoc(); // Retorna los datos del usuario
    }

    return null; // Retorna null si no se encuentra el usuario
  }
}
?>
