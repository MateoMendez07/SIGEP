<?php
session_start();

if (!isset($_SESSION['usu'])) {
    header("Location: login.html");
    exit();
}

$titulo = "Información Niños";
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
        <!-- Formulario para seleccionar mes y archivo -->
        <form action="../Ajax/FileUploadController.php" method="POST" enctype="multipart/form-data">
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="selectMonth">Seleccione el mes:</label>
                    <select id="selectMonth" name="selectMonth" class="form-control">
                        <?php
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];

                        $mesActual = date('n');
                        $mesSiguiente = ($mesActual % 12) + 1;
                        $anioActual = date('Y');
                        $anioSiguiente = ($mesActual == 12) ? $anioActual + 1 : $anioActual;

                        echo "<option value='{$anioActual}-{$mesActual}-01'>{$meses[$mesActual]}</option>";
                        echo "<option value='{$anioSiguiente}-{$mesSiguiente}-01'>{$meses[$mesSiguiente]}</option>";
                        ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="excelFile">Selecciona el archivo Excel:</label>
                    <input type="file" name="excelFile" id="excelFile" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <button type="submit" name="upload" class="btn btn-primary mt-4">Cargar y Procesar</button>
                </div>
            </div>
        </form>

        <!-- Tabla para mostrar los meses disponibles -->
        <h3 class="mt-4">Registros por Mes</h3>
        <table id="tablaMeses" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Cantidad de Niños</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Los datos se cargarán con JavaScript -->
            </tbody>
        </table>

        <!-- Modal para mostrar los niños del mes seleccionado -->
        <div class="modal fade" id="modalNinos" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Niños registrados en <span id="mesSeleccionado"></span></h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="tablaNiñosIngresados" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Numero Niño</th>
                                    <th>Nombre Completo</th>
                                    <th>Aldea</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Comunidad</th>
                                    <th>Género</th>
                                    <th>Estado Patrocinio</th>
                                    <th>Fecha Inscripción</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div aria-live="polite" aria-atomic="true" style="position: relative; z-index: 1060;">
    <div id="toastContainer" style="position: fixed; top: 1rem; right: 1rem;"></div>
</div>

<script>
// Mostrar mensajes como toasts
document.addEventListener('DOMContentLoaded', function () {
    <?php if (isset($_SESSION['mensaje'])): ?>
        const toastContainer = document.getElementById('toastContainer');
        const toastHTML = ` 
            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000" style="max-width: 350px;">
                <div class="toast-header bg-info text-white">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong class="mr-auto">Mensaje</strong>
                    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">
                    <?= $_SESSION['mensaje']; ?>
                </div>
            </div>
        `;
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        $('.toast').toast('show');
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>
});
</script>

<?php
$contenido = ob_get_clean();
require "layout.php";
?>



<!-- Librería vfs_fonts (requerida por pdfMake) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>


<script src="../Js/ingresarInformacion.js"></script>
