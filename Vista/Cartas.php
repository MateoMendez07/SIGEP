 <?php 
// vista/ingresarInformacion.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Asignar Cartas";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Asignar Cartas</h1>
                </div>
            </div>
        </div>
    </div>
    

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
