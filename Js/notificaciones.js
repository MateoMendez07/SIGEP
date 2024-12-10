$(document).ready(function() {
    // Inicializar la tabla de DataTables con la fuente de datos AJAX
    var table = $('#tablaNotificaciones').DataTable({
        "ajax": {
            "url": '../Ajax/notificacionesA.php',
            "type": 'GET',
            "dataSrc": function(json) {
                // Filtrar filas con nombre_gestor no vacío ni nulo
                return json.filter(row => row.nombre_gestor && row.nombre_gestor.trim() !== '');
            }
        },
        "columns": [
            {
                "data": "nombre_gestor",
                "render": function(data, type, row, meta) {
                    // Mostrar el nombre solo si es la primera fila o cambia en comparación con la fila anterior
                    if (meta.row === 0 || table.row(meta.row - 1).data().nombre_gestor !== data) {
                        return data;
                    } else {
                        return ''; // Ocultar si es igual al de la fila anterior
                    }
                }
            },
            {
                "data": null,
                "defaultContent": '<button class="btn btn-info btn-sm">Ver Detalles</button>'  // Botón para mostrar los detalles
            }
        ]
    });

    // Manejar el evento de clic en el botón de detalles
    $('#tablaNotificaciones tbody').on('click', 'button', function() {
        // Limpiar el contenido del modal antes de agregar los nuevos detalles
        $('#modalDetalles').find('.modal-body').html('');

        // Obtener los datos de la fila seleccionada
        var data = table.row($(this).parents('tr')).data();
        var gestorNombre = data.nombre_gestor;

        // Verificar si los datos están disponibles
        if (data) {
            var modalBody = $('#modalDetalles').find('.modal-body');

            // Filtrar todas las notificaciones con el mismo gestor
            var allDetails = table.rows().data().filter(function(row) {
                return row.nombre_gestor === gestorNombre;
            });

            var seen = new Set();
            var detailsContent = `<h5>${gestorNombre}</h5>`;

            allDetails.each(function(row) {
                // Crear una clave única para cada niño (puede incluir el número de niño o el nombre)
                var uniqueKey = row.codigo_mcs + '-' + row.nombre_nino;
                if (!seen.has(uniqueKey)) {
                    seen.add(uniqueKey);
                    detailsContent += `
                        <p><strong>Nombre del Niño:</strong> ${row.nombre_nino}</p>
                        <p><strong>Número del Niño:</strong> ${row.codigo_mcs}</p>
                        <p><strong>Observaciones:</strong> ${row.observaciones}</p>
                        <hr>
                    `;
                }
            });

            // Solo agregar detalles si no se ha añadido ya información para este gestor
            if (detailsContent.trim() !== `<h5>${gestorNombre}</h5>`) {
                modalBody.append(detailsContent);
            }

            // Mostrar el modal
            $('#modalDetalles').modal('show');
        } else {
            console.error('No se encontraron datos para mostrar en el modal.');
        }
    });
});

// Código del modal
$(document).ready(function() {
    $('body').append(`
        <!-- Modal -->
        <div class="modal fade" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="modalDetallesLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetallesLabel">Detalles de las Notificaciones</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Contenido dinámico se agregará aquí -->
                    </div>
                </div>
            </div>
        </div>
    `);
});
