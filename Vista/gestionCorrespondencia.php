<?php 
session_start();
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

$titulo = "Gestión de Correspondencia";
ob_start();
?>

<?php 
$contenido = ob_get_clean();
require "layout.php";
?>