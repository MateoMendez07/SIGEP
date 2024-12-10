<?php
session_start();
require "../config/conexion.php";
require "../Modelo/Login.php";

$conexion = Fn_getConnect(); // Obtener la conexión
$usuario = new Usuario($conexion);

switch ($_GET["op"]) {
    case 'validar':
        $idUCedula = $_POST["usu"]; // Cédula
        $contrasena = $_POST["cla"]; // Contraseña
        
        error_log("Validando usuario: $idUCedula con contraseña: $contrasena"); // Agregar registro de depuración
        
        // Verificar las credenciales del usuario
        $resultado = $usuario->verificar($idUCedula); // Solo pasa la cédula

        if ($resultado) {
            // Verificar la contraseña
            if (password_verify($contrasena, $resultado['contrasena'])) {
                // Establecer las variables de sesión
                $_SESSION['usu'] = $idUCedula; // Almacenar el identificador de usuario
                $_SESSION['tipo_rol'] = $resultado['tipo_rol']; // Almacenar el rol
                $_SESSION['nombre_completo'] = $resultado['nombre_completo']; // Almacenar el nombre completo

                // Devolver el tipo de rol
                echo $resultado['tipo_rol'];
            } else {
                echo "Contraseña incorrecta"; // Contraseña incorrecta
            }
        } else {
            echo "Usuario no encontrado"; // Usuario no encontrado
        }
        break;

    default:
        echo "Operación no válida";
        break;
}
?>
