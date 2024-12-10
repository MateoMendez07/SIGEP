<?php
// controlador/FileUploadController.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';
require '../Modelo/modificarInfo.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class FileUploadController {
    private $niñoModel;

    public function __construct() {
        $this->niñoModel = new NiñoModel();
    }

    public function upload() {
        $mensaje = '';
        // Obtener el mes seleccionado del formulario
        $mesSeleccionado = isset($_POST['selectMonth']) ? $_POST['selectMonth'] : null;

        if (isset($_POST['upload']) && isset($_FILES['excelFile'])) {
            $file = $_FILES['excelFile']['tmp_name'];

            if (is_uploaded_file($file)) {
                try {
                    $spreadsheet = IOFactory::load($file);
                    $worksheet = $spreadsheet->getActiveSheet();

                    $filaInicio = 2;
                    foreach ($worksheet->getRowIterator($filaInicio) as $row) {
                        $filaIndex = $row->getRowIndex();

                        // Obtener valores y validar
                        $numero_nino = $this->getCellValue($worksheet, "A$filaIndex");
                        $nombre_completo = $this->getCellValue($worksheet, "B$filaIndex");
                        $aldea = $this->getCellValue($worksheet, "C$filaIndex");
                        $fecha_nacimiento = $this->convertExcelDateToYMD($worksheet, "D$filaIndex");
                        $comunidad = $this->getCellValue($worksheet, "E$filaIndex");
                        $genero = $this->getCellValue($worksheet, "F$filaIndex");
                        $estado_patrocinio = $this->getCellValue($worksheet, "G$filaIndex");
                        $fecha_inscripcion = $this->convertExcelDateToYMD($worksheet, "H$filaIndex");
                        $socio_local = $this->getCellValue($worksheet, "I$filaIndex");
                        $nombre_alianza = $this->getCellValue($worksheet, "J$filaIndex");
                        $nombre_contacto_principal = $this->getCellValue($worksheet, "K$filaIndex");

                        // Insertar datos en la base de datos
                        $resultado = $this->niñoModel->insertarNiño(
                            $numero_nino,
                            $nombre_completo,
                            $aldea,
                            $fecha_nacimiento,
                            $comunidad,
                            $genero,
                            $estado_patrocinio,
                            $fecha_inscripcion,
                            $socio_local,
                            $nombre_alianza,
                            $nombre_contacto_principal,
                            $mesSeleccionado // Pasar el mes seleccionado
                        );

                        // Registrar errores si los hay
                        if (strpos($resultado, "Error") !== false) {
                            $mensaje .= "Error al insertar datos en la fila $filaIndex: $resultado <br>";
                        }
                    }

                    if (empty($mensaje)) {
                        $mensaje = "Archivo procesado correctamente.";
                    }
                } catch (Exception $e) {
                    $mensaje = 'Error al procesar el archivo: ' . $e->getMessage();
                }
            } else {
                $mensaje = "Error al subir el archivo.";
            }
        }

        $_SESSION['mensaje'] = $mensaje;
        header('Location: ../Vista/ingresarInformacion.php');
        exit;
    }

    // Método auxiliar para obtener valores de celdas
    private function getCellValue($worksheet, $cellAddress) {
        $value = $worksheet->getCell($cellAddress)->getValue();
        return $value !== null ? trim($value) : null;
    }

    // Método auxiliar para convertir fechas de Excel a formato Y-m-d
    private function convertExcelDateToYMD($worksheet, $cellAddress) {
        $value = $this->getCellValue($worksheet, $cellAddress);

        if ($value !== null && is_numeric($value)) {
            try {
                $dateTime = PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return $dateTime->format('Y-m-d');
            } catch (Exception $e) {
                // Manejar valores no válidos
                error_log("Error al convertir fecha en $cellAddress: " . $e->getMessage());
                return null;
            }
        }

        return null;
    }


    // Método para modificar la información de los niños
    public function modificar() {
        $mensaje = '';
        if (isset($_POST['modificar'])) {
            $numero_nino = $_POST['numero_nino'];
            $nombre_completo = $_POST['nombre_completo'];
            $aldea = $_POST['aldea'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $comunidad = $_POST['comunidad'];
            $genero = $_POST['genero'];
            $estado_patrocinio = $_POST['estado_patrocinio'];
            $fecha_inscripcion = $_POST['fecha_inscripcion'];

            foreach ($numero_nino as $index => $num_nino) {
                $resultado = $this->niñoModel->actualizarNiño(
                    $num_nino, 
                    $nombre_completo[$index], 
                    $aldea[$index], 
                    $fecha_nacimiento[$index], 
                    $comunidad[$index], 
                    $genero[$index], 
                    $estado_patrocinio[$index], 
                    $fecha_inscripcion[$index]
                );

                if (strpos($resultado, "Error") !== false) {
                    $mensaje .= "Error al actualizar el niño con número: $num_nino. <br>";
                }
            }

            $mensaje .= "Información modificada correctamente.";
        }

        $_SESSION['mensaje'] = $mensaje;
        header('Location: ../Vista/modificarInformacion.php');
        exit;
    }

    // Método para obtener el historial de archivos subidos por mes
    public function getHistorialMensual($mesSeleccionado)
    {
        //$historial = $this->niñoModel->obtenerHistorialMensual($mesSeleccionado);
        //return $historial;
    }
}

// Crear una instancia del controlador y llamar al método según el caso
$controller = new FileUploadController();

if (isset($_POST['upload'])) {
    $controller->upload();
} elseif (isset($_POST['modificar'])) {
    $controller->modificar();
} elseif (isset($_GET['mes'])) {
    $mesSeleccionado = $_GET['mes'];
    $historial = $controller->getHistorialMensual($mesSeleccionado);
    // Aquí puedes enviar $historial a la vista para mostrarlo
}
?>