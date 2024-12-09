<?php 
session_start();
require '../Modelo/usuarioM.php'; // Incluir el modelo de usuario

class GestorController {
    private $gestorModel;

    public function __construct() {
        $this->gestorModel = new GestorModel();
    }

    // Método para mostrar todos los usuarios
    public function mostrarUsuarios() {
        return $this->gestorModel->obtenerUsuarios();
    }

    // Método para obtener todos los roles
    public function obtenerRoles() {
        return $this->gestorModel->obtenerRoles();
    }

    // Método para insertar un nuevo usuario
    public function insertarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena) {
        $resultado = $this->gestorModel->insertarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena);
        return $resultado === true ? "Usuario agregado correctamente." : "Error al agregar el usuario: $resultado";
    }

    // Método para actualizar un usuario
    public function actualizarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena) {
        $resultado = $this->gestorModel->actualizarUsuario($cedula, $nombreCompleto, $correoElectronico, $idRol, $contrasena);
        return $resultado === true ? "Usuario actualizado correctamente." : "Error al actualizar el usuario: $resultado";
    }

    // Método para eliminar un usuario
    public function eliminarUsuario($cedula) {
        $resultado = $this->gestorModel->eliminarUsuario($cedula);
        return $resultado === true ? "Usuario eliminado correctamente." : "Error al eliminar el usuario: $resultado";
    }

    // Método para obtener los datos de un usuario por cédula
    public function obtenerUsuarioPorCedula($cedula) {
        return $this->gestorModel->obtenerUsuarioPorCedula($cedula); // Este método debe estar definido en el modelo
    }
}

// Manejo de solicitudes
if (isset($_POST['accion']) || isset($_GET['accion'])) {
    $controller = new GestorController();
    $accion = $_POST['accion'] ?? $_GET['accion'];

    switch ($accion) {
        case 'insertar':
            $mensaje = $controller->insertarUsuario(
                $_POST['cedula'],
                $_POST['nombre_completo'],
                $_POST['correo_electronico'],
                $_POST['id_rol'],
                $_POST['contrasena']
            );
            echo $mensaje; // Enviar mensaje directamente
            break;
        case 'actualizar':
            $mensaje = $controller->actualizarUsuario(
                $_POST['cedula'],
                $_POST['nombre_completo'],
                $_POST['correo_electronico'],
                $_POST['id_rol'],
                $_POST['contrasena']
            );
            echo $mensaje; // Enviar mensaje directamente
            break;
        case 'eliminar':
            $mensaje = $controller->eliminarUsuario($_GET['cedula']);
            echo $mensaje; // Enviar mensaje directamente
            break;
        case 'mostrar':
            $usuarios = $controller->mostrarUsuarios();
            header('Content-Type: application/json');
            echo json_encode($usuarios);
            break;
        // Acción para obtener datos de un usuario por cédula
        case 'obtenerPorCedula': // Este es el nombre correcto de la acción en el SP
            $cedula = $_GET['cedula'];
            $usuario = $controller->obtenerUsuarioPorCedula($cedula); // Obtener datos del usuario
            header('Content-Type: application/json');
            if ($usuario) {
                echo json_encode($usuario); // Retornar datos en formato JSON
            } else {
                echo json_encode(['error' => 'Usuario no encontrado']); // Respuesta de error si no se encuentra el usuario
            }
            break;
        case 'obtenerRoles': // Nueva acción para obtener roles
            $roles = $controller->obtenerRoles();
            header('Content-Type: application/json');
            echo json_encode($roles); // Enviar los datos de los roles en formato JSON
            break;
        default:
            echo 'Acción no válida.'; // Enviar mensaje directamente
            break;
    }
} else {
    echo 'No se recibió ninguna acción.'; // Enviar mensaje directamente
}
?>
