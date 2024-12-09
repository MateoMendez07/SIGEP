// Función para cargar los semestres disponibles
function cargarSemestres() {
    fetch('../Ajax/informesSemestralA.php?op=obtenerSemestres')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            const tbody = document.querySelector("#tablaSemestres tbody");
            tbody.innerHTML = ""; // Limpiar la tabla

            if (data.error) {
                tbody.innerHTML = `<tr><td colspan="3">${data.error}</td></tr>`;
                return;
            }

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3">No hay semestres disponibles.</td></tr>`;
                return;
            }

            data.forEach((semestreObj, index) => {
                const semestre = semestreObj.semestre_disponible;

                // Validar el formato del semestre
                if (!semestre || typeof semestre !== 'string' || !/^\d{4}-(1|2)$/.test(semestre.trim())) {
                    console.warn(`Semestre inválido:`, semestreObj);
                    return;
                }

                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${semestre.trim()}</td>
                    <td>
                        <button class="btn btn-primary" onclick="verDetalles('${semestre.trim()}')">Ver Detalles</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error("Error al cargar los semestres:", error);
            const tbody = document.querySelector("#tablaSemestres tbody");
            tbody.innerHTML = `<tr><td colspan="3">Error al cargar los semestres.</td></tr>`;
        });
}

// Función para ver los detalles del semestre
function verDetalles(semestre) {
    if (!/^\d{4}-(1|2)$/.test(semestre)) {
        console.error("Formato inválido para el semestre:", semestre);
        alert("Formato de semestre inválido. Por favor, selecciona un semestre válido.");
        return;
    }

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    modal.show();

    // Limpiar cualquier contenido previo en el modal
    const modalBody = document.querySelector("#modalDetalles .modal-body");
    modalBody.innerHTML = '';  // Limpiar el contenido anterior

    // Ahora pasamos el semestre al backend
    fetch(`../Ajax/informesSemestralA.php?op=obtenerInformeSemestral&semestre=${encodeURIComponent(semestre)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            // Verificar si hay un error en la respuesta
            if (data.error) {
                modalBody.innerHTML = `<p><strong>Error:</strong> ${data.error}</p>`;  // Mostrar el error en el modal
                return;
            }

            // Verificar si data es un arreglo
            if (!Array.isArray(data)) {
                modalBody.innerHTML = `<p><strong>Error:</strong> Datos no válidos recibidos.</p>`;
                return;
            }

            // Si es un arreglo, construir la tabla con los datos obtenidos
            const table = document.createElement("table");
            table.classList.add("table", "table-bordered");

            const headers = data.map(row => `<th>${row.tipo_carta_nombre}</th>`).join('');
            const rows = `
                <tr><td>Total Cartas por Enviar</td>${data.map(row => `<td>${row.total_cartas_por_enviar || 0}</td>`).join('')}</tr>
                <tr><td>Cartas Recibidas en el Año</td>${data.map(row => `<td>${row.cartas_recibidas_anio || 0}</td>`).join('')}</tr>
                <tr><td>Cartas Enviadas en el Año</td>${data.map(row => `<td>${row.enviadas_anio_actual || 0}</td>`).join('')}</tr>
                <tr><td>Cartas Enviadas con Retraso</td>${data.map(row => `<td>${row.enviadas_con_retraso || 0}</td>`).join('')}</tr>
                <tr><td>Porcentaje de Retraso</td>${data.map(row => `<td>${row.porcentaje_retraso || 0}%</td>`).join('')}</tr>
                <tr><td>Promedio de Cumplimiento</td>${data.map(row => `<td>${row.promedio_cumplimiento_dias_envio || 0}%</td>`).join('')}</tr>
                <tr><td>Por Enviar a la Fecha</td>${data.map(row => `<td>${row.por_enviar_a_fecha || 0}</td>`).join('')}</tr>
                <tr><td>Por Enviar Fuera de Tiempo</td>${data.map(row => `<td>${row.por_enviar_fuera_tiempo || 0}</td>`).join('')}</tr>
                <tr><td>Total Promotores/Gestores</td>${data.map(row => `<td>${row.total_promotores_gestores || 0}</td>`).join('')}</tr>
            `;

            table.innerHTML = `
                <thead>
                    <tr>
                        <th>Indicador</th>${headers}
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            `;
            
            modalBody.appendChild(table);  // Agregar la nueva tabla al modal
        })
        .catch(error => {
            console.error("Error al cargar los detalles:", error);
            modalBody.innerHTML = `<p>Error al cargar los detalles del semestre. ${error.message}</p>`;
        });
}

// Cargar los semestres al iniciar la página
document.addEventListener("DOMContentLoaded", cargarSemestres);
