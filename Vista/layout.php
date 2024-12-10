<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo ?? "Escritorio"; ?></title>
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="../Public/AdminLTE-3.1.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../Public/fontawesome-free-6.6.0-web/css/all.min.css">
    
    <!-- Bootstrap CSS (elige una versión y usa solo esa) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="escritorio.php" class="nav-link">Inicio</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
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

        <!-- Footer -->
        <footer class="main-footer">
            <strong>&copy; 2024 <a href="#">FOCI</a>.</strong> Todos los derechos reservados.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</body>
</html>
