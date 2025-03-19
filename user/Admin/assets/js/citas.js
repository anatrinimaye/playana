// Variables globales
let citasTable;
let searchTimeout;

// Funciones de inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Cargado, iniciando carga de datos...');
    loadMedicos();
    loadPacientes();
    loadServicios();
    loadCitas();
    setupEventListeners();
});

function setupEventListeners() {
    // Búsqueda
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchCitas(e.target.value), 500);
    });

    // Formulario
    document.getElementById('citaForm').addEventListener('submit', submitForm);

    // Filtros
    document.getElementById('filterMedico').addEventListener('change', loadCitas);
    document.getElementById('filterFecha').addEventListener('change', loadCitas);
    document.getElementById('filterEstado').addEventListener('change', loadCitas);
}

// Funciones de carga de datos
function loadMedicos() {
    console.log('Cargando médicos...');
    fetch(BASE_URL + 'admin/modules/personal/ajax_handler.php?action=getAllActive')
        .then(response => {
            console.log('Respuesta del servidor:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos de médicos:', data);
            if (data.status === 'success') {
                const select = document.getElementById('medico_id');
                select.innerHTML = '<option value="">Seleccione un médico</option>';
                data.data.forEach(medico => {
                    select.innerHTML += `
                        <option value="${medico.id}">
                            ${medico.nombre} ${medico.apellidos}
                        </option>
                    `;
                });
            } else {
                throw new Error(data.message || 'Error al cargar médicos');
            }
        })
        .catch(error => {
            console.error('Error en loadMedicos:', error);
            showMessage('Error al cargar médicos: ' + error.message, 'error');
        });
}

function loadPacientes() {
    console.log('Cargando pacientes...');
    const url = `${BASE_URL}modules/citas/ajax_handler.php?action=getAll`;
    console.log('URL de la petición:', url);

    fetch(url)
        .then(response => {
            console.log('Respuesta completa:', response);
            return response.text(); // Cambiamos a text() para ver la respuesta exacta
        })
        .then(text => {
            console.log('Respuesta del servidor (texto):', text);
            try {
                const data = JSON.parse(text);
                console.log('Datos parseados:', data);
                if (data.status === 'success') {
                    const select = document.getElementById('paciente_id');
                    if (!select) {
                        throw new Error('No se encontró el elemento select de pacientes');
                    }
                    select.innerHTML = '<option value="">Seleccione un paciente</option>';
                    data.data.forEach(paciente => {
                        select.innerHTML += `
                            <option value="${paciente.id}">
                                ${paciente.nombre} ${paciente.apellidos}
                            </option>
                        `;
                    });
                } else {
                    throw new Error(data.message || 'Error al cargar pacientes');
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                console.error('Respuesta recibida:', text);
                throw new Error('Error al procesar la respuesta del servidor');
            }
        })
        .catch(error => {
            console.error('Error en loadPacientes:', error);
            showMessage('Error al cargar pacientes: ' + error.message, 'error');
        });
}

function loadServicios() {
    console.log('Cargando servicios...');
    fetch(BASE_URL + 'admin/modules/servicios/ajax_handler.php?action=getAll')
        .then(response => {
            console.log('Respuesta del servidor:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos de servicios:', data);
            if (data.status === 'success') {
                const select = document.getElementById('servicio_id');
                select.innerHTML = '<option value="">Seleccione un servicio</option>';
                data.data.forEach(servicio => {
                    select.innerHTML += `
                        <option value="${servicio.id}">
                            ${servicio.nombre}
                        </option>
                    `;
                });
            } else {
                throw new Error(data.message || 'Error al cargar servicios');
            }
        })
        .catch(error => {
            console.error('Error en loadServicios:', error);
            showMessage('Error al cargar servicios: ' + error.message, 'error');
        });
}

function loadCitas() {
    const medico = document.getElementById('filterMedico').value;
    const fecha = document.getElementById('filterFecha').value;
    const estado = document.getElementById('filterEstado').value;
    
    let url = 'ajax_handler.php?action=getAll';
    if (medico) url += `&medico=${medico}`;
    if (fecha) url += `&fecha=${fecha}`;
    if (estado) url += `&estado=${estado}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayCitas(data.data);
            } else {
                showMessage(data.message || 'Error al cargar citas', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar citas', 'error');
        });
}

function displayCitas(citas) {
    const tbody = document.querySelector('#citasTable tbody');
    tbody.innerHTML = '';
    
    citas.forEach(cita => {
        tbody.innerHTML += `
            <tr>
                <td>${formatDateTime(cita.fecha_hora)}</td>
                <td>${cita.nombre_paciente}</td>
                <td>${cita.nombre_medico}</td>
                <td>${cita.servicio || 'N/A'}</td>
                <td>
                    <span class="status-badge ${getStatusClass(cita.estado)}">
                        ${cita.estado}
                    </span>
                </td>
                <td class="actions">
                    <button onclick="editCita(${cita.id})" class="btn-edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteCita(${cita.id})" class="btn-delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

function setupHorarioListeners() {
    const medicoSelect = document.getElementById('medico_id');
    const fechaInput = document.getElementById('fecha');
    
    [medicoSelect, fechaInput].forEach(element => {
        element.addEventListener('change', cargarHorariosDisponibles);
    });
}

function cargarHorariosDisponibles() {
    const medico_id = document.getElementById('medico_id').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!medico_id || !fecha) return;

    fetch(`ajax_handler.php?action=getHorariosDisponibles&medico_id=${medico_id}&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('hora');
                select.innerHTML = '<option value="">Seleccione una hora</option>';
                
                data.data.forEach(hora => {
                    select.innerHTML += `<option value="${hora}">${formatTime(hora)}</option>`;
                });
            } else {
                showMessage('No hay horarios disponibles para esta fecha', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar horarios', 'error');
        });
}

function setupForm() {
    document.getElementById('citaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('id').value;
        const submitButton = e.submitter;
        const action = submitButton.dataset.action;
        
        formData.append('action', id ? 'update' : 'create');

        fetch('ajax_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                
                if (action === 'save-return') {
                    closeModal();
                } else if (action === 'save-new') {
                    this.reset();
                    document.getElementById('id').value = '';
                }
                
                loadCitas();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            showMessage('Error en la operación', 'error');
            console.error(error);
        });
    });
}

function openModal(isEdit = false) {
    const modal = document.getElementById('citaModal');
    document.getElementById('modalTitle').textContent = isEdit ? 'Editar Cita' : 'Nueva Cita';
    if (!isEdit) {
        document.getElementById('citaForm').reset();
        document.getElementById('id').value = '';
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('citaModal').style.display = 'none';
    document.getElementById('citaForm').reset();
}

function loadCitaData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const cita = data.data;
                document.getElementById('id').value = cita.id;
                document.getElementById('paciente_id').value = cita.paciente_id;
                document.getElementById('medico_id').value = cita.medico_id;
                document.getElementById('servicio_id').value = cita.servicio_id;
                document.getElementById('fecha').value = cita.fecha;
                document.getElementById('hora').value = cita.hora;
                document.getElementById('estado').value = cita.estado;
                document.getElementById('notas').value = cita.notas;
            }
        })
        .catch(error => showMessage('Error al cargar datos de la cita', 'error'));
}

function deleteCita(id) {
    if (confirm('¿Está seguro de eliminar esta cita?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('ajax_handler.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                loadCitas();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al eliminar', 'error');
        });
    }
}

function setupFilters() {
    const filterFecha = document.getElementById('filterFecha');
    const filterMedico = document.getElementById('filterMedico');
    const filterEstado = document.getElementById('filterEstado');

    [filterFecha, filterMedico, filterEstado].forEach(filter => {
        filter.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const fecha = document.getElementById('filterFecha').value;
    const medico = document.getElementById('filterMedico').value;
    const estado = document.getElementById('filterEstado').value;

    let url = 'ajax_handler.php?action=filtrar';
    if (fecha) url += `&fecha=${fecha}`;
    if (medico) url += `&medico=${medico}`;
    if (estado) url += `&estado=${estado}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#citasTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(cita => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${formatDateTime(cita.fecha_hora)}</td>
                            <td>${cita.nombre_paciente}</td>
                            <td>${cita.nombre_medico}</td>
                            <td>${cita.nombre_servicio}</td>
                            <td>
                                <span class="status-badge ${getStatusClass(cita.estado)}">
                                    ${cita.estado}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editCita(${cita.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteCita(${cita.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al aplicar filtros', 'error'));
}

function setupSearch() {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#citasTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function getStatusClass(estado) {
    const classes = {
        'Pendiente': 'pending',
        'Confirmada': 'confirmed',
        'Completada': 'completed',
        'Cancelada': 'cancelled'
    };
    return classes[estado] || '';
}

function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatTime(timeStr) {
    return timeStr.substring(0, 5);
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
    if (!container) {
        console.error('No se encontró el contenedor de mensajes');
        return;
    }

    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    container.innerHTML = '';
    container.appendChild(alert);
    
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => container.innerHTML = '', 300);
    }, 3000);
}

function searchCitas(searchTerm) {
    const rows = document.querySelectorAll('#citasTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
}

function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    console.log('Datos del formulario:', Object.fromEntries(formData));
    const id = document.getElementById('id').value;
    
    fetch('ajax_handler.php?action=' + (id ? 'update' : 'create'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showMessage(data.message, 'success');
            closeModal();
            loadCitas();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error en la operación', 'error');
    });
}

function editCita(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                fillForm(data.data);
                openModal(true);
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar datos', 'error');
        });
}

function deleteCita(id) {
    if (confirm('¿Está seguro de eliminar esta cita?')) {
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('ajax_handler.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                loadCitas();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al eliminar', 'error');
        });
    }
}

function cargarHorariosDisponibles() {
    const medico_id = document.getElementById('medico_id').value;
    const fecha = document.getElementById('fecha').value;
    
    if (!medico_id || !fecha) return;
    
    fetch(`ajax_handler.php?action=getHorariosDisponibles&medico_id=${medico_id}&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('hora');
                select.innerHTML = '<option value="">Seleccione una hora</option>';
                
                data.data.forEach(hora => {
                    select.innerHTML += `<option value="${hora}">${formatTime(hora)}</option>`;
                });
            } else {
                showMessage('No hay horarios disponibles para esta fecha', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar horarios', 'error');
        });
}

function fillForm(data) {
    document.getElementById('id').value = data.id;
    document.getElementById('paciente_id').value = data.paciente_id;
    document.getElementById('medico_id').value = data.medico_id;
    document.getElementById('fecha').value = data.fecha;
    document.getElementById('hora').value = data.hora;
    document.getElementById('motivo').value = data.motivo || '';
    document.getElementById('estado').value = data.estado;
} 