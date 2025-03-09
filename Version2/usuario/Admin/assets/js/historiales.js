document.addEventListener('DOMContentLoaded', function() {
    loadHistoriales();
    loadPacientes();
    loadMedicos();
    setupSearch();
    setupFilters();
    setupForm();
});

function loadHistoriales() {
    fetch('ajax_handler.php?action=getAll')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#historialesTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(historial => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${formatDateTime(historial.fecha)}</td>
                            <td>${historial.nombre_paciente}</td>
                            <td>${historial.nombre_medico}</td>
                            <td>${historial.motivo_consulta.substring(0, 50)}...</td>
                            <td>${historial.diagnostico.substring(0, 50)}...</td>
                            <td class="actions">
                                <button onclick="editHistorial(${historial.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteHistorial(${historial.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printHistorial(${historial.id})" class="btn-print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => showMessage('Error al cargar historiales', 'error'));
}

function loadPacientes() {
    fetch('ajax_handler.php?action=getPacientes')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('paciente_id');
                const filterSelect = document.getElementById('filterPaciente');
                const options = '<option value="">Seleccione un paciente</option>' +
                    data.data.map(paciente => 
                        `<option value="${paciente.id}">${paciente.nombre_completo}</option>`
                    ).join('');
                
                select.innerHTML = options;
                filterSelect.innerHTML = '<option value="">Todos</option>' + options;
            }
        })
        .catch(error => showMessage('Error al cargar pacientes', 'error'));
}

function loadMedicos() {
    fetch('ajax_handler.php?action=getMedicos')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const select = document.getElementById('medico_id');
                const filterSelect = document.getElementById('filterMedico');
                const options = '<option value="">Seleccione un médico</option>' +
                    data.data.map(medico => 
                        `<option value="${medico.id_personal}">${medico.nombre_completo}</option>`
                    ).join('');
                
                select.innerHTML = options;
                filterSelect.innerHTML = '<option value="">Todos</option>' + options;
            }
        })
        .catch(error => showMessage('Error al cargar médicos', 'error'));
}

function setupForm() {
    document.getElementById('historialForm').addEventListener('submit', function(e) {
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
                
                loadHistoriales();
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

function openModal(id = null) {
    const modal = document.getElementById('historialModal');
    const form = document.getElementById('historialForm');
    modal.style.display = 'block';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Editar Historial';
        loadHistorialData(id);
    } else {
        document.getElementById('modalTitle').textContent = 'Nuevo Historial';
        form.reset();
        document.getElementById('id').value = '';
    }
}

function closeModal() {
    const modal = document.getElementById('historialModal');
    modal.style.display = 'none';
    document.getElementById('historialForm').reset();
}

function loadHistorialData(id) {
    fetch(`ajax_handler.php?action=getById&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const historial = data.data;
                document.getElementById('id').value = historial.id;
                document.getElementById('paciente_id').value = historial.paciente_id;
                document.getElementById('medico_id').value = historial.medico_id;
                document.getElementById('motivo_consulta').value = historial.motivo_consulta;
                document.getElementById('antecedentes').value = historial.antecedentes;
                document.getElementById('examen_fisico').value = historial.examen_fisico;
                document.getElementById('diagnostico').value = historial.diagnostico;
                document.getElementById('tratamiento').value = historial.tratamiento;
                document.getElementById('observaciones').value = historial.observaciones;
            }
        })
        .catch(error => showMessage('Error al cargar datos del historial', 'error'));
}

function deleteHistorial(id) {
    if (confirm('¿Está seguro de eliminar este historial médico?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        fetch('ajax_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showMessage(data.message, 'success');
                loadHistoriales();
            } else {
                showMessage(data.message, 'error');
            }
        })
        .catch(error => showMessage('Error al eliminar', 'error'));
    }
}

function setupFilters() {
    const filterFecha = document.getElementById('filterFecha');
    const filterMedico = document.getElementById('filterMedico');
    const filterPaciente = document.getElementById('filterPaciente');

    [filterFecha, filterMedico, filterPaciente].forEach(filter => {
        filter.addEventListener('change', aplicarFiltros);
    });
}

function aplicarFiltros() {
    const fecha = document.getElementById('filterFecha').value;
    const medico = document.getElementById('filterMedico').value;
    const paciente = document.getElementById('filterPaciente').value;

    let url = 'ajax_handler.php?action=filtrar';
    if (fecha) url += `&fecha=${fecha}`;
    if (medico) url += `&medico=${medico}`;
    if (paciente) url += `&paciente=${paciente}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.querySelector('#historialesTable tbody');
                tbody.innerHTML = '';
                
                data.data.forEach(historial => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${formatDateTime(historial.fecha)}</td>
                            <td>${historial.nombre_paciente}</td>
                            <td>${historial.nombre_medico}</td>
                            <td>${historial.motivo_consulta.substring(0, 50)}...</td>
                            <td>${historial.diagnostico.substring(0, 50)}...</td>
                            <td class="actions">
                                <button onclick="editHistorial(${historial.id})" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteHistorial(${historial.id})" class="btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button onclick="printHistorial(${historial.id})" class="btn-print">
                                    <i class="fas fa-print"></i>
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
        const rows = document.querySelectorAll('#historialesTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}

function printHistorial(id) {
    window.open(`print_historial.php?id=${id}`, '_blank');
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