// Función para ver detalles usando el nuevo SP (ObtenerInformeAnualT)
async function verDetallesAnualesT(anio) {
    if (!anio || isNaN(anio)) {
        console.error("Año inválido:", anio);
        alert("No se reconoce el año seleccionado.");
        return;
    }

    try {
        const response = await fetch(`../Ajax/informesAnualesA.php?op=obtenerInformeAnualT&anio=${anio}`);
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
        }

        const data = await response.json();
        const modalBody = document.querySelector("#modalDetalles .modal-body");
        modalBody.innerHTML = ""; // Limpiar contenido previo

        if (data.error) {
            modalBody.innerHTML = `<p>${data.error}</p>`;
        } else if (data.length === 0) {
            modalBody.innerHTML = `<p>No hay datos disponibles para el año seleccionado.</p>`;
        } else {
            // Crear la tabla con los datos obtenidos
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
                    ${crearFilaTabla("Total Cartas por Enviar", data.map(row => row.total_cartas_por_enviar || 0))}
                    ${crearFilaTabla("Cartas Recibidas en el Año", data.map(row => row.cartas_recibidas_anio || 0))}
                    ${crearFilaTabla("Cartas Enviadas en el Año", data.map(row => row.enviadas_anio_actual || 0))}
                    ${crearFilaTabla("Cartas Enviadas con Retraso", data.map(row => row.enviadas_con_retraso || 0))}
                    ${crearFilaTabla("Porcentaje de Retraso", data.map(row => `${row.porcentaje_retraso || 0}%`))}
                    ${crearFilaTabla("Promedio de Cumplimiento", data.map(row => `${row.promedio_cumplimiento_dias_envio || 0}%`))}
                    ${crearFilaTabla("Por Enviar a la Fecha", data.map(row => row.por_enviar_a_fecha || 0))}
                    ${crearFilaTabla("Por Enviar Fuera de Tiempo", data.map(row => row.por_enviar_fuera_tiempo || 0))}
                    ${crearFilaTabla("Total Promotores/Gestores", data.map(row => row.total_promotores_gestores || 0))}
                </tbody>
            `;
            modalBody.appendChild(table);

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
            modal.show();
        }
    } catch (error) {
        console.error("Error al cargar los detalles:", error);
        alert("Hubo un problema al cargar los detalles del informe.");
    }
}

// Función para crear una fila de la tabla
function crearFilaTabla(indicador, valores) {
    return `
        <tr>
            <td>${indicador}</td>
            ${valores.map(valor => `<td>${valor}</td>`).join('')}
        </tr>
    `;
}

// Función para cargar los años disponibles
async function cargarAnios() {
    try {
        const response = await fetch('../Ajax/informesAnualesA.php?op=obtenerAnios');
        if (!response.ok) {
            throw new Error(`Error en la respuesta del servidor: ${response.statusText}`);
        }

        const data = await response.json();
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
                        <button class="btn btn-primary" onclick="generarPDF(${item.anio})">PDF</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }
    } catch (error) {
        console.error("Error al cargar los años:", error);
        const tbody = document.querySelector("#tablaAnios tbody");
        tbody.innerHTML = `<tr><td colspan="3">Error al cargar los años.</td></tr>`;
    }
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
