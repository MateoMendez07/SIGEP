<?php
session_start();
session_unset();    // Eliminar todas las variables de sesión
session_destroy();  // Destruir la sesión actual
header("Location: ../Vista/Login.html"); // Redirigir a la página de inicio de sesión o principal
exit();
?>
