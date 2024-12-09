$(document).ready(function () {
    // Inicializamos DataTable
    var table = $('#tablaNotificaciones').DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
        },
        "order": [[0, 'desc']],
        "data": [],
        "columns": [
            { "data": "codigo_mcs" },             // Código MCS
            { "data": "nombre_nino" },           // Nombre del niño
            { "data": "observaciones" },         // Observaciones
            { "data": "nombre_gestor" }        // Nombre del gestor
        ]
    });

    // Solicitud AJAX para cargar las notificaciones
    $.ajax({
        url: '../Ajax/notificacionesA.php',  // La URL del controlador en PHP
        method: 'GET',                      // Método GET
        success: function (data) {
            try {
                // Validamos si la respuesta es un string y la convertimos
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }

                // Comprobamos si hay un error en la respuesta
                if (data.error) {
                    console.error("Error desde el servidor: ", data.error);
                    alert("Error: " + data.error); // Opcional: mostrar mensaje al usuario
                } else {
                    // Cargamos los datos en la tabla
                    table.clear();
                    table.rows.add(data);
                    table.draw();
                }
            } catch (e) {
                console.error("Error al parsear JSON: ", e);
                alert("Error al procesar los datos del servidor.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar los datos: ", error);
            alert("Error al comunicarse con el servidor.");
        }
    });
});
