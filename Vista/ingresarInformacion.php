 <?php 
// vista/ingresarInformacion.php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

// Título de la página
$titulo = "Ingresar Información";

// Iniciar un buffer para capturar el contenido de la página
ob_start();
?>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ingresar información de niños</h1>
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

            <form action="../Ajax/FileUploadController.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="excelFile">Selecciona el archivo Excel:</label>
                    <input type="file" name="excelFile" id="excelFile" class="form-control" required>
                </div>
                <button type="submit" name="upload" class="btn btn-primary">Cargar y Procesar</button>
            </form>
        </div>
    </section>

<?php
// Capturar el contenido de la página y guardarlo en la variable $contenido
$contenido = ob_get_clean();

// Incluir la plantilla layout.php
require "layout.php";
?>
