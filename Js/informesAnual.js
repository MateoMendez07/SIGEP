// Función para ver detalles usando el nuevo SP (ObtenerInformeAnualT)
function verDetallesAnualesT(anio) {
    if (!anio || isNaN(anio)) {
        console.error("Año inválido:", anio);
        alert("No se reconoce el año seleccionado.");
        return;
    }

    // Realizar la solicitud al servidor
    fetch(`../Ajax/informesAnualesA.php?op=obtenerInformeAnualT&anio=${anio}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const modalBody = document.querySelector("#modalDetalles .modal-body");
            modalBody.innerHTML = ""; // Limpiar contenido previo

            if (data.error) {
                // Mostrar error en caso de fallo en el backend
                modalBody.innerHTML = `<p>${data.error}</p>`;
            } else if (data.length === 0) {
                // Si no hay datos para el año seleccionado
                modalBody.innerHTML = `<p>No hay datos disponibles para el año seleccionado.</p>`;
            } else {
                // Construir la tabla con los datos obtenidos
                const table = document.createElement("table");
                table.classList.add("table", "table-bordered");
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Indicador</th>
                            ${data.map(row => `<th>${row.tipo_carta_nombre}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>Total Cartas por Enviar</td>${data.map(row => `<td>${row.total_cartas_por_enviar || 0}</td>`).join('')}</tr>
                        <tr><td>Cartas Recibidas en el Año</td>${data.map(row => `<td>${row.cartas_recibidas_anio || 0}</td>`).join('')}</tr>
                        <tr><td>Cartas Enviadas en el Año</td>${data.map(row => `<td>${row.enviadas_anio_actual || 0}</td>`).join('')}</tr>
                        <tr><td>Cartas Enviadas con Retraso</td>${data.map(row => `<td>${row.enviadas_con_retraso || 0}</td>`).join('')}</tr>
                        <tr><td>Porcentaje de Retraso</td>${data.map(row => `<td>${row.porcentaje_retraso || 0}%</td>`).join('')}</tr>
                        <tr><td>Promedio de Cumplimiento</td>${data.map(row => `<td>${row.promedio_cumplimiento_dias_envio || 0}%</td>`).join('')}</tr>
                        <tr><td>Por Enviar a la Fecha</td>${data.map(row => `<td>${row.por_enviar_a_fecha || 0}</td>`).join('')}</tr>
                        <tr><td>Por Enviar Fuera de Tiempo</td>${data.map(row => `<td>${row.por_enviar_fuera_tiempo || 0}</td>`).join('')}</tr>
                        <tr><td>Total Promotores/Gestores</td>${data.map(row => `<td>${row.total_promotores_gestores || 0}</td>`).join('')}</tr>
                    </tbody>
                `;
                modalBody.appendChild(table);
            }

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
            modal.show();
        })
        .catch(error => {
            console.error("Error al cargar los detalles:", error);
            alert("Hubo un problema al cargar los detalles del informe.");
        });
}

// Función para cargar los años disponibles
function cargarAnios() {
    fetch('../Ajax/informesAnualesA.php?op=obtenerAnios')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.querySelector("#tablaAnios tbody");
            tbody.innerHTML = ""; // Limpiar la tabla

            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="3">${data.error}</td></tr>`;
            } else if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3">No hay años disponibles.</td></tr>`;
            } else {
                data.forEach((item, index) => {
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.anio}</td>
                        <td>
                            <button class="btn btn-primary" onclick="verDetallesAnualesT(${item.anio})">Ver Detalles</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        })
        .catch(error => {
            console.error("Error al cargar los años:", error);
            const tbody = document.querySelector("#tablaAnios tbody");
            tbody.innerHTML = `<tr><td colspan="3">Error al cargar los años.</td></tr>`;
        });
}

// Función para generar PDF y mostrarlo fuera del modal
async function generarPDF(anio) {
    if (!anio || isNaN(anio)) {
        console.error("Año inválido:", anio);
        alert("No se reconoce el año seleccionado.");
        return;
    }

    try {
        const response = await fetch(`../Ajax/informesAnualesA.php?op=generarPDF&anio=${anio}`);
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
        }

        const blob = await response.blob();
        console.log("Tipo de archivo recibido:", blob.type);

        if (blob.type !== 'application/pdf') {
            const text = await blob.text();
            console.error("Contenido recibido:", text);
            alert("Error: " + text); // Muestra el contenido de la respuesta como alerta
            throw new Error('El archivo recibido no es un PDF.');
        }

        // Crear enlace de descarga y añadirlo al contenedor
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Informe_Anual_${anio}.pdf`;
        a.textContent = 'Haz clic aquí para descargar el PDF';
        a.classList.add('btn', 'btn-success', 'mt-3');

        const pdfContainer = document.querySelector("#pdfDescarga");
        if (pdfContainer) {
            pdfContainer.innerHTML = "";
            pdfContainer.appendChild(a);
        } else {
            console.error("El contenedor #pdfDescarga no existe en el DOM.");
        }
    } catch (error) {
        console.error("Error al generar el PDF:", error);
        alert("Hubo un problema al generar el PDF.");
    }
}

// Cargar los años al iniciar la página
document.addEventListener("DOMContentLoaded", cargarAnios);

// Cerrar modal cuando se presiona la 'X' o fuera del modal
$(document).on('click', '[data-dismiss="modal"]', function() {
    $('#modalDetalles').modal('hide');
});
