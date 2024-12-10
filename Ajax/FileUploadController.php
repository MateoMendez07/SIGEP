<?php
// controlador/FileUploadController.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php'; // Asegúrate de cargar el autoloader de Composer
require '../Modelo/ingresarInformacionM.php'; // Incluir el modelo

use PhpOffice\PhpSpreadsheet\IOFactory;

class FileUploadController {
    private $niñoModel;

    public function __construct() {
        $this->niñoModel = new NiñoModel();
    }

    public function upload() {
        $mensaje = ''; // Variable para almacenar mensajes

        if (isset($_POST['upload']) && isset($_FILES['excelFile'])) {
            $file = $_FILES['excelFile']['tmp_name'];

            if (is_uploaded_file($file)) {
                // Cargar el archivo Excel
                try {
                    $spreadsheet = IOFactory::load($file);
                    $worksheet = $spreadsheet->getActiveSheet();

                    // Iterar sobre las filas del archivo Excel
                    $filaInicio = 2; // Suponiendo que la primera fila es de encabezados
                    foreach ($worksheet->getRowIterator($filaInicio) as $row) {
                        // Obtener las celdas de cada fila
                        $numero_nino = $worksheet->getCell("A" . $row->getRowIndex())->getValue();
                        $nombre_completo = $worksheet->getCell("B" . $row->getRowIndex())->getValue();
                        $aldea = $worksheet->getCell("C" . $row->getRowIndex())->getValue();

                        // Convertir la fecha de Excel a un formato de fecha
                        $fecha_nacimiento = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($worksheet->getCell("D" . $row->getRowIndex())->getValue())->format('Y-m-d');
                        $comunidad = $worksheet->getCell("E" . $row->getRowIndex())->getValue();
                        $genero = $worksheet->getCell("F" . $row->getRowIndex())->getValue();
                        $estado_patrocinio = $worksheet->getCell("G" . $row->getRowIndex())->getValue();
                        $fecha_inscripcion = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($worksheet->getCell("H" . $row->getRowIndex())->getValue())->format('Y-m-d');
                        $socio_local = $worksheet->getCell("I" . $row->getRowIndex())->getValue();
                        $nombre_alianza = $worksheet->getCell("J" . $row->getRowIndex())->getValue();
                        $nombre_contacto_principal = $worksheet->getCell("K" . $row->getRowIndex())->getValue();

                        // Llamar al modelo para insertar los datos
                        $resultado = $this->niñoModel->insertarNiño($numero_nino, $nombre_completo, $aldea, $fecha_nacimiento, $comunidad, $genero, $estado_patrocinio, $fecha_inscripcion, $socio_local, $nombre_alianza, $nombre_contacto_principal);

                        // Manejar el resultado de la inserción
                        if (strpos($resultado, "Error") !== false) {
                            $mensaje .= "Error al insertar datos en la fila " . $row->getRowIndex() . ": " . $resultado . "<br>";
                        }
                    }

                    $mensaje .= "Archivo procesado correctamente.";
                } catch (Exception $e) {
                    $mensaje = 'Error al procesar el archivo: ' . $e->getMessage();
                }
            } else {
                $mensaje = "Error al subir el archivo.";
            }
        }

        // Redirigir a la vista
        $_SESSION['mensaje'] = $mensaje; // Almacenar el mensaje en la sesión
        header('Location: ../Vista/ingresarInformacion.php'); // Redirigir a la vista
        exit; // Asegurarse de salir después de redirigir
    }
}

// Crear una instancia del controlador y llamar al método upload
$controller = new FileUploadController();
$controller->upload();
?>
