<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? "Escritorio"; ?></title>
    
    <!-- AdminLTE CSS -->
	<link rel="stylesheet" href="../Public/bootstrap-4.5.1-dist/bootstrap-4.5.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Public/AdminLTE-3.1.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../Public/fontawesome-free-6.6.0-web/css/all.min.css">
    
    <!-- DataTables CSS -->
	<link rel="stylesheet" href="../Public/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

    <!-- Estilos personalizados --> 
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Botón de colapso -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <!-- Links de navegación -->
            <ul class="navbar-nav">
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="escritorio.php" class="nav-link">Inicio</a>
                </li>
            </ul>

            <!-- Links a la derecha -->
            <ul class="navbar-nav ml-auto">
                <!-- Dropdown para mostrar usuario y rol -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <strong><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></strong>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item">
                            <strong>Rol:</strong> <?php echo htmlspecialchars($_SESSION['tipo_rol']); ?>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../Ajax/Cerrar.php">Cerrar Sesión</a>
                    </div>
                </li>
            </ul>
        </nav>

        <?php require "sidebar.php"; ?>

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <?php echo $contenido; ?>
        </div>

        <!-- Footer (opcional) -->
        <footer class="main-footer">
            <strong>&copy; 2024 <a href="#">FOCI</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- AdminLTE Scripts -->
	<script src="../Public/js/jquery-3.7.1.js"></script>
		<script src="../Public/js/jquery-3.7.1.min.js"></script>

	
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
-->
<script src="../Public/bootstrap-4.5.1-dist/bootstrap-4.5.1-dist/js/bootstrap.bundle.min.js"></script>
<script src="../Public/AdminLTE-3.1.0/dist/js/adminlte.min.js"></script>
<!-- DataTables JS -->
<script src="../Public/js/jquery.dataTables.min.js"></script>
<script src="../Public/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.53/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- Scripts personalizados -->
</body>
</html>
