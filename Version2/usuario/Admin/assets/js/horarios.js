// Variables globales
let horariosTable;
let searchTimeout;

// Funciones de inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('Iniciando carga de horarios...');
    if (typeof BASE_URL === 'undefined') {
        console.error('BASE_URL no está definida');
        return;
    }
    loadHorarios();
    loadMedicos();
    setupEventListeners();
});

function setupEventListeners() {
    // Búsqueda
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchHorarios(e.target.value), 500);
    });

    // Formulario
    document.getElementById('horarioForm').addEventListener('submit', submitForm);

    // Filtros
    document.getElementById('filterMedico').addEventListener('change', loadHorarios);
    document.getElementById('filterDia').addEventListener('change', loadHorarios);
    document.getElementById('filterEstado').addEventListener('change', loadHorarios);
}

// Funciones de carga de datos
function loadHorarios() {
    const medico = document.getElementById('filterMedico').value;
    const dia = document.getElementById('filterDia').value;
    const estado = document.getElementById('filterEstado').value;
    
    let url = 'ajax_handler.php?action=filtrar';
    if (medico) url += `&medico=${medico}`;
    if (dia) url += `&dia=${dia}`;
    if (estado) url += `&estado=${estado}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayHorarios(data.data);
            } else {
                showMessage(data.message || 'Error al cargar horarios', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error al cargar horarios', 'error');
        });
}

function loadMedicos() {
    console.log('Cargando médicos para horarios...');
    const url = `${BASE_URL}modules/personal/ajax_handler.php?action=getAllActive`;
    console.log('URL de la petición:', url);
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            console.log('Respuesta del servidor:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos de médicos recibidos:', data);
            if (data.status === 'success') {
                const select = document.getElementById('medico_id');
                const filterSelect = document.getElementById('filterMedico');
                
                if (!select) {
                    console.error('No se encontró el elemento select medico_id');
                    return;
                }
                
                const options = '<option value="">Seleccione un médico</option>' +
                    data.data.map(medico => 
                        `<option value="${medico.id}">
                            ${medico.nombre} ${medico.apellidos} - ${medico.especialidad || 'Sin especialidad'}
                        </option>`
                    ).join('');
                
                select.innerHTML = options;
                if (filterSelect) {
                    filterSelect.innerHTML = '<option value="">Todos los médicos</option>' + options;
                }
            } else {
                throw new Error(data.message || 'Error al cargar médicos');
            }
        })
        .catch(error => {
            console.error('Error al cargar médicos:', error);
            showMessage('Error al cargar médicos: ' + error.message, 'error');
        });
}

function displayHorarios(horarios) {
                const tbody = document.querySelector('#horariosTable tbody');
                tbody.innerHTML = '';
                
    horarios.forEach(horario => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${horario.nombre_medico}</td>
                            <td>${getDayName(horario.dia_semana)}</td>
                            <td>${formatTime(horario.hora_inicio)}</td>
                            <td>${formatTime(horario.hora_fin)}</td>
                            <td>
                                <span class="status-badge ${horario.estado === '1' ? 'activo' : 'inactivo'}">
                                    ${horario.estado === '1' ? 'Activo' : 'Inactivo'}
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editHorario(${horario.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteHorario(${horario.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
}

// Funciones de formulario
function submitForm(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
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
            loadHorarios();
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error en la operación', 'error');
    });
}

function editHorario(id) {
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

function deleteHorario(id) {
    if (confirm('¿Está seguro de eliminar este horario?')) {
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
                loadHorarios();
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

// Funciones auxiliares
function fillForm(data) {
    document.getElementById('id').value = data.id;
    document.getElementById('medico_id').value = data.medico_id;
    document.getElementById('dia_semana').value = data.dia_semana;
    document.getElementById('hora_inicio').value = data.hora_inicio;
    document.getElementById('hora_fin').value = data.hora_fin;
    document.getElementById('estado').value = data.estado;
}

function openModal(isEdit = false) {
    const modal = document.getElementById('horarioModal');
    document.getElementById('modalTitle').textContent = isEdit ? 'Editar Horario' : 'Nuevo Horario';
    if (!isEdit) {
        document.getElementById('horarioForm').reset();
        document.getElementById('id').value = '';
    }
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('horarioModal').style.display = 'none';
    document.getElementById('horarioForm').reset();
}

function showMessage(message, type) {
    const container = document.getElementById('message-container');
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

function searchHorarios(query) {
    const rows = document.querySelectorAll('#horariosTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
    });
}

function getDayName(dayNumber) {
    const days = {
        '1': 'Domingo',
        '2': 'Lunes',
        '3': 'Martes',
        '4': 'Miércoles',
        '5': 'Jueves',
        '6': 'Viernes',
        '7': 'Sábado'
    };
    return days[dayNumber] || '';
}

function formatTime(timeStr) {
    return timeStr.substring(0, 5);
} 