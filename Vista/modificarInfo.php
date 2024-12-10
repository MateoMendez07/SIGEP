<?php 
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Modificar Información";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Modificar Información de Niños</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Mostrar el mensaje si existe -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['mensaje']; ?>
                <?php unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo ?>
            </div>
        <?php endif; ?>

        <!-- Tabla para mostrar los niños -->
        <h3>Niños ingresados</h3>
        <form id="formModificar" action="../Ajax/FileUploadController.php" method="POST">
            <table id="tablaNiños" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 10%;">Numero Niño</th>
                        <th>Nombre Completo</th>
                        <th>Aldea</th>
                        <th>Fecha Nacimiento</th>
                        <th>Comunidad</th>
                        <th>Género</th>
                        <th>Estado Patrocinio</th>
                        <th>Fecha Inscripción</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaBody">
                    <!-- Los datos se cargarán aquí mediante AJAX -->
                </tbody>
            </table>
        </form>
    </div>
</section>

<?php
$contenido = ob_get_clean();
require "layout.php";
?>	

<script src="../Js/modificarInfo.js"></script>

<link rel="stylesheet" href="../CSS/niños.css">